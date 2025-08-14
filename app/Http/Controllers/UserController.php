<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
   {
    public function index()
    {
        // Hanya developer dan project leader yang bisa akses
        if (!Gate::allows('manage-users')) {
            abort(403);
        }

        $currentUser = Auth::user();
        $users = [];

        if ($currentUser->role === 'DEVELOPER') {
            $users = User::where('role', 'PROJECT_LEADER')->get();
        } elseif ($currentUser->role === 'PROJECT_LEADER') {
            $users = User::whereIn('role', ['ACCOUNTING', 'SITE_MANAGER'])->get();
        }

        return view('users.index', compact('users'));
    }

    public function create()
    {
        if (!Gate::allows('manage-users')) {
            abort(403);
        }

        $currentUser = Auth::user();
        $roles = [];

        if ($currentUser->role === 'DEVELOPER') {
            $roles = ['PROJECT_LEADER'];
        } elseif ($currentUser->role === 'PROJECT_LEADER') {
            $roles = ['ACCOUNTING', 'SITE_MANAGER'];
        }

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('manage-users')) {
            abort(403);
        }

        $currentUser =Auth::user();
        $allowedRoles = [];

        if ($currentUser->role === 'DEVELOPER') {
            $allowedRoles = ['PROJECT_LEADER'];
        } elseif ($currentUser->role === 'PROJECT_LEADER') {
            $allowedRoles = ['ACCOUNTING', 'SITE_MANAGER'];
        }

        $request->validate([
            'Kode_Karyawan' => 'required|string|max:255|unique:users',
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|string|in:' . implode(',', $allowedRoles),
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'Kode_Site' => 'nullable|exists:sites,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::create([
            'Kode_Karyawan' => $request->Kode_Karyawan,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Simpan detail user
        $userDetail = new UserDetails([
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
            'jabatan' => $request->jabatan,
            'Kode_Site' => $request->Kode_Site,
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('profile-photos', 'public');
            $userDetail->foto = $path;
        }

        $user->detail()->save($userDetail);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        if (!Gate::allows('manage-users')) {
            abort(403);
        }

        // Pastikan user yang diedit sesuai hierarki
        $currentUser = Auth::user();
        $allowed = false;

        if ($currentUser->role === 'DEVELOPER' && $user->role === 'PROJECT_LEADER') {
            $allowed = true;
        } elseif ($currentUser->role === 'PROJECT_LEADER' && in_array($user->role, ['ACCOUNTING', 'SITE_MANAGER'])) {
            $allowed = true;
        }

        if (!$allowed) {
            abort(403);
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (!Gate::allows('manage-users')) {
            abort(403);
        }

        // Validasi hierarki sama seperti edit
        $currentUser = Auth::user();
        $allowed = false;

        if ($currentUser->role === 'DEVELOPER' && $user->role === 'PROJECT_LEADER') {
            $allowed = true;
        } elseif ($currentUser->role === 'PROJECT_LEADER' && in_array($user->role, ['ACCOUNTING', 'SITE_MANAGER'])) {
            $allowed = true;
        }

        if (!$allowed) {
            abort(403);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'Kode_Site' => 'nullable|exists:sites,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
        ]);

        $userDetail = $user->detail ?? new UserDetails();
        $userDetail->nomor_telepon = $request->nomor_telepon;
        $userDetail->alamat = $request->alamat;
        $userDetail->jabatan = $request->jabatan;
        $userDetail->Kode_Site = $request->Kode_Site;

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($userDetail->foto) {
                Storage::disk('public')->delete($userDetail->foto);
            }
            $path = $request->file('foto')->store('profile-photos', 'public');
            $userDetail->foto = $path;
        }

        $user->detail()->save($userDetail);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }
}
