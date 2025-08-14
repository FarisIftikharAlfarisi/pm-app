@extends('Partials.main')

@section('content')
<div class="pagetitle">
    <h1>Manajemen Pengguna Sistem</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Pengguna Sistem</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Daftar Pengguna Sistem</h5>
                    </div>
                    <a href="{{ route('pengguna.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Pengguna
                    </a>

                    <table class="table datatables">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Site</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->Kode_Karyawan }}</td>
                                <td>
                                    @if($user->detail && $user->detail->foto)
                                        <img src="{{ asset('storage/users/'.$user->detail->foto) }}" width="40" class="rounded-circle">
                                    @else
                                        <div class="avatar-placeholder rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            {{ substr($user->nama, 0, 1) }}
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $user->nama }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($user->role == 'ADMIN_SITE')
                                        <span class="badge bg-secondary">Admin Site</span>
                                    @elseif ($user->role == 'DEVELOPER')
                                        <span class="badge bg-primary">Developer IT</span>
                                    @elseif ($user->role == 'PROJECT_LEADER')
                                        <span class="badge bg-info">Project Leader</span>
                                    @elseif ($user->role == 'ACCOUNTING')
                                        <span class="badge bg-success">Accounting</span>
                                    @elseif ($user->role == 'KARYAWAN_SITE')
                                        <span class="badge bg-secondary">Karyawan Site</span>

                                    @endif
                                </td>
                                <td>{{ $user->detail->site->nama_site ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $user->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <a href="{{ route('pengguna.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $user->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Detail -->
                            <div class="modal fade" id="detailModal{{ $user->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $user->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detailModalLabel{{ $user->id }}">Detail Pengguna</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-4 text-center">
                                                    @if($user->detail && $user->detail->foto)
                                                        <img src="{{ asset('storage/users/'.$user->detail->foto) }}" class="img-fluid rounded-circle mb-3" width="150">
                                                    @else
                                                        <div class="avatar-placeholder rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px; font-size: 50px;">
                                                            {{ substr($user->nama, 0, 1) }}
                                                        </div>
                                                    @endif
                                                    <h4>{{ $user->nama }}</h4>
                                                    <span class="badge bg-{{ $user->role == 'admin' ? 'success' : 'primary' }}">
                                                        {{ ucfirst($user->role) }}
                                                    </span>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <strong>Kode Karyawan:</strong>
                                                            <p>{{ $user->Kode_Karyawan }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Email:</strong>
                                                            <p>{{ $user->email }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <strong>Nomor Telepon:</strong>
                                                            <p>{{ $user->detail->nomor_telepon ?? '-' }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Jabatan:</strong>
                                                            <p>{{ $user->detail->jabatan ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <strong>Site:</strong>
                                                            <p>{{ $user->detail->site->nama_site ?? '-' }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Alamat:</strong>
                                                            <p>{{ $user->detail->alamat ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
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
                window.location.href = `/users/${id}`;
            }
        });
    }
</script>
@endsection
