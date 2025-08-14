@extends('Partials.main')

@section('content')
<div class="pagetitle">
    <h1>Tambah Pengguna Sistem</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('pengguna.index') }}">Pengguna Sistem</a></li>
            <li class="breadcrumb-item active">Tambah Pengguna</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form Tambah Pengguna</h5>

                    <form action="{{ route('pengguna.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6 class="fw-bold">Informasi Akun</h6>
                                <hr>
                                <div class="mb-3">
                                    <label for="Kode_Karyawan" class="form-label">Kode Karyawan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="Kode_Karyawan" name="Kode_Karyawan" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="KARYAWAN_SITE"> Karyawan Site </option>
                                        <option value="ADMIN_SITE">Admin Site</option>
                                        <option value="ACCOUNTING">Accounting</option>
                                        <option value="PROJECT_LEADER">Project Leader</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Informasi Tambahan</h6>
                                <hr>
                                <div class="mb-3">
                                    <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon">
                                </div>
                                <div class="mb-3">
                                    <label for="jabatan" class="form-label">Jabatan</label>
                                    <input type="text" class="form-control" id="jabatan" name="jabatan">
                                </div>
                                <div class="mb-3">
                                    <label for="Kode_Site" class="form-label">Site <span class="text-danger">*</span></label>
                                    <select class="form-select" id="Kode_Site" name="Kode_Site" required>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}">{{ $site->Kode_Site || $site->nama_site }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="2"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="foto" class="form-label">Foto Profil</label>
                                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                    <p class="text-muted"><span class="text-danger">*</span> Ukuran file maksimal 2MB dengan rasio maksimal 1080x1080 piksel. </p>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('pengguna.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
