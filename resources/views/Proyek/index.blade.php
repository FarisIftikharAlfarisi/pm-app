@extends('Partials.main')

@section('title', 'Daftar Proyek')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- Breadcrumbs dan Title -->
            <div class="card-header">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page">Proyek</li>
                        <li class="breadcrumb-item active" aria-current="page">Daftar Proyek</li>
                    </ol>
                </nav>
                <h3 class="card-title">Daftar Proyek</h3>
                <div class="card-tools">
                    <a href="{{ route('projects.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Tambah Proyek Baru
                    </a>
                </div>
            </div>

            <!-- Tabel Proyek -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Kode Proyek</th>
                                <th>Nama Proyek</th>
                                <th>Tanggal Mulai</th>
                                <th>Durasi</th>
                                <th>Tenggat Klien</th>
                                <th>Status</th>
                                <th>Setting</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projects as $project)
                            <tr>
                                <td>{{ $project->kode_project }}</td>
                                <td>{{ $project->nama_project }}</td>
                                <td>
                                    @if($project->tanggal_mulai)
                                        {{ \Carbon\Carbon::parse($project->tanggal_mulai)->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($project->tanggal_mulai && $project->tanggal_selesai)
                                        {{ \Carbon\Carbon::parse($project->tanggal_mulai)->diffInDays($project->tanggal_selesai) }} hari
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($project->tanggal_selesai)
                                        {{ \Carbon\Carbon::parse($project->tanggal_selesai)->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                   @if ($project->status == 'planning')
                                       <span class="badge text-bg-primary">Planning</span>
                                   @elseif ($project->status == 'in_progress')
                                       <span class="badge text-bg-info">In Progress</span>
                                   @elseif ($project->status == 'completed')
                                       <span class="badge text-bg-success">Completed</span>
                                   @elseif ($project->status == 'on_hold')
                                       <span class="badge text-bg-warning">On Hold</span>
                                   @elseif ($project->status == 'cancelled')
                                       <span class="badge text-bg-danger">Cancelled</span>
                                   @endif
                                </td>
                                <td>

                                            {{-- @if($project->hasMouDocument()) --}}
                                                <a href="{{ route('task-to-do.index', $project->kode_project) }}" class="btn btn-md btn-primary" title="Tambahkan Penjadwalan Proyek"
                                                > <i class="bi bi-file-earmark-text"></i> WBS </a>
                                            {{-- @endif --}}

                                            {{-- @if($project->hasWbs()) {{ route('finance.dashboard', $project->id) }} --}}
                                                <a href="{{ route('finance.index',[$project->kode_project]) }}"
                                                class="btn btn-md btn-success">
                                                    <i class="bi bi-bank"></i> Finance
                                                </a>
                                            {{-- @endif --}}

                                            {{-- @if($project->hasWbs() && $project->hasTechnicalDrawing()) {{ route('project.dashboard', $project->id) }} --}}
                                                <a href=""
                                                class="btn btn-md btn-primary">
                                                    <i class="bi bi-building-fill-gear"></i> Proyek
                                                </a>
                                            {{-- @endif --}}
                                        </div>
                                    </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="" class="btn btn-sm btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        {{-- <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a> --}}
                                        {{-- <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus proyek ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form> --}}
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data proyek</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                {{-- @if($projects->hasPages())
                <div class="card-footer clearfix">
                    {{ $projects->links() }}
                </div>
                @endif --}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi datatable (opsional)
        $('.table').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            }
        });
    });
</script>
@endsection
