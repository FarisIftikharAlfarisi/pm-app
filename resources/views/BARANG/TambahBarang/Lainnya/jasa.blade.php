@extends('Partials.main')

@section('content')
<div class="container mt-4">
    <h3>Tambah Barang (Jasa) </h3>

    <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- <input type="hidden" name="kategori" value="{{ $kategori }}"> --}}

        <!-- Card 1: Informasi Dasar -->
        <div class="card mb-4">
            <div class="card-header">Informasi Dasar Barang</div>
            <div class="card-body">
                <div class="row">
                    {{-- <div class="col-md-6 mb-3">
                        <label for="kode_barang" class="form-label">Kode Barang</label>
                        <input type="text" class="form-control" id="kode_barang" name="kode_barang"
                                 value="{{ $kode_barang }}">
                    </div> --}}
                    <div class="col-md-6 mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                               required>
                    </div>
                        <div class="col-md-6 mb-3">
                        <label for="kategori" class="form-label">Kategori<span class="text-danger">*</span> </label>
                        <select name="kategori" id="kategori" class="form-control">
                            <option value="JASA_INTERNAL"> Jasa Internal </option>
                            <option value="JASA_EKSTERNAL"> Jasa Eksternal </option>
                        </select>
                        </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="merk" class="form-label">Merk</label>
                        <input type="text" class="form-control" id="merk" name="merk"
                               >
                    </div>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan"> </textarea>
                </div>
            </div>
        </div>

        <!-- Card 2: Satuan dan Konversi -->
        <div class="card mb-4">
            <div class="card-header">Satuan Pengukuran</div>
            <div class="card-body">
                <div id="unit-container">
                    <div class="unit-row mb-3 border p-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Nama Satuan*</label>
                                <input type="text" name="units[0][unit_name]" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Faktor Konversi*</label>
                                <input type="number" step="0.0001" name="units[0][conversion_factor]"
                                       class="form-control" value="1" required>
                                <small class="text-muted">Relatif terhadap satuan dasar</small>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check mt-4 pt-2">
                                    <input class="form-check-input" type="radio" name="default_unit"
                                           value="0" id="default_unit_0" checked>
                                    <label class="form-check-label" for="default_unit_0">
                                        Jadikan Satuan Default
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-secondary" id="add-unit">
                    <i class="fas fa-plus"></i> Tambah Satuan
                </button>
            </div>
        </div>

        <!-- Submit -->
        <div class="text-end mb-5">
            <button type="submit" class="btn btn-primary">Simpan Barang</button>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<!-- Template untuk Satuan Baru (Hidden) -->
<div id="unit-template" class="d-none">
    <div class="unit-row mb-3 border p-3">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="units[__INDEX__][unit_name]" class="form-control" required>
            </div>
            <div class="col-md-4">
                <input type="number" step="0.0001" name="units[__INDEX__][conversion_factor]"
                       class="form-control" value="1" required>
            </div>
            <div class="col-md-3">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" name="default_unit"
                           value="__INDEX__" id="default_unit___INDEX__">
                    <label class="form-check-label" for="default_unit___INDEX__">
                        Jadikan Satuan Default
                    </label>
                </div>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-unit mt-1">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Tambah Satuan Baru
    document.getElementById('add-unit').addEventListener('click', function() {
        const container = document.getElementById('unit-container');
        const template = document.getElementById('unit-template').innerHTML;
        const index = document.querySelectorAll('.unit-row').length;

        const newUnit = template.replace(/__INDEX__/g, index);
        container.insertAdjacentHTML('beforeend', newUnit);
    });

    // Hapus Satuan
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-unit')) {
            const unitRow = e.target.closest('.unit-row');
            if (document.querySelectorAll('.unit-row').length > 1) {
                unitRow.remove();
                // Update radio button names
                document.querySelector('[name="default_unit"][value="0"]').checked = true;
            } else {
                alert('Minimal harus ada satu satuan');
            }
        }
    });

    // Validasi sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const defaultUnit = document.querySelector('[name="default_unit"]:checked');
        if (!defaultUnit) {
            e.preventDefault();
            alert('Harap tentukan satuan default');
        }
    });
</script>

<style>
    .unit-row {
        background-color: #f8f9fa;
        border-radius: 5px;
    }
    .remove-unit {
        cursor: pointer;
    }
</style>
@endsection
