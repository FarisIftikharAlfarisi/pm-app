<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Site;
use App\Models\User;
use App\Models\Absensi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AbsensiController extends Controller
{
    /**
     * Menampilkan form absensi
     */
    public function index()
    {
        $now = Carbon::now();
        date_default_timezone_set('Asia/Jakarta');

        $jamMasukStart = today()->setTime(8, 0, 0);
        $jamMasukEnd = today()->setTime(9, 0, 0);
        $jamPulangStart = today()->setTime(15, 0, 0);
        $jamPulangEnd = today()->setTime(16, 0, 0);

        // Hitung kondisi waktu
        $isAbsenMasukTime = $now->between($jamMasukStart, $jamMasukEnd);
        $isAbsenPulangTime = $now->between($jamPulangStart, $jamPulangEnd);

        // Ambil data karyawan per site

        return view('ABSEN.absensi', compact('now', 'jamMasukStart', 'jamMasukEnd', 'jamPulangStart', 'jamPulangEnd', 'isAbsenMasukTime', 'isAbsenPulangTime'));
    }

    /**
     * Menyimpan data absensi
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'absen_type' => 'required|in:masuk,pulang',
            'Kode_Karyawan' => 'required|exists:users,Kode_Karyawan',
            'latitude' => 'required',
            'longitude' => 'required',
            'selfie' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {

            $tipe_absen = Str::upper($request->absen_type);

            $karyawan = User::where('Kode_Karyawan', $request->Kode_Karyawan)->first();
            $siteId = $karyawan->userDetail->Kode_Site ?? null;

            // Simpan foto selfie
            $selfiePath = $this->storeSelfie($request->file('selfie'), $karyawan->Kode_Karyawan, $tipe_absen);

            if ($request->absen_type === 'masuk') {
                // Cek apakah sudah ada absen masuk hari ini
                $existingAbsensi = Absensi::where('karyawan_id', $karyawan->id)
                    ->whereDate('absen_masuk', today())
                    ->first();

                if ($existingAbsensi) {
                    return redirect()->back()->with('error', 'Anda sudah melakukan absen masuk hari ini.');
                }

                // Buat record absensi baru
                $absensi = Absensi::create([
                    'karyawan_id' => $karyawan->id,
                    'site_id' => $siteId,
                    'latitude_masuk' => $request->latitude,
                    'longitude_masuk' => $request->longitude,
                    'absen_masuk' => now(),
                    'status_kehadiran' => 'hadir',
                    'selfie_path' => $selfiePath,
                    'creator_id' => Auth::user()->id,
                ]);

                return redirect()->back()->with('success', 'Absen masuk berhasil dicatat.');
            } else {
                // Cari record absen masuk untuk diupdate
                $absensi = Absensi::where('karyawan_id', $karyawan->id)
                    ->whereDate('absen_masuk', today())
                    ->first();

                if (!$absensi) {
                    return redirect()->back()->with('error', 'Anda belum melakukan absen masuk hari ini.');
                }

                if ($absensi->absen_keluar) {
                    return redirect()->back()->with('error', 'Anda sudah melakukan absen pulang hari ini.');
                }

                // Hitung jam kerja
                $jamKerja = $this->calculateWorkingHours($absensi->absen_masuk, now());

                // Update record absensi
                $absensi->update([
                    'latitude_pulang' => $request->latitude,
                    'longitude_pulang' => $request->longitude,
                    'absen_keluar' => now(),
                    'jam_kerja' => $jamKerja,
                    'selfie_path_pulang' => $selfiePath,
                ]);

                return redirect()->back()->with('success', 'Absen pulang berhasil dicatat.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menghitung jam kerja dalam menit
     */
    private function calculateWorkingHours($start, $end)
    {
        return $start->diffInMinutes($end);
    }

    /**
     * Menyimpan foto selfie
     */
    private function storeSelfie($file, $employeeCode, $absenceType)
    {
        $fileName = 'ABSEN_'. $absenceType . '_' . $employeeCode . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('AbsensiKaryawan', $fileName, 'public');

        return $path;
    }

    /**
     * Menampilkan daftar absensi
     */
    // public function index()
    // {
    //     $absensis = Absensi::with(['karyawan', 'site'])
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(10);

    //     return view('ABSEN.absensi');
    // }

    /**
     * Menghapus data absensi (soft delete)
     */
    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);

        // Hanya admin atau creator yang bisa menghapus
        if (Auth::user()->role === 'ADMIN_SITE' || Auth::user()->id === $absensi->creator_id) {
            $absensi->delete();
            return redirect()->route('ABSEN.index')->with('success', 'Data absensi berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus data ini.');
    }

    /**
     * Menampilkan form edit absensi
     */
    public function edit($id)
    {
        $absensi = Absensi::findOrFail($id);
        $karyawan = User::where('role', 'karyawan')->get();

        return view('absensi.edit', compact('absensi', 'karyawan'));
    }

    /**
     * Mengupdate data absensi
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'Kode_Karyawan' => 'required|exists:users,Kode_Karyawan',
            'absen_masuk' => 'nullable|date',
            'absen_keluar' => 'nullable|date',
            'status_kehadiran' => 'required|in:hadir,izin,sakit,cuti,alpa',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $absensi = Absensi::findOrFail($id);
            $karyawan = User::where('Kode_Karyawan', $request->Kode_Karyawan)->first();

            $data = [
                'karyawan_id' => $karyawan->id,
                'status_kehadiran' => $request->status_kehadiran,
            ];

            if ($request->absen_masuk) {
                $data['absen_masuk'] = $request->absen_masuk;
            }

            if ($request->absen_keluar) {
                $data['absen_keluar'] = $request->absen_keluar;

                if ($absensi->absen_masuk) {
                    $data['jam_kerja'] = Carbon::parse($absensi->absen_masuk)->diffInMinutes(Carbon::parse($request->absen_keluar));
                }
            }

            $absensi->update($data);

            return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan data yang sudah dihapus
     */
    public function trashed()
    {
        $absensis = Absensi::onlyTrashed()
            ->with(['karyawan', 'site'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('absensi.trashed', compact('absensis'));
    }

    /**
     * Mengembalikan data yang sudah dihapus
     */
    public function restore($id)
    {
        $absensi = Absensi::onlyTrashed()->findOrFail($id);
        $absensi->restore();

        return redirect()->route('absensi.trashed')->with('success', 'Data absensi berhasil dikembalikan.');
    }

    /**
     * Menghapus permanen data absensi
     */
    public function forceDelete($id)
    {
        $absensi = Absensi::onlyTrashed()->findOrFail($id);

        // Hapus file selfie jika ada
        if ($absensi->selfie_path) {
            Storage::disk('public')->delete($absensi->selfie_path);
        }
        if ($absensi->selfie_path_pulang) {
            Storage::disk('public')->delete($absensi->selfie_path_pulang);
        }

        $absensi->forceDelete();

        return redirect()->route('absensi.trashed')->with('success', 'Data absensi berhasil dihapus permanen.');
    }
}
