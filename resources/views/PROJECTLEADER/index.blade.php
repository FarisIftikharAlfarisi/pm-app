@extends('Partials.main')

@section('content')
<div class="pagetitle">
    <h1>Data Site Task Job</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Site Task Job</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Site Task Job</h5>
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                        <i class="bi bi-plus-circle"></i> Buat Task Baru
                    </button>

                    <!-- Tabel Site Task Job -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Kode Task</th>
                                <th>Site</th>
                                <th>PIC</th>
                                <th>Fase</th>
                                <th>Task</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>Target Date</th>
                                <th>Finish Date</th>
                                <th>Anggaran</th>
                                <th>Progress</th>
                                <th>Keterangan Progress</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                            <tr>
                                <td>{{ $task->Kode_Task }}</td>
                                <td>{{ $task->site->nama_site }}</td>
                                <td>{{ $task->pic->name }}</td>
                                <td>{{ $task->Fase }}</td>
                                <td>{{ $task->Task }}</td>
                                <td>{{ $task->Status }}</td>
                                <td>{{ $task->Start_Date }}</td>
                                <td>{{ $task->Target_Date }}</td>
                                <td>{{ $task->Finish_Date }}</td>
                                <td>{{ $task->Anggaran }}</td>
                                <td>{{ $task->Progress }}</td>
                                <td>{{ $task->Keterangan_Progress }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editTaskModal{{ $task->id }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $task->id }})">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editTaskModal{{ $task->id }}" tabindex="-1" aria-labelledby="editTaskModalLabel{{ $task->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editTaskModalLabel{{ $task->id }}">Edit Task</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('site-task-jobs.update', $task->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="Kode_Task" class="form-label">Kode Task</label>
                                                            <input type="text" name="Kode_Task" id="Kode_Task" class="form-control" value="{{ $task->Kode_Task }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="Kode_Site" class="form-label">Site</label>
                                                            <select name="Kode_Site" id="Kode_Site" class="form-control">
                                                                @foreach($sites as $site)
                                                                    <option value="{{ $site->id }}" {{ $task->Kode_Site == $site->id ? 'selected' : '' }}>{{ $site->nama_site }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="PIC" class="form-label">PIC</label>
                                                            <select name="PIC" id="PIC" class="form-control">
                                                                @foreach($users as $user)
                                                                    <option value="{{ $user->id }}" {{ $task->PIC == $user->id ? 'selected' : '' }}>{{ $user->Kode_Karyawan | $user->nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="Fase" class="form-label">Fase</label>
                                                            <input type="text" name="Fase" id="Fase" class="form-control" value="{{ $task->Fase }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="Task" class="form-label">Task</label>
                                                            <input type="text" name="Task" id="Task" class="form-control" value="{{ $task->Task }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="Status" class="form-label">Status</label>
                                                            <input type="text" name="Status" id="Status" class="form-control" value="{{ $task->Status }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="Start_Date" class="form-label">Start Date</label>
                                                            <input type="datetime-local" name="Start_Date" id="Start_Date" class="form-control" value="{{ $task->Start_Date }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="Target_Date" class="form-label">Target Date</label>
                                                            <input type="datetime-local" name="Target_Date" id="Target_Date" class="form-control" value="{{ $task->Target_Date }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="Finish_Date" class="form-label">Finish Date</label>
                                                            <input type="datetime-local" name="Finish_Date" id="Finish_Date" class="form-control" value="{{ $task->Finish_Date }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="Anggaran" class="form-label">Anggaran</label>
                                                            <input type="number" name="Anggaran" id="Anggaran" class="form-control" value="{{ $task->Anggaran }}">
                                                        </div>
                                                    </div>
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

<!-- Modal Tambah Task Baru -->
<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTaskModalLabel">Buat Task Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('site-task-jobs.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="Kode_Task" class="form-label">Kode Task</label>
                                <input type="text" name="Kode_Task" id="Kode_Task" class="form-control" value="{{ $kode_task }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="Kode_Site" class="form-label">Site</label>
                                <select name="Kode_Site" id="Kode_Site" class="form-control">
                                    @foreach($sites as $site)
                                        <option value="{{ $site->id }}">{{ $site->site }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="PIC" class="form-label">PIC</label>
                                <select name="PIC" id="PIC" class="form-control">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="Fase" class="form-label">Fase</label>
                                <input type="text" name="Fase" id="Fase" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="Task" class="form-label">Task</label>
                                <input type="text" name="Task" id="Task" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="Status" class="form-label">Status</label>
                                <input type="text" name="Status" id="Status" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="Start_Date" class="form-label">Start Date</label>
                                <input type="datetime" name="Start_Date" id="Start_Date" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="Target_Date" class="form-label">Target Date</label>
                                <input type="datetime" name="Target_Date" id="Target_Date" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="Finish_Date" class="form-label">Finish Date</label>
                                <input type="datetime" name="Finish_Date" id="Finish_Date" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="Anggaran" class="form-label">Anggaran</label>
                                <input type="number" name="Anggaran" id="Anggaran" class="form-control">
                            </div>
                        </div>
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
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>

<!-- Form tersembunyi untuk menghapus data -->
@foreach($tasks as $task)
<form id="delete-form-{{ $task->id }}" action="{{ route('site-task-jobs.destroy', $task->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endforeach
@endsection
