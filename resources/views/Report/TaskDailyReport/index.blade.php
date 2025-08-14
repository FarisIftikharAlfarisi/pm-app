@extends('Partials.main')

@section('content')
<div class="pagetitle">
    <h1>Data Daily Report Task</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Daily Report Task</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Daily Report Task</h5>
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createDailyReportModal">
                        <i class="bi bi-plus-circle"></i> Buat Laporan Baru
                    </button>

                    <!-- Tabel Daily Report Task -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Site</th>
                                <th>Reporter</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>Target Date</th>
                                <th>Finish Date</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->task->nama_task }}</td>
                                <td>{{ $report->site->nama_site }}</td>
                                <td>{{ $report->reporter->name }}</td>
                                <td>{{ $report->Progress }}%</td>
                                <td>{{ $report->Status }}</td>
                                <td>{{ $report->Start_Date }}</td>
                                <td>{{ $report->Target_Date }}</td>
                                <td>{{ $report->Finish_Date }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editDailyReportModal{{ $report->id }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $report->id }})">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editDailyReportModal{{ $report->id }}" tabindex="-1" aria-labelledby="editDailyReportModalLabel{{ $report->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editDailyReportModalLabel{{ $report->id }}">Edit Laporan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('daily-report-tasks.update', $report->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="Kode_Task" class="form-label">Task</label>
                                                    <select name="Kode_Task" id="Kode_Task" class="form-control">
                                                        @foreach($tasks as $task)
                                                            <option value="{{ $task->id }}" {{ $report->Kode_Task == $task->id ? 'selected' : '' }}>{{ $task->nama_task }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="Kode_Site" class="form-label">Kode Site</label>
                                                    <input type="text" name="Kode_Site" id="Kode_Site" class="form-control" value="{{ $report->Kode_Site }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="Reporter" class="form-label">Reporter</label>
                                                    <select name="Reporter" id="Reporter" class="form-control">
                                                        @foreach(User::all() as $user)
                                                            <option value="{{ $user->id }}" {{ $report->Reporter == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="Progress" class="form-label">Progress</label>
                                                    <input type="number" name="Progress" id="Progress" class="form-control" value="{{ $report->Progress }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="Keterangan" class="form-label">Keterangan</label>
                                                    <textarea name="Keterangan" id="Keterangan" class="form-control" required>{{ $report->Keterangan }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="Status" class="form-label">Status</label>
                                                    <input type="text" name="Status" id="Status" class="form-control" value="{{ $report->Status }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="Start_Date" class="form-label">Start Date</label>
                                                    <input type="date" name="Start_Date" id="Start_Date" class="form-control" value="{{ $report->Start_Date }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="Target_Date" class="form-label">Target Date</label>
                                                    <input type="date" name="Target_Date" id="Target_Date" class="form-control" value="{{ $report->Target_Date }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="Finish_Date" class="form-label">Finish Date</label>
                                                    <input type="date" name="Finish_Date" id="Finish_Date" class="form-control" value="{{ $report->Finish_Date }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="foto" class="form-label">Foto</label>
                                                    <input type="file" name="foto" id="foto" class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah Laporan Baru -->
<div class="modal fade" id="createDailyReportModal" tabindex="-1" aria-labelledby="createDailyReportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDailyReportModalLabel">Buat Laporan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('daily-report-tasks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="Kode_Task" class="form-label">Task</label>
                        <select name="Kode_Task" id="Kode_Task" class="form-control">
                            @foreach($tasks as $task)
                                <option value="{{ $task->id }}">{{ $task->nama_task }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="Kode_Site" class="form-label">Kode Site</label>
                        <input type="text" name="Kode_Site" id="Kode_Site" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="Reporter" class="form-label">Reporter</label>
                        <select name="Reporter" id="Reporter" class="form-control">
                            @foreach(User::all() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="Progress" class="form-label">Progress</label>
                        <input type="number" name="Progress" id="Progress" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="Keterangan" class="form-label">Keterangan</label>
                        <textarea name="Keterangan" id="Keterangan" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="Status" class="form-label">Status</label>
                        <input type="text" name="Status" id="Status" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="Start_Date" class="form-label">Start Date</label>
                        <input type="date" name="Start_Date" id="Start_Date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="Target_Date" class="form-label">Target Date</label>
                        <input type="date" name="Target_Date" id="Target_Date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="Finish_Date" class="form-label">Finish Date</label>
                        <input type="date" name="Finish_Date" id="Finish_Date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" name="foto" id="foto" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert untuk Konfirmasi Hapus -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form untuk menghapus data
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>

<!-- Form tersembunyi untuk menghapus data -->
@foreach($reports as $report)
<form id="delete-form-{{ $report->id }}" action="{{ route('daily-report-tasks.destroy', $report->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endforeach
@endsection
