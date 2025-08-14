@extends('Partials.main')

@section('content')
<div class="pagetitle">
    <h1>Data Penerimaan Barang Site</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Penerimaan Barang Site</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Penerimaan Barang Site</h5>
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createPenerimaanModal">
                        <i class="bi bi-plus-circle"></i> Penerimaan Baru
                    </button>

                    <!-- Tabel Penerimaan Barang -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Kode Penerimaan</th>
                                <th>Site</th>
                                <th>Penerima</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penerimaanBarangSites as $penerimaan)
                            <tr>
                                <td>{{ $penerimaan->Kode_Penerimaan }}</td>
                                <td>{{ $penerimaan->site }}</td>
                                <td>{{ $penerimaan->Penerima }}</td>
                                <td>
                                    <a href="{{ route('penerimaan_barang_site.list_barang', $penerimaan->id) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-list"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah Penerimaan Baru -->
<div class="modal fade" id="createPenerimaanModal" tabindex="-1" aria-labelledby="createPenerimaanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPenerimaanModalLabel">Tambah Penerimaan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('penerimaan_barang_site.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="Kode_Penerimaan" class="form-label">Nomor Penerimaan</label>
                        <input type="text" class="form-control" id="Kode_Penerimaan" name="Kode_Penerimaan" required>
                    </div>

                    <div class="mb-3">
                        <label for="Kode_Penerimaan" class="form-label">Nomor Permohonan</label>
                        <input type="text" class="form-control" id="kode_request" name="kode_request" required>
                    </div>

                    <div class="mb-3">
                        <label for="Penerima" class="form-label">Penerima</label>
                        <input type="text" class="form-control" id="Penerima" name="Penerima" required>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="sayaPenerima">
                            <label class="form-check-label" for="sayaPenerima">
                                Saya penerimanya.
                            </label>
                        </div>
                    </div>
                    <!-- Input tersembunyi untuk menyimpan ID user yang login -->
                    <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Lanjut</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script untuk mengisi otomatis field Penerima -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('sayaPenerima');
        const penerimaInput = document.getElementById('Penerima');
        const userIdInput = document.getElementById('user_id');
        const userNama = "{{ Auth::user()->nama }}"; // Ambil nama user yang login

        checkbox.addEventListener('change', function() {
            if (this.checked) {
                penerimaInput.value = userNama; // Isi dengan nama user yang login
                penerimaInput.readOnly = true; // Buat field menjadi read-only
            } else {
                penerimaInput.value = ''; // Kosongkan field
                penerimaInput.readOnly = false; // Kembalikan ke mode bisa diisi
            }
        });
    });
</script>
@endsection
