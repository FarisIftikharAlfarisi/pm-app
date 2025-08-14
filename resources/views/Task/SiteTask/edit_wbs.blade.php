@extends('Partials.main')

@section('title', 'Edit WBS Task')

@section('content')
<div class="container">
    <h2>Edit WBS Task {{ $task->kode_task }}</h2>

    <form method="POST" action="" multipart="multipart/form-data">
        {{-- CSRF Token and Method --}}
        @csrf
        @method('PUT')

        {{-- Informasi Umum --}}
        <div class="card mb-4">
            <div class="card-header">Informasi Umum</div>
            <div class="card-body">
                <div class="form-group">
                    <label>Nama Task</label>
                    <input type="text" name="nama_task" class="form-control" value="{{ old('nama_task', $task->nama_task) }}" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $task->deskripsi) }}</textarea>
                </div>

                <div class="form-group col-md-6">
    <label>Tanggal Mulai</label>
    <input type="date" name="start_date" class="form-control"
        value="{{ old('start_date', $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('Y-m-d') : '') }}"
        required>
</div>

<div class="form-group col-md-6">
    <label>Tanggal Selesai</label>
    <input type="date" name="end_date" class="form-control"
        value="{{ old('end_date', $task->end_date ? \Carbon\Carbon::parse($task->end_date)->format('Y-m-d') : '') }}"
        required>
</div>

                <div class="form-group">
                    <label>Predecessor</label>
                    <select name="predecessor_id" class="form-control">
                        <option value="">-- Tidak ada --</option>
                        @foreach($existingTasks as $t)
                            @if($t->id != $task->id)
                                <option value="{{ $t->id }}" {{ $task->predecessor_id == $t->id ? 'selected' : '' }}>
                                    {{ $t->nama_task }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Daftar Kebutuhan (Dimodifikasi) -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Daftar Kebutuhan</span>
                <button type="button" class="btn btn-sm btn-success" id="addKebutuhanBtn">+ Tambah Kebutuhan</button>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="kebutuhanTable">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="kebutuhanTableBody">
    @foreach ($task->kebutuhanBarangWbs as $index => $kebutuhan)
    <tr class="existing-row" data-row-type="existing">
        <td>
            <input type="text"
                   class="form-control nama-barang" data-bs-toggle="modal"
                   data-bs-target="#modalPilihBarang"
                   value="{{ $kebutuhan->barang->nama_barang }}"
                   readonly>
            <input type="hidden"
                   name="kebutuhan[{{ $index }}][barang_id]"
                   value="{{ $kebutuhan->barang_id }}">
        </td>
        <td>
            <input type="number"
                   name="kebutuhan[{{ $index }}][jumlah]"
                   class="form-control"
                   value="{{ $kebutuhan->jumlah }}"
                   step="0.01" min="0" required>
        </td>
        <td>
            <input type="text"
                   class="form-control satuan"
                   value="{{ $kebutuhan->satuan->unit_name }}"
                   readonly>
            <input type="hidden"
                   name="kebutuhan[{{ $index }}][satuan_id]"
                   value="{{ $kebutuhan->satuan_id }}">
        </td>
        <td>
            <input type="text"
                   name="kebutuhan[{{ $index }}][deskripsi_konversi]"
                   class="form-control"
                   value="{{ $kebutuhan->deskripsi_konversi }}">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger removeRow">
                Hapus
            </button>
        </td>
    </tr>
    @endforeach
</tbody>
                </table>
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-primary">Update WBS</button>
        </div>
    </form>
</div>

<!-- Modal Pilih Barang (Diadaptasi dari Aftercraft) -->
<div class="modal fade" id="modalPilihBarang" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control mb-3" id="searchBarangWBS" placeholder="Cari barang...">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangs as $barang)
                            @foreach($barang->satuan as $satuan)
                            <tr>
                                <td>{{ $barang->kode_barang }}</td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td>{{ $barang->kategori }}</td>
                                <td>{{ $satuan->unit_name }}</td>
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-primary pilih-barang-wbs" data-bs-dismiss="modal"
                                            data-id="{{ $barang->id }}"
                                            data-nama="{{ $barang->nama_barang }}"
                                            data-satuan="{{ $satuan->unit_name }}"
                                            data-satuan-id="{{ $satuan->id }}">
                                        Pilih ({{ $satuan->unit_name }})
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Track active row
    let activeRowWBS = null;

    // Set active row when clicking barang input
    document.addEventListener('focusin', (e) => {
        if (e.target.classList.contains('nama-barang')) {
            activeRowWBS = e.target.closest('tr');
        }
    });

    // Handle barang selection from modal
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('pilih-barang-wbs') && activeRowWBS) {
            const barangId = e.target.dataset.id;
            const namaBarang = e.target.dataset.nama;
            const satuan = e.target.dataset.satuan;
            const satuanId = e.target.dataset.satuanId;

            activeRowWBS.querySelector('.nama-barang').value = namaBarang;
            activeRowWBS.querySelector('.barang-id').value = barangId;
            activeRowWBS.querySelector('.satuan').value = satuan;
            activeRowWBS.querySelector('.satuan-id').value = satuanId;

            // Auto-set jenis based on kategori
            const kategori = e.target.closest('tr').querySelector('td:nth-child(3)').textContent;
            const jenisSelect = activeRowWBS.querySelector('select[name*="[jenis]"]');

            if (kategori.includes('PERALATAN')) jenisSelect.value = 'alat';
            else if (kategori.includes('BAHAN_BAKU')) jenisSelect.value = 'bahan_baku';
            else if (kategori.includes('JASA')) jenisSelect.value = 'man_power';

            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('modalPilihBarang')).hide();
        }
    });

    // Search functionality
    document.getElementById('searchBarangWBS').addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('#modalPilihBarang tbody tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    });

    let newRowIndex = {{ count($task->kebutuhanBarangWbs) }};

document.getElementById('addKebutuhanBtn').addEventListener('click', function() {
    const tbody = document.getElementById('kebutuhanTableBody');
    const newRow = document.createElement('tr');
    newRow.className = 'new-row';
    newRow.setAttribute('data-row-type', 'new');

    newRow.innerHTML = `
        <td>
            <input type="text"
                   class="form-control nama-barang"
                   readonly
                   data-bs-toggle="modal"
                   data-bs-target="#modalPilihBarang">
            <input type="hidden"
                   name="kebutuhan[${newRowIndex}][barang_id]"
                   class="barang-id">
        </td>
        <td>
            <input type="number"
                   name="kebutuhan[${newRowIndex}][jumlah]"
                   class="form-control"
                   step="0.01" min="0" required>
        </td>
        <td>
            <input type="text"
                   class="form-control satuan"
                   readonly>
            <input type="hidden"
                   name="kebutuhan[${newRowIndex}][satuan_id]"
                   class="satuan-id">
        </td>
        <td>
            <input type="text"
                   name="kebutuhan[${newRowIndex}][deskripsi_konversi]"
                   class="form-control">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger removeRow">
                Hapus
            </button>
        </td>
    `;

    tbody.appendChild(newRow);
    newRowIndex++;
});

// Fungsi untuk menghapus row
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('removeRow')) {
        const row = e.target.closest('tr');
        if (row.getAttribute('data-row-type') === 'new') {
            row.remove(); // Langsung hapus jika row baru
        } else {
            // Jika row existing, tambahkan input flag untuk hapus
            row.innerHTML += `
                <input type="hidden"
                       name="kebutuhan[${row.rowIndex}][_delete]"
                       value="1">
            `;
            row.style.display = 'none'; // Sembunyikan row
        }
    }
});
</script>
@endsection
