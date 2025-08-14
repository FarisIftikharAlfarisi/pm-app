@extends('Partials.main')
@section('title', 'Edit Bahan Baku')

@section('content')
<div class="container mt-4">
    <h3>Edit Bahan Baku</h3>

    <!-- Foto Barang -->
    @if ($barang->foto)
        <div class="mb-4 text-center">
            <img src="{{ asset('' . $barang->foto) }}" alt="Foto Barang" class="img-thumbnail" style="max-height: 200px;">
        </div>
    @endif

    {{-- Route Belum di definisikan --}}
    <form action="#" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Card 1: Informasi Dasar -->
        <div class="card mb-4">
            <div class="card-header">Informasi Barang</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="Kode_Barang" class="form-label">Kode Barang</label>
                        <input type="text" class="form-control" id="Kode_Barang" name="Kode_Barang"
                               value="{{ $barang->kode_barang }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="Nama_Barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('Nama_Barang') is-invalid @enderror"
                               id="Nama_Barang" name="Nama_Barang" value="{{ $barang->nama_barang }}" required>
                        @error('Nama_Barang')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="Merk" class="form-label">Merk</label>
                        <input type="text" class="form-control" id="Merk" name="Merk" value="{{ $barang->merk }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="foto" class="form-label">Foto Barang</label>
                        <input type="file" class="form-control @error('foto') is-invalid @enderror"
                               id="foto" name="foto">
                        @error('foto')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="Keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="Keterangan" name="Keterangan">{{ $barang->keterangan }}</textarea>
                </div>
            </div>
        </div>

        <!-- Card 2: Satuan dan Konversi -->
        <div class="card mb-4">
            <div class="card-header">Satuan Pengukuran</div>
            <div class="card-body">
                <div id="satuan-container">
                    @foreach($barang->satuan as $index => $unit)
                        <div class="satuan-item row mb-3 border p-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Nama Satuan*</label>
                                    <input type="text" name="satuan[{{ $index }}][nama]" class="form-control" required value="{{ $unit->unit_name }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Faktor Konversi</label>
                                    <input type="number" step="0.0001" name="satuan[{{ $index }}][konversi]"
                                           class="form-control" value="{{ $unit->conversion_factor }}">
                                    <small class="text-muted">Relatif terhadap satuan dasar</small>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Deskripsi Konversi</label>
                                    <input type="text" name="satuan[{{ $index }}][deskripsi]" class="form-control"
                                           value="{{ $unit->deskripsi_konversi }}">
                                </div>
                                <div class="col-md-1">
                                    <div class="form-check mt-4 pt-2">
                                        <input class="form-check-input" type="checkbox" name="satuan[{{ $index }}][default]"
                                               value="on" id="satuan_default_{{ $index }}" {{ $unit->is_default ? 'checked' : '' }}>
                                        <label class="form-check-label" for="satuan_default_{{ $index }}">
                                            Default
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-secondary" id="tambah-satuan">
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

<!-- Template untuk Satuan Baru -->
<div id="satuan-template" class="d-none">
    <div class="satuan-item row mb-3 border p-3">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="satuan[__INDEX__][nama]" class="form-control" required>
            </div>
            <div class="col-md-4">
                <input type="number" step="0.0001" name="satuan[__INDEX__][konversi]" class="form-control" value="1">
            </div>
            <div class="col-md-3">
                <input type="text" name="satuan[__INDEX__][deskripsi]" class="form-control" placeholder="Contoh: 1 kg = 1000 gram">
            </div>
            <div class="col-md-1">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="satuan[__INDEX__][default]"
                           value="on" id="satuan_default___INDEX__">
                    <label class="form-check-label" for="satuan_default___INDEX__">
                        Default
                    </label>
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-satuan mt-1">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Tambah Satuan Baru
    document.getElementById('tambah-satuan').addEventListener('click', function () {
        const container = document.getElementById('satuan-container');
        const template = document.getElementById('satuan-template').innerHTML;
        const index = document.querySelectorAll('.satuan-item').length;
        const newSatuan = template.replace(/__INDEX__/g, index);
        container.insertAdjacentHTML('beforeend', newSatuan);
    });

    // Hapus Satuan
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-satuan')) {
            const satuanRow = e.target.closest('.satuan-item');
            if (document.querySelectorAll('.satuan-item').length > 1) {
                satuanRow.remove();
                // Set default satuan pertama jika tidak ada yang default
                if (!document.querySelector('[name^="satuan"][name$="[default]"]:checked')) {
                    document.querySelector('[name="satuan[0][default]"]').checked = true;
                }
            } else {
                alert('Minimal harus ada satu satuan');
            }
        }
    });

    // Validasi hanya satu satuan default
    document.addEventListener('change', function (e) {
        if (e.target && e.target.name.includes('[default]') && e.target.checked) {
            const checkboxes = document.querySelectorAll('[name^="satuan"][name$="[default]"]');
            checkboxes.forEach(cb => {
                if (cb !== e.target) cb.checked = false;
            });
        }
    });

    // Validasi sebelum submit
    document.querySelector('form').addEventListener('submit', function (e) {
        const defaultSatuan = document.querySelector('[name^="satuan"][name$="[default]"]:checked');
        if (!defaultSatuan) {
            e.preventDefault();
            alert('Harap tentukan satuan default');
        }
    });
</script>

<style>
    .satuan-item {
        background-color: #f8f9fa;
        border-radius: 5px;
    }

    .remove-satuan {
        cursor: pointer;
    }
</style>
@endsection
