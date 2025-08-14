@extends('Partials.main')

@section('content')
<div class="container mt-4">
    <h3>Tambah Barang yang Disuplai Supplier</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('supplier.store') }}" method="POST">
        @csrf

        <!-- Informasi Supplier -->
        <div class="card mb-4">
            <div class="card-header">Informasi Supplier</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="kode_supplier" class="form-label">Kode Supplier</label>
                        <input type="text" name="kode_supplier" id="kode_supplier" class="form-control" value="{{ $kodeSupplier }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="nama_supplier" class="form-label">Nama Supplier</label>
                        <input type="text" name="nama_supplier" id="nama_supplier" class="form-control" required>
                    </div>
                    <div class="col-md-12">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="contact" class="form-label">Nomor Kontak</label>
                        <input type="text" name="contact" id="contact" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="nama_contact_person" class="form-label">Contact Person</label>
                        <input type="text" name="nama_contact_person" id="nama_contact_person" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="no_rekening" class="form-label">No Rekening</label>
                        <input type="text" name="no_rekening" id="no_rekening" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="bank" class="form-label">Bank</label>
                        <input type="text" name="bank" id="bank" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="atas_nama_bank" class="form-label">Atas Nama Rekening</label>
                        <input type="text" name="atas_nama_bank" id="atas_nama_bank" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Supplier Barang -->
        <div class="card mb-4">
            <div class="card-header">Informasi Pengiriman</div>
            <div class="card-body">
                <div class="row">
                    <!-- Waktu Pengiriman -->
                    <div class="col-md-4 mb-3">
                        <label for="satuan_lama_waktu_pengiriman" class="form-label">Lama Waktu Pengiriman</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="lama_waktu_pengiriman" name="lama_waktu_pengiriman">
                            <select class="form-select" id="satuan_lama_waktu_pengiriman" name="satuan_lama_waktu_pengiriman">
                                <option value="">Pilih Satuan</option>
                                <option value="HARI">Hari</option>
                                <option value="MINGGU">Minggu</option>
                                <option value="BULAN">Bulan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Jarak Pengiriman -->
                    <div class="col-md-4 mb-3">
                        <label for="jarak_pengiriman" class="form-label">Jarak Pengiriman</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="jarak_pengiriman" name="jarak_pengiriman">
                            <select class="form-select" id="satuan_jarak_pengiriman" name="satuan_jarak_pengiriman">
                                <option value="">Pilih Satuan</option>
                                <option value="KM">Kilometer</option>
                                <option value="M">Meter</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barang yang Disuplai -->
        <div class="card mb-4">
            <div class="card-header">Barang yang Disuplai</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Satuan</th>
                            <th>Harga</th>
                            <th>Min. Order</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="supplier-barang-body">
                        <tr>
                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control nama-barang" readonly placeholder="Klik untuk pilih barang" data-bs-toggle="modal" data-bs-target="#modalPilihBarang">
                                    <input type="hidden" name="barang_id[]" class="barang-id">
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control satuan-barang" readonly>
                                <input type="hidden" name="item_unit[]" class="unit-nama">
                            </td>
                            <td><input type="number" name="harga[]" class="form-control" step="0.01" required></td>
                            <td><input type="number" name="min_order[]" class="form-control" step="1" ></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
                        </tr>
                    </tbody>
                                    </table>
                <button type="button" class="btn btn-secondary" id="add-row">Tambah Barang</button>
            </div>
        </div>

        <div class="text-end mb-5">
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>

<!-- Modal Pilih Barang -->
<div class="modal fade" id="modalPilihBarang" tabindex="-1" aria-labelledby="modalPilihBarangLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="searchBarang" class="form-control mb-3" placeholder="Cari barang...">
                <table class="table table-bordered" id="barangTable">
                    <thead>
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangs as $barang)
                        <tr>
                            <td>{{ $barang->kode_barang }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>
                                @foreach($barang->satuan as $unit)
                                    <div class="mb-1">
                                        <button type="button"
                                            class="btn btn-sm pilih-barang {{ $unit->is_default ? 'btn-success' : 'btn-primary' }}"
                                            data-id="{{ $barang->id }}"
                                            data-nama="{{ $barang->nama_barang }}"
                                            data-uom="{{ $unit->unit_name }}"
                                            data-uom-id="{{ $unit->id }}">
                                            Pilih ({{ $unit->unit_name }})
                                        </button>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Script -->
<script>
    let activeRow = null;

    // Tambah baris baru
    document.getElementById('add-row').addEventListener('click', function () {
        const tbody = document.getElementById('supplier-barang-body');
        const newRow = tbody.rows[0].cloneNode(true);

        newRow.querySelectorAll('input').forEach(input => input.value = '');
        tbody.appendChild(newRow);
    });

    // Pilih baris aktif saat input barang diklik
    document.addEventListener('focusin', function (e) {
        if (e.target.classList.contains('nama-barang')) {
            activeRow = e.target.closest('tr');
        }
    });

    // Pilih barang dari modal
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('pilih-barang')) {
        const id = e.target.dataset.id;
        const nama = e.target.dataset.nama;
        const unit = e.target.dataset.uom;

        if (activeRow) {
            activeRow.querySelector('.nama-barang').value = nama;
            activeRow.querySelector('.barang-id').value = id;
            activeRow.querySelector('.satuan-barang').value = unit;
            activeRow.querySelector('.unit-nama').value = unit;
        }

            bootstrap.Modal.getInstance(document.getElementById('modalPilihBarang')).hide();
        }

        // Hapus baris
        if (e.target.classList.contains('remove-row')) {
            const row = e.target.closest('tr');
            const tbody = row.parentElement;
            if (tbody.rows.length > 1) {
                row.remove();
            }
        }
    });

    // Filter barang di modal
    document.getElementById('searchBarang').addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        document.querySelectorAll('#barangTable tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(keyword) ? '' : 'none';
        });
    });
</script>
@endsection
