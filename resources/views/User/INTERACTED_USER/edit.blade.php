@extends('Partials.main')

@section('content')
<div class="pagetitle">
    <h1>Edit Pengguna Sistem</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('pengguna.index') }}">Pengguna Sistem</a></li>
            <li class="breadcrumb-item active">Edit Pengguna</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form Edit Pengguna</h5>

                    <form action="{{ route('pengguna.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6 class="fw-bold">Informasi Akun</h6>
                                <hr>
                                <div class="mb-3">
                                    <label for="Kode_Karyawan" class="form-label">Kode Karyawan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="Kode_Karyawan" name="Kode_Karyawan" value="{{ $user->Kode_Karyawan }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="{{ $user->nama }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password (Kosongkan jika tidak diubah)</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                        <option value="manager" {{ $user->role == 'manager' ? 'selected' : '' }}>Manager</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Informasi Tambahan</h6>
                                <hr>
                                <div class="mb-3">
                                    <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" value="{{ $user->detail->nomor_telepon ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label for="jabatan" class="form-label">Jabatan</label>
                                    <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ $user->detail->jabatan ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label for="Kode_Site" class="form-label">Site <span class="text-danger">*</span></label>
                                    <select class="form-select" id="Kode_Site" name="Kode_Site" required>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}" {{ ($user->detail->Kode_Site ?? '') == $site->id ? 'selected' : '' }}>
                                                {{ $site->nama_site }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="2">{{ $user->detail->alamat ?? '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="foto" class="form-label">Foto Profil</label>
                                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                    <p class="text-muted"><span class="text-danger">*</span> Ukuran file maksimal 2MB dengan rasio maksimal 1080x1080 piksel. </p>
                                    @if($user->detail && $user->detail->foto)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/pengguna/'.$user->detail->foto) }}" width="100" class="img-thumbnail">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" id="hapus_foto" name="hapus_foto">
                                                <label class="form-check-label" for="hapus_foto">
                                                    Hapus foto saat disimpan
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="{{ route('pengguna.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
