@extends('Partials.main')

@section('content')
<div class="container mt-4">
    <h3>Tambah Barang Aftercraft</h3>

    @error('error')
        <div class="alert alert-danger">
            {{ $message }}
        </div>

    @enderror

    <!-- Form -->
    <form action="{{ route('barang.store.aftercraft') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Card 1: Informasi Barang (General) -->
        <div class="card mb-4">
            <div class="card-header">Informasi Umum Barang</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kode_barang" class="form-label">Kode Barang</label>
                        <input type="text" class="form-control" id="kode_barang" name="kode_barang" value="{{ $kode_barang }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="foto_path" class="form-label">Foto Barang</label>
                    <input type="file" class="form-control" id="foto_path" name="foto_path">
                </div>
            </div>
        </div>

        <!-- Card 2: Informasi BOM (Khusus Aftercraft) -->
        <div class="card mb-4">
            <div class="card-header">Bill of Material (BOM)</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kode_bom" class="form-label">Kode BOM</label>
                        <input type="text" class="form-control" id="kode_bom" name="kode_bom" value="{{ $kode_bom }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nama_bom" class="form-label">Nama BOM</label>
                        <input type="text" class="form-control" id="nama_bom" name="nama_bom" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="quantity" class="form-label">Quantity Produksi</label>
                        <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="unit_of_measure" class="form-label">Satuan Produksi</label>
                        <input type="text" class="form-control" id="unit_of_measure" name="unit_of_measure" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="catatan_produksi" class="form-label">Catatan Produksi</label>
                    <textarea class="form-control" id="catatan_produksi" name="catatan_produksi" rows="3"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="waktu_produksi" class="form-label">Estimasi Waktu Produksi</label>
                        <input type="number" class="form-control" id="waktu_produksi" name="waktu_produksi" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="satuan_estimasi_waktu_produksi" class="form-label">Satuan Estimasi Waktu</label>
                        <select name="satuan_estimasi_waktu_produksi" id="satuan_estimasi_waktu_produksi" class="form-select" required>
                            <option value="NULL">Pilih Satuan</option>
                            <option value="MENIT">Menit</option>
                            <option value="JAM">Jam</option>
                            <option value="HARI">Hari</option>
                            <option value="MINGGU">Minggu</option>
                            <option value="BULAN">Bulan</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Komponen Bahan Baku -->
        <div class="card mb-4">
            <div class="card-header">Komponen Bahan Baku</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Bahan Baku</th>
                            <th>Quantity</th>
                            <th>Satuan</th>
                            <th>Toleransi (%)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="component-table-body">
                        <tr>
                            <td>
                                <input type="text" class="form-control nama-barang" readonly
                                       data-bs-toggle="modal" data-bs-target="#modalPilihBarang"
                                       placeholder="Klik untuk memilih bahan baku">
                                <input type="hidden" name="bahan_baku_id[]" class="bahan-baku-id">
                            </td>
                            <td>
                                <input type="number" step="0.01" name="component_quantity[]" class="form-control" required>
                            </td>
                            <td>
                                <input type="text" name="component_uom[]" class="form-control component-uom" required readonly>
                            </td>
                            <td>
                                <input type="number" step="0.01" name="toleransi_quantity[]" class="form-control" value="0">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-secondary" id="add-row">Tambah Komponen</button>
            </div>
        </div>

        <!-- Submit -->
        <div class="text-end mb-5">
            <button type="submit" class="btn btn-success">Simpan Barang Aftercraft</button>
        </div>
    </form>
</div>

<!-- Modal Pilih Barang -->
<div class="modal fade" id="modalPilihBarang" tabindex="-1" aria-labelledby="modalPilihBarangLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Bahan Baku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="searchBarang" placeholder="Cari bahan baku...">
                </div>
                <table class="table table-bordered" id="barangTable">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Satuan Default</th>
                            <th> Satuan </th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangs as $bb)
                            <tr>
                                <td>{{ $bb->kode_barang }}</td>
                                <td>{{ $bb->nama_barang }}</td>
                                <td>{{ $bb->kategori }}</td>
                                <td>
                                    @php
                                        $default = $bb->satuan->firstWhere('is_default', true);
                                    @endphp
                                    @if($default)
                                        {{ $default->unit_name }} (± {{ $default->conversion_factor }})
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @foreach($bb->satuan as $satuan)
                                        <div>
                                            {{ $satuan->unit_name }}
                                            @if($satuan->conversion_factor != 1)
                                                (± {{ $satuan->conversion_factor }})
                                            @endif
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                     @foreach($bb->satuan as $satuan)
                                        <div class="mb-1">
                                            <button type="button" class="btn btn-sm btn-primary pilih-barang"
                                                data-id="{{ $bb->id }}"
                                                data-nama="{{ $bb->nama_barang }}"
                                                data-uom="{{ $satuan->unit_name }}"
                                                data-uom-id="{{ $satuan->id }}"
                                                data-konversi="{{ $satuan->conversion_factor }}"
                                                data-kategori="{{ $bb->kategori }}"
                                            >
                                                Pilih ({{ $satuan->unit_name }})
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
    document.getElementById('add-row').addEventListener('click', function () {
        const tbody = document.getElementById('component-table-body');
        const newRow = tbody.rows[0].cloneNode(true);

        // Clear all input values in the new row
        newRow.querySelectorAll('input').forEach(input => {
            if (input.type !== 'hidden') {
                input.value = '';
            }
        });

        tbody.appendChild(newRow);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            const tbody = document.getElementById('component-table-body');
            if (tbody.rows.length > 1) {
                e.target.closest('tr').remove();
            }
        }
    });

    // Track active row for modal selection
    let activeRow = null;

    document.addEventListener('focusin', function(e) {
        if (e.target.classList.contains('nama-barang')) {
            activeRow = e.target.closest('tr');
        }
    });

    // Handle barang selection from modal
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('pilih-barang') && activeRow) {
            const id = e.target.dataset.id;
            const nama = e.target.dataset.nama;
            const uom = e.target.dataset.uom;

            activeRow.querySelector('.nama-barang').value = nama;
            activeRow.querySelector('.bahan-baku-id').value = id;
            activeRow.querySelector('.component-uom').value = uom;

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalPilihBarang'));
            modal.hide();
        }
    });

    // Search functionality in modal
    document.getElementById('searchBarang').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#barangTable tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
@endsection
