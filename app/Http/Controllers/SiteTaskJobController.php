<?php
/**
 * ==========================================================
 * BACA DULU PLISSZZZZ
 *
 *
 * INI MAH BUAT LAPORAN HARIAN PEKERJAAN DARI SITE BUKAN BUAT NAMBAH WBS
 */


namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\User;
use App\Models\Projects;
use App\Models\SiteTaskJob;
use Illuminate\Http\Request;

class SiteTaskJobController extends Controller
{
    // Menampilkan semua task
    public function index($kode_project)
{
    $project = Projects::where('kode_project', $kode_project)->firstOrFail();
    $sites = Site::where('project_id', $project->id)->get();
    $taskCount = SiteTaskJob::where('project_id', $project->id)->count();

    // Generate complete task codes for each site
    $siteTaskCodes = [];
    foreach ($sites as $site) {
        $taskNumber = ($taskCount + 1).str_pad(4, '0');
        $siteTaskCodes[$site->id] = "TSK-{$site->kode_site}-{$project->kode_project}-{$taskNumber}";
    }

    $data = [
        'project' => $project,
        'sites' => $sites,
        'siteTaskCodes' => $siteTaskCodes,
    ];

    return view('PROJECTLEADER.index', compact('data'));
}

    // Menampilkan form untuk membuat task baru
    public function create()
    {
        $sites = Site::all();
        $users = User::all();
        return view('site_task_jobs.create', compact('sites', 'users'));
    }

    // Menyimpan task baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'Kode_Task' => 'required|string|unique:site_task_jobs,Kode_Task',
            'Kode_Site' => 'required|exists:sites,id',
            'PIC' => 'required|exists:users,id',
            'Fase' => 'required|string',
            'Task' => 'required|string',
            'Status' => 'required|string',
            'Keterangan' => 'nullable|string',
            'Start_Date' => 'nullable|date',
            'Target_Date' => 'nullable|date',
            'Finish_Date' => 'nullable|date',
            'Anggaran' => 'nullable|numeric'
        ]);

        SiteTaskJob::create($request->all());

        return redirect()->route('site-task-jobs.index')->with('success', 'Task berhasil dibuat.');
    }

    // Menampilkan detail task
    public function show(SiteTaskJob $siteTaskJob)
    {
        return view('site_task_jobs.show', compact('siteTaskJob'));
    }

    // Menampilkan form untuk mengedit task
    public function edit(SiteTaskJob $siteTaskJob)
    {
        $sites = Site::all();
        $users = User::all();
        return view('site_task_jobs.edit', compact('siteTaskJob', 'sites', 'users'));
    }

    // Memperbarui task di database
    public function update(Request $request, SiteTaskJob $siteTaskJob)
    {
        $request->validate([
            'Kode_Task' => 'required|string|unique:site_task_jobs,Kode_Task,' . $siteTaskJob->id,
            'Kode_Site' => 'required|exists:sites,id',
            'PIC' => 'required|exists:users,id',
            'Fase' => 'required|string',
            'Task' => 'required|string',
            'Keterangan' => 'nullable|string',
            'Status' => 'required|string',
            'Start_Date' => 'nullable|date',
            'Target_Date' => 'nullable|date',
            'Finish_Date' => 'nullable|date',
            'Anggaran' => 'nullable|numeric'
        ]);

        $siteTaskJob->update($request->all());

        return redirect()->route('site-task-jobs.index')->with('success', 'Task berhasil diperbarui.');
    }

    // Menghapus task dari database
    public function destroy(SiteTaskJob $siteTaskJob)
    {
        $siteTaskJob->delete();
        return redirect()->route('site-task-jobs.index')->with('success', 'Task berhasil dihapus.');
    }
}
