<?php

/**
 * ==========================================================
 * BACA DULU PLISSZZZZ
 *
 *
 * INI BARU BUAT NAMBAH WBS
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Site;
use App\Models\User;
use App\Models\Barang;
use App\Models\Projects;
use App\Models\TaskToDo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\KebutuhanBarangWbs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Console\View\Components\Task;

class TaskToDoController extends Controller
{
    /**
     * Menampilkan daftar task untuk proyek tertentu
     */

        public function index($kode_project){
        $project = Projects::where('kode_project', $kode_project)->firstOrFail();

        // Get the site for this project
        $sites = Site::find($project->site_id)->firstOrFail();

        $taskCount = TaskToDo::where('project_id', $project->id)->count();
        // Generate complete task codes for each site
        $siteTaskCodes = [];

        // ambil task setiap site
        $allTasks = TaskToDo::where('project_id', $project->id)
        ->orderBy('kode_task')
        ->get();

        $groupedTasks = [];

        foreach ($allTasks as $task) {
            $kodeTask = $task->kode_task;

            if (strpos($kodeTask, '.') === false) {
                // Task tanpa titik → Parent
                $groupedTasks[$kodeTask]['parent'] = $task;
                $groupedTasks[$kodeTask]['children'] = [];
            } else {
                // Task dengan titik → Child
                $parentKode = explode('.', $kodeTask)[0];
                $groupedTasks[$parentKode]['children'][] = $task;
            }
        }

        foreach ($sites as $site) {
            $taskNumber = ($taskCount + 1).str_pad(4, '0');
            $siteTaskCodes[$sites->id] = "TSK-{$project->kode_project}-{$sites->Kode_Site}-{$taskNumber}";
        }

        // site task code yang paling akhir
        $lastSiteTaskCode = end($siteTaskCodes);

        $barangs = Barang::where('kategori',['BAHAN_BAKU','AFTERCRAFT'])
            ->get();

        return view('Task.SiteTask.index', compact('groupedTasks', 'project', 'sites', 'lastSiteTaskCode', 'barangs'));
    }

    /**
     * Menyimpan task baru
     */

    public function create($kode_project)
    {
        $project = Projects::where('kode_project', $kode_project)->firstOrFail();

        // Get the site for this project
        $sites = Site::find($project->site_id)->firstOrFail();

        $taskCount = TaskToDo::where('project_id', $project->id)->count();
        // Generate complete task codes for each site
        $siteTaskCodes = [];

        // ambil task setiap site
        $existingTasks = TaskToDo::where('project_id', $project->id)
            ->get();

        foreach ($sites as $site) {
            $taskNumber = ($taskCount + 1).str_pad(4, '0');
            $siteTaskCodes[$sites->id] = "TSK-{$project->kode_project}-{$sites->Kode_Site}-{$taskNumber}";
        }

        // site task code yang paling akhir
        $lastSiteTaskCode = end($siteTaskCodes);

        $barangs = Barang::where('kategori',['BAHAN_BAKU','AFTERCRAFT'])
            ->get();

       return view('Task.SiteTask.create', compact('project', 'sites', 'lastSiteTaskCode', 'existingTasks', 'barangs'));
    }


    public function store(Request $request)
    {

        // ambil data project
        $project = Projects::findOrFail($request->kode_proyek);

        // ambil data validator apakah dia login
        if (Auth::user()->role == 'ACCOUNTING') {
            $request->merge(['accounting_id' => Auth::user()->id]);
            $request->merge(['project_leader_id' => null]);
        } elseif (Auth::user()->role == 'PROJECT_LEADER') {
            $request->merge(['accounting_id' => null]);
            $request->merge(['project_leader_id' => Auth::user()->id]);
        }

    // dd($request->all());

    DB::beginTransaction();

    try {
        // 1. Simpan Task
        $task = TaskToDo::create([
            'site_id' => $project->site_id,
            'project_id' => $project->id,
            'kode_task' => $request->kode_task,
            'nama_task' => $request->nama_task,
            'deskripsi' => $request->deskripsi,
            'type' => 'general', // default
            'parent_id' => $request->parent_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'estimated_hours' => $request->estimated_hours,
            'accounting_approve' => 'pending',
            'accounting_id' => Auth::user()->role == 'ACCOUNTING' ? Auth::user()->id : null,
            'project_leader_approve' => 'pending',
            'project_leader_id' => Auth::user()->role == 'PROJECT_LEADER' ? Auth::user()->id : null,
        ]);

        // 2. Tambahkan kebutuhan barang jika ada
        $barang_ids = $request->barang_id ?? [];
        $uom_ids = $request->barang_uom_id ?? [];
        $jumlahs = $request->jumlah ?? [];

        foreach ($barang_ids as $index => $barang_id) {
            if ($barang_id && $uom_ids[$index] && $jumlahs[$index]) {
                KebutuhanBarangWbs::create([
                    'task_to_do_id' => $task->id,
                    'barang_id' => $barang_id,
                    'satuan_id' => $uom_ids[$index],
                    'jumlah' => $jumlahs[$index],
                ]);
            }
        }

        DB::commit();
        return redirect()->back()->with('success', 'Task dan kebutuhan barang berhasil ditambahkan.');

    } catch (\Exception $e) {
        DB::rollback();
        // return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        return response()->json([
            'failure' => true,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
    }

    /**
     * Menampilkan detail task
     */
    public function edit($kode_proyek, $kode_task)
{
    $project = Projects::where('kode_project', $kode_proyek)->firstOrFail();

    $task = TaskToDo::where('project_id', $project->id)
                    ->where('kode_task', $kode_task)
                    ->with(['parentTask', 'kebutuhanBarangWbs'])
                    ->firstOrFail();

    $existingTasks = TaskToDo::where('project_id', $project->id)
                            ->where('id', '!=', $task->id)
                            ->get();

    $barangs = Barang::with('satuan')->get();

    // Ambil kebutuhan barang yang sudah ada untuk task ini
    $existingKebutuhan = KebutuhanBarangWbs::where('task_to_do_id', $task->id)
        ->with('barang', 'satuan')
        ->get();

    return view('Task.SiteTask.edit_wbs', compact('task', 'existingTasks', 'barangs', 'existingKebutuhan'));
}



    /**
     * Update task
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_task' => 'sometimes|string|max:255',
            'deskripsi' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'sometimes|in:pending,in_progress,completed,cancelled',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'estimated_hours' => 'sometimes|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
            'priority' => 'sometimes|in:low,medium,high'
        ]);

        $task = TaskToDo::findOrFail($id);

        DB::beginTransaction();
        try {
            $task->update($validated);

            /*untuk integrasi dengan tabel perhitungan PERT atau CPM*/
            // if ($request->has(['start_date', 'end_date'])) {
            //     CriticalPath::updateOrCreate(
            //         ['task_id' => $task->id],
            //         ['duration' => $this->calculateDuration($task)]
            //     );
            // }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Task berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus task
     */
    public function destroy($id)
    {
        $task = TaskToDo::findOrFail($id);

        DB::beginTransaction();
        try {
            // Hapus children tasks (cascade)
            $task->children()->delete();
            $task->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Task berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate kode task otomatis
     */
    private function generateTaskCode($project)
    {
        // Format: TSK-SITECODE-PRJCODE-TaskNumberCount
        $siteCode = strtoupper(substr($project->site->kode_site ?? 'ST', 0, 3));
        $projectCode = strtoupper(substr($project->kode_project, 0, 3));
        $taskCount = TaskToDo::where('site_id', $project->site->id)->count() + 1;

        return 'TSK-' . $siteCode . '-' . $projectCode . '-' . str_pad($taskCount, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Hitung durasi untuk CPM/PERT
     */
    private function calculateDuration($task)
    {
        // Contoh perhitungan durasi sederhana
        $start = new \DateTime($task->start_date);
        $end = new \DateTime($task->end_date);
        return $start->diff($end)->days + 1; // Durasi dalam hari
    }
}
