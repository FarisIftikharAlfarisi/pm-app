@extends('Partials.main')

@section('content')
<div class="container">

    <!-- FORM TAMBAH TASK (BAGIAN STRUKTUR WBS) -->
    <form id="form-task-structure" action="{{ route('task-to-do.store') }}" method="POST">
        <h2 class="mb-4">Tambah Task WBS</h2>

        <!-- BAGIAN 1: STRUKTUR WBS -->
    <div class="card mb-4">
        <div class="card-header">Struktur WBS</div>
        <div class="card-body">

                @csrf

                <input type="text" name="kode_proyek" value="{{ $project->id }}" hidden>

                <div class="row mb-3">
                    <div class="col">
                        <label>Kode Task</label>
                        <input type="text" class="form-control" name="kode_task" value="{{ $lastSiteTaskCode }}" readonly required>
                    </div>
                    <div class="col">
                        <label>Nama Task</label>
                        <input type="text" class="form-control" name="nama_task" required>
                    </div>
                    <div class="col">
                        <label for="type" class="form-label">Tipe Task</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="" disabled selected>Pilih Type</option>
                            <option value="general">General</option>
                            <option value="procurement">Procurement</option>
                            <option value="production">Production</option>
                            <option value="delivery">Delivery</option>
                            <option value="inspection">Inspection</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="2"></textarea>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>Start Date</label>
                        <input type="datetime-local" class="form-control" name="start_date">
                    </div>
                    <div class="col">
                        <label>End Date</label>
                        <input type="datetime-local" class="form-control" name="end_date">
                    </div>
                    <div class="col">
                        <label>Estimated Hours</label>
                        <input type="number" step="0.1" class="form-control" name="estimated_hours">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>Parent Task (optional)</label>
                        <select name="parent_id" class="form-control">
                            <option value="">- None -</option>
                            @foreach($existingTasks as $task)
                                <option value="{{ $task->id }}">{{ $task->nama_task }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label>Predecessor</label>
                        <select name="predecessor_id" class="form-control" id="predecessor_id">
                            <option value="">- None -</option>
                            @foreach($existingTasks as $task)
                                <option value="{{ $task->id }}">{{ $task->nama_task }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

        </div>
    </div>

    <!-- LIST TASK YANG DITAMBAHKAN -->
    <div class="card mb-4">
        <div class="card-header">Daftar Task WBS Saat Ini</div>
        <div class="card-body" id="task-list">
            <!-- AJAX populated content -->
        </div>
    </div>

    <!-- BAGIAN 2: TAMBAH KEBUTUHAN BARANG -->
    <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Kebutuhan Barang</span>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th></th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="barang-table-body">
                <tr>
                    <td>
                        <input type="text" class="form-control barang-nama" placeholder="Klik untuk memilih barang"
                               readonly data-bs-toggle="modal" data-bs-target="#modalPilihBarang">
                        <input type="hidden" name="barang_id[]" class="barang-id">
                        <input type="hidden" name="barang_uom_id[]" class="barang-uom-id">
                    </td>
                    <td>
                        <input type="text" class="form-control barang-uom" placeholder="Satuan" readonly>
                    </td>
                    <td>
                        <input type="number" step="0.01" name="jumlah[]" class="form-control" placeholder="Jumlah">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-barang-row">Hapus</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-sm btn-primary" id="add-barang-row">+ Tambah Barang</button>
    </div>
</div>


    <!-- FINALISASI -->
    <div class="text-center">
        <button type="submit" class="btn btn-lg btn-primary" id="finalize-wbs">Tambah Tugas</button>
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
                <div class="mb-3">
                    <input type="text" class="form-control" id="searchBarang" placeholder="Cari barang...">
                </div>
                <table class="table table-bordered" id="barangTable">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Satuan Default</th>
                            <th>Satuan</th>
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
                                            <button type="button" class="btn btn-sm btn-primary pilih-barang" data-bs-dismiss="modal"
                                                data-id="{{ $bb->id }}"
                                                data-nama="{{ $bb->nama_barang }}"
                                                data-uom="{{ $satuan->unit_name }}"
                                                data-uom-id="{{ $satuan->id }}">
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


<script>
    let activeBarangRow = null;

    // Tambah baris
    document.getElementById('add-barang-row').addEventListener('click', function () {
        const tbody = document.getElementById('barang-table-body');
        const firstRow = tbody.rows[0];
        const newRow = firstRow.cloneNode(true);

        newRow.querySelectorAll('input').forEach(input => {
            if (input.type !== 'hidden') input.value = '';
        });

        tbody.appendChild(newRow);
    });

    // Fokus row aktif sebelum buka modal
    document.addEventListener('focusin', function (e) {
        if (e.target.classList.contains('barang-nama')) {
            activeBarangRow = e.target.closest('tr');
        }
    });

    // Pilih barang dari modal
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('pilih-barang') && activeBarangRow) {
            const id = e.target.dataset.id;
            const nama = e.target.dataset.nama;
            const uom = e.target.dataset.uom;
            const uomId = e.target.dataset.uomId;

            activeBarangRow.querySelector('.barang-nama').value = nama;
            activeBarangRow.querySelector('.barang-id').value = id;
            activeBarangRow.querySelector('.barang-uom').value = uom;
            activeBarangRow.querySelector('.barang-uom-id').value = uomId;

            const modal = bootstrap.Modal.getInstance(document.getElementById('modalPilihBarang'));
            modal.hide();
        }
    });

    // Hapus baris
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-barang-row')) {
            const row = e.target.closest('tr');
            const tbody = document.getElementById('barang-table-body');
            if (tbody.rows.length > 1) row.remove();
        }
    });

    // Search dalam modal
    document.getElementById('searchBarang').addEventListener('input', function (e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('#barangTable tbody tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    });
</script>


@endsection
