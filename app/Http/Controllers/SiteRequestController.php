<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Barang;
use App\Models\Projects;
use App\Models\SiteRequest;
use Illuminate\Http\Request;
use App\Models\KebutuhanBarangWbs;
use App\Models\SiteRequestDetails;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseRequisition;
use Illuminate\Support\Facades\Auth;

class SiteRequestController extends Controller
{
    public function index()
    {

        $title = 'Permohonan | Site';
        $siteRequests = SiteRequest::with(['creator', 'details'])
            ->latest()
            ->get();

        // kode request otomatis
        $lastRequest = SiteRequest::orderBy('created_at', 'desc')->first();
        $lastKodeRequest = $lastRequest ? $lastRequest->kode_request : null;
        $newKodeRequest = $lastKodeRequest ? (int) substr($lastKodeRequest, 3) + 1 : 1;
        $kode_request = 'SR' . str_pad($newKodeRequest, 5, '0', STR_PAD_LEFT);

        // nanti ganti ke site request tang tidak terhapus pakai softdelete dan per site
        $sites = Site::all();

        // data barang dengan kategori BAHAN_BAKU, AFTERCRAFT
        $data_barang = Barang::whereIn('kategori', ['BAHAN_BAKU', 'AFTERCRAFT'])->get();

        return view('BARANG.SiteRequest.RFQ.Site.index', compact('title', 'siteRequests','kode_request', 'sites', 'data_barang'));
    }

    public function index_by_wbs($kode_project)
    {

        $project = Projects::where('kode_project', $kode_project)->firstOrFail();
        $projectId = $project->id;


        // cek apakah id proyek ini ada di site request
        $siteRequest = SiteRequest::where('project_id', $projectId)->first();
        if ($siteRequest) {
            return redirect()->back()->with('message', 'Permintaan barang untuk proyek ini sudah ada, silahkan lanjutkan ke proses pemilihan vendor.');
        } else {
        try {
            // Ambil semua kebutuhan barang dari WBS berdasarkan project
            $requirements = KebutuhanBarangWbs::with([
                'taskToDo' => function($query) {
                    $query->select('id', 'nama_task', 'kode_task', 'start_date', 'end_date', 'project_id');
                },
                'barang' => function($query) {
                    $query->select('id', 'kode_barang', 'nama_barang', 'kategori');
                },
                'satuan' => function($query) {
                    $query->select('id', 'unit_name', 'conversion_factor', 'barang_id');
                }
            ])
            ->whereHas('taskToDo', function($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
            ->get()
            ->groupBy(function($item) {
                return $item->barang_id;
            })
            ->map(function($items) {
                $firstItem = $items->first();
                return [
                    'barang_id' => $firstItem->barang_id,
                    'kode_barang' => $firstItem->barang->kode_barang,
                    'nama_barang' => $firstItem->barang->nama_barang,
                    'kategori' => $firstItem->barang->kategori,
                    'total_quantity' => $items->sum('jumlah'),
                    'satuan_id' => $firstItem->satuan->id ?? null,
                    'satuan' => $firstItem->satuan->unit_name ?? '-',
                    'tasks' => $items->map(function($item) {
                        return [
                            'task_id' => $item->task_to_do_id,
                            'nama_task' => $item->taskToDo->nama_task,
                            'kode_task' => $item->taskToDo->kode_task,
                            'quantity' => $item->jumlah,
                            'start_date' => $item->taskToDo->start_date,
                            'end_date' => $item->taskToDo->end_date,
                        ];
                    })->toArray(),
                    'earliest_needed' => $items->min('taskToDo.start_date'),
                    'status' => 'pending', // Default status
                    'is_approved' => false
                ];
            });


            // return response()->json([
            //     'success' => true,
            //     'message' => 'Data kebutuhan barang berhasil diambil',
            //     'data' => [
            //         'project_id' => $projectId,
            //         'total_items' => $requirements->count(),
            //         'requirements' => $requirements->values()
            //     ]
            // ]);

            return response()->view('BARANG.SiteRequest.BarangWBSProyek.index', [
            'title' => 'Kebutuhan Barang WBS Proyek',
            'projectId' => $projectId,
            'requirements' => $requirements->values(),
            'total_items' => $requirements->count()
        ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kebutuhan barang',
                'error' => $e->getMessage()
            ], 500);
        }
        }
    }

    /**
     * Approve/Reject setiap item yang dibutuhkan
     * Point 2: acc setiap item yang dibutuhkan (bisa di acc bisa nggak)
     */
    public function approveItems(Request $request)
    {

        // dd($request->all());

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'approvals' => 'required|array',
            'approvals.*.barang_id' => 'required|exists:barangs,id',
            'approvals.*.satuan' => 'required|exists:item_units,id',
            'approvals.*.is_approved' => 'required|boolean',
            'approvals.*.quantity' => 'required|numeric|min:0',
            'approvals.*.notes' => 'nullable|string|max:500'
        ]);

        //ambil nama proyek
        $project = Projects::where('id', $request->project_id)->first();

        $nama_request = 'Permintaan Barang WBS Proyek ' . $project->nama_project;

        DB::beginTransaction();

        try {
            $projectId = $request->project_id;
            $approvals = $request->approvals;

            // Cek apakah sudah ada Site Request untuk project ini
            $siteRequest = SiteRequest::where('project_id', $projectId)
                ->where('approval_accounting_status', null)
                ->first();



            if (!$siteRequest) {
                // Buat Site Request baru
                $siteRequest = SiteRequest::create([
                    'kode_request' => $this->generateSiteRequestNumber(),
                    'nama_request' => $nama_request,
                    'jenis_request' => 'Barang WBS',
                    'site_id' => $project->site_id,
                    'approval_accounting_status' => 'APPROVED',
                    'project_id' => $projectId,
                    'tanggal_request' => now(),
                    'creator_id' => Auth::id(),
                    'total_items' => 0,
                    'approved_items' => 0
                ]);
            }


            $approvedCount = 0;
            $totalItems = count($approvals);

            foreach ($approvals as $approval) {
                // Update atau create site request item
                $siteRequestItem = SiteRequestDetails::updateOrCreate(
                    [
                        'site_request_id' => $siteRequest->id,
                        'barang_id' => $approval['barang_id']
                    ],
                    [
                        'jumlah' => $approval['quantity'],
                        'satuan_id' => $approval['satuan'],
                        'approval_accounting_status' => $approval['is_approved'] ? 'approved' : 'rejected',
                        'accounting_comment' => $approval['notes'] ?? null,
                        'accounting_id' => Auth::id(),
                        'accounting_approval_date' => $approval['is_approved'] ? now() : null,
                        'keterangan' => $approval['notes'] ?? null,
                        'approved_by' => Auth::id(),
                        'approved_at' => $approval['is_approved'] ? now() : null
                    ]
                );

                if ($approval['is_approved']) {
                    $approvedCount++;
                }
            }

            // Update site request summary
            $siteRequest->update([
                'total_items' => $totalItems,
                'approved_items' => $approvedCount,
                'status' => $approvedCount > 0 ? 'approved' : 'rejected'
            ]);

            DB::commit();

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Approval items berhasil disimpan',
            //     'data' => [
            //         'site_request_id' => $siteRequest->id,
            //         'nomor_sr' => $siteRequest->nomor_sr,
            //         'total_items' => $totalItems,
            //         'approved_items' => $approvedCount,
            //         'rejected_items' => $totalItems - $approvedCount
            //     ]
            // ]);
            $kode_project = Projects::where('id', $projectId)->first()->kode_project;

            return redirect()->route('finance.index', [$kode_project])
                ->with('success', 'Approval items berhasil disimpan. Silakan lanjutkan ke tahap berikutnya.');

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan approval items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get approved items untuk lanjut ke PR
     */
    public function getApprovedItems($siteRequestId)
    {
        try {
            $siteRequest = SiteRequest::with([
                'items' => function($query) {
                    $query->where('is_approved', true)
                          ->with(['barang:id,kode_barang,nama_barang,kategori']);
                },
                'project:id,nama_project'
            ])->findOrFail($siteRequestId);

            return response()->json([
                'success' => true,
                'message' => 'Data approved items berhasil diambil',
                'data' => [
                    'site_request' => [
                        'id' => $siteRequest->id,
                        'nomor_sr' => $siteRequest->nomor_sr,
                        'project_name' => $siteRequest->project->nama_project,
                        'total_approved_items' => $siteRequest->items->count()
                    ],
                    'approved_items' => $siteRequest->items->map(function($item) {
                        return [
                            'barang_id' => $item->barang_id,
                            'kode_barang' => $item->barang->kode_barang,
                            'nama_barang' => $item->barang->nama_barang,
                            'kategori' => $item->barang->kategori,
                            'quantity_requested' => $item->quantity_requested,
                            'approval_notes' => $item->approval_notes
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data approved items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate nomor Site Request
     */
    private function generateSiteRequestNumber()
    {
        $year = date('Y');
        $month = date('m');

        $lastSR = SiteRequest::whereYear('created_at', $year)
                            ->whereMonth('created_at', $month)
                            ->orderBy('id', 'desc')
                            ->first();

        $sequence = $lastSR ? (int)substr($lastSR->nomor_sr, -4) + 1 : 1;

        return 'SR/' . $year . '/' . $month . '/' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_request' => 'required|unique:site_requests,kode_request',
            'nama_request' => 'required|string',
            'jenis_request' => 'required|string',
            'site_id' => 'required|exists:sites,id',
        ]);

        $request->merge(['creator_id' => Auth::user()->id]);

        $siteRequest = SiteRequest::create([
            'kode_request' => $request->kode_request,
            'nama_request' => $request->nama_request,
            'jenis_request' => $request->jenis_request,
            'site_id' => $request->site_id,
            'approval_project_leader_status' => 'PENDING',
            'approval_accounting_status' => 'PENDING',
            'creator_id' => Auth::user()->id,
        ]);

        return redirect()->route('site-request.list_barang', $siteRequest->id)
            ->with('success', 'Permintaan berhasil dibuat. Silakan tambahkan detail barang.');
    }

    public function listBarang($id)
    {
        $siteRequest = SiteRequest::findOrFail($id);
        $barangs = Barang::all();
        return view('BARANG.SiteRequest.RFQ.Site.details', compact('siteRequest', 'barangs'));
    }

    public function storeDetail(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);


        SiteRequestDetails::create([
            'site_request_id' => $id,
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'approval_project_leader_status' => 'PENDING',
            'approval_accounting_status' => 'PENDING',
        ]);

        return redirect()->back()->with('success', 'Detail barang berhasil ditambahkan ke permintaan.');
    }

    public function destroyDetail($id)
    {
        $detail = SiteRequestDetails::findOrFail($id);
        $detail->delete();

        return back()->with('success', 'Barang berhasil dihapus dari permintaan.');
    }

    public function review()
    {
        $title = 'Review Permohonan | Site';
        $user = Auth::user();

        // Pending Requests
        $pendingQuery = SiteRequest::with(['creator', 'site', 'details'])
            ->where(function($query) use ($user) {
                if ($user->role == "PROJECT_LEADER") {
                    $query->where('approval_project_leader_status', 'PENDING');
                } elseif ($user->role == "ACCOUNTING") {
                    $query->where('approval_accounting_status', 'PENDING');
                } else {
                    $query->where('approval_project_leader_status', 'PENDING')
                        ->where('approval_accounting_status', 'PENDING');
                }
            });

        // Half Approved Requests
        $halfApproveQuery = SiteRequest::with(['creator', 'site', 'details'])
            ->where(function($query) use ($user) {
                if ($user->role == "PROJECT_LEADER") {
                    $query->where('approval_accounting_status', 'PENDING')
                        ->where('approval_project_leader_status', 'APPROVED');
                } elseif ($user->role == "ACCOUNTING") {
                    $query->where('approval_project_leader_status', 'PENDING')
                        ->where('approval_accounting_status', 'APPROVED');
                } else {
                    $query->where(function($q) {
                        $q->where('approval_project_leader_status', 'APPROVED')
                            ->where('approval_accounting_status', 'PENDING');
                    })->orWhere(function($q) {
                        $q->where('approval_project_leader_status', 'PENDING')
                            ->where('approval_accounting_status', 'APPROVED');
                    });
                }
            });

        // Fully Approved Requests
        $fullApproveQuery = SiteRequest::with(['creator', 'site', 'details'])
            ->where('approval_project_leader_status', 'APPROVED')
            ->where('approval_accounting_status', 'APPROVED');

        $pendingRequests = $pendingQuery->get();
        $halfApproveRequests = $halfApproveQuery->get();
        $fullApproveRequests = $fullApproveQuery->get();

        return view('BARANG.SiteRequest.RFQ.Approval.approval_site_request',
            compact(
                'title',
                'pendingRequests',
                'halfApproveRequests',
                'fullApproveRequests'
            )
        );
    }

    public function detailReview($id)
    {
        $title = 'Detail Review Permohonan | Site';
        $siteRequest = SiteRequest::with(['creator', 'details.barang', 'site'])
            ->findOrFail($id);

        return view('BARANG.SiteRequest.RFQ.Approval.list_approval', compact('title', 'siteRequest'));
    }

    public function saveReviews(Request $request, $id){
    $request->validate([
        'site_request_id' => 'required|exists:site_requests,id',
        'site_request_code' => 'required|string',
        'approved_items' => 'array',
        'comment' => 'nullable|string|max:255',
    ]);

    $siteRequest = SiteRequest::findOrFail($id);
    $user = Auth::user();

    // Validate user role
    if (!$user->role == "PROJECT_LEADER" && !$user->role == "ACCOUNTING") {
        return back()->with('error', 'anda tidak memiliki hak akses untuk melakukan persetujuan.');
    }

    // Process approval based on role
    if ($user->role == "PROJECT_LEADER") {
        $this->processProjectLeaderApproval($request, $siteRequest, $user);
    } elseif ($user->role == "ACCOUNTING") {
        $this->processAccountingApproval($request, $siteRequest, $user);
    }

    // Check if all items are approved by both roles
    $this->checkFullApproval($siteRequest);
        return back()->with('success', 'Approval berhasil disimpan.');
    }


    /**
     * Fungsi untuk proses approval dari Project Leader
     *
     * proses project leader mengapprove setiap barang dari site request
     * **/
    private function processProjectLeaderApproval($request, $siteRequest, $user)
    {
        // Update approved items
        if ($request->has('approved_items')) {
            // Update items that are approved
            SiteRequestDetails::where('site_request_id', $siteRequest->id)
                ->whereIn('id', $request->approved_items)
                ->update([
                    'approval_project_leader_status' => 'APPROVED',
                    'project_leader_id' => $user->id,
                    'project_leader_approval_date' => now(),
                    'project_leader_comment' => $request->comment ?? null,
                ]);

            // Update items that are NOT approved (rejected)
            SiteRequestDetails::where('site_request_id', $siteRequest->id)
                ->whereNotIn('id', $request->approved_items)
                ->update([
                    'approval_project_leader_status' => 'REJECTED',
                    'project_leader_id' => $user->id,
                    'project_leader_approval_date' => now(),
                    'project_leader_comment' => $request->comment ?? 'Item tidak disetujui',
                ]);
        } else {
            // Jika tidak ada approved_items, berarti semua ditolak
            SiteRequestDetails::where('site_request_id', $siteRequest->id)
                ->update([
                    'approval_project_leader_status' => 'REJECTED',
                    'project_leader_id' => $user->id,
                    'project_leader_approval_date' => now(),
                    'project_leader_comment' => $request->comment ?? 'Semua item tidak disetujui',
                ]);
        }

        // Update main request status
        $allItemsApproved = $siteRequest->details->every(function ($detail) {
            return $detail->approval_project_leader_status === 'APPROVED';
        });

        $someItemsRejected = $siteRequest->details->contains(function ($detail) {
            return $detail->approval_project_leader_status === 'REJECTED';
        });

        $status = 'APPROVED';
        if ($allItemsApproved) {
            $status = 'APPROVED';
        } elseif ($someItemsRejected && !$siteRequest->details->contains(function ($detail) {
            return $detail->approval_project_leader_status === 'APPROVED';
        })) {
            $status = 'REJECTED';
        }

        $siteRequest->update([
            'approval_project_leader_status' => $status,
            'approval_project_leader_id' => $user->id,
            'project_leader_approval_date' => now(),
            'project_leader_comment' => $request->comment ?? null,
        ]);
    }

     /**
     * Fungsi untuk proses approval dari Accounting
     *
     * proses accounting mengapprove setiap barang dari site request
     * **/

    private function processAccountingApproval($request, $siteRequest, $user)
    {
        // Update approved items
        if ($request->has('approved_items')) {
            // Update items that are approved
            SiteRequestDetails::where('site_request_id', $siteRequest->id)
                ->whereIn('id', $request->approved_items)
                ->update([
                    'approval_accounting_status' => 'APPROVED',
                    'accounting_id' => $user->id,
                    'accounting_approval_date' => now(),
                    'accounting_comment' => $request->comment ?? null,
                ]);

            // Update items that are NOT approved (rejected)
            SiteRequestDetails::where('site_request_id', $siteRequest->id)
                ->whereNotIn('id', $request->approved_items)
                ->update([
                    'approval_accounting_status' => 'REJECTED',
                    'accounting_id' => $user->id,
                    'accounting_approval_date' => now(),
                    'accounting_comment' => $request->comment ?? 'Item tidak disetujui',
                ]);
        } else {
            // Jika tidak ada approved_items, berarti semua ditolak
            SiteRequestDetails::where('site_request_id', $siteRequest->id)
                ->update([
                    'approval_accounting_status' => 'REJECTED',
                    'accounting_id' => $user->id,
                    'accounting_approval_date' => now(),
                    'accounting_comment' => $request->comment ?? 'Semua item tidak disetujui',
                ]);
        }

        // Update main request status
        $allItemsApproved = $siteRequest->details->every(function ($detail) {
            return $detail->approval_accounting_status === 'APPROVED';
        });

        $someItemsRejected = $siteRequest->details->contains(function ($detail) {
            return $detail->approval_accounting_status === 'REJECTED';
        });

        $status = 'APPROVED';
        if ($allItemsApproved) {
            $status = 'APPROVED';
        } elseif ($someItemsRejected && !$siteRequest->details->contains(function ($detail) {
            return $detail->approval_accounting_status === 'APPROVED';
        })) {
            $status = 'REJECTED';
        }

        $siteRequest->update([
            'approval_accounting_status' => $status,
            'approval_accounting_id' => $user->id,
            'accounting_approval_date' => now(),
            'accounting_comment' => $request->comment ?? null,
        ]);
    }

    private function checkFullApproval($siteRequest)
    {
        // cek apakah SiteRequest sudah di approve semua
        $allFullyApproved = $siteRequest->approval_project_leader_status === 'APPROVED' &&
            $siteRequest->approval_accounting_status === 'APPROVED';

        // jika ya maka update
        // status SiteRequest menjadi APPROVED
        if ($allFullyApproved) {
            $siteRequest->update(['approval_status' => 'APPROVED']);
            $this->createPurchaseRequisition($siteRequest);
        }
    }

    private function createPurchaseRequisition($siteRequest)
    {
    // Generate PR code
    $siteCode = $siteRequest->site->kode_site;

    $lastPR = PurchaseRequisition::where('site_id', $siteRequest->site_id)
        ->orderBy('created_at', 'desc')
        ->first();

    // count jumlah PR yang dikeluarkan berdasarkan site_id
    $lastNumber = $lastPR ? (int) substr($lastPR->kode_requisition, 3, 3) : 0;
    $newNumber = $lastNumber + 1;

    // Format nomor dengan leading zeros
    $formattedNumber = str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    // Ambil tanggal hari ini dalam format ddmmyy
    $date = now()->format('dmy');

    // Gabungkan semuanya
    $kode_pr = "PR/{$formattedNumber}/{$siteCode}/{$date}";

    dd($siteRequest, $kode_pr);

    // Create PR header
    $pr = PurchaseRequisition::create([
        'kode_requisition' => $kode_pr,
        'request_id' => $siteRequest->id,
        'site_id' => $siteRequest->site_id,
    ]);

    // Create PR details
    foreach ($siteRequest->details as $detail) {
        $pr->details()->create([
            'barang_id' => $detail->barang_id,
            'jumlah' => $detail->jumlah,
            'keterangan' => $detail->keterangan,
        ]);
    }

    return $pr;
    }
}
