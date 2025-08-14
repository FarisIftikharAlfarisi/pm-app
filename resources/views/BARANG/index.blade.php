@extends('Partials.main')

@section('content')
<div class="pagetitle">
    <h1>Data Barang</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Barang</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Barang</h5>
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createBarangModal">
                        <i class="bi bi-plus-circle"></i> Tambah Barang
                    </button>

                    <!-- Tabel Barang -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Merk</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangs as $barang)
                            <tr>
                                <td>{{ $barang->kode_barang }}</td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td>{{ $barang->kategori }}</td>
                                <td>{{ $barang->merk }}</td>
                                <td>{{ $barang->keterangan }}</td>
                                <td>
                                    <!-- Tombol Edit -->

                                @if($barang->kategori == 'AFTERCRAFT')
                                    <a href="{{ route('barang.edit.aftercraft', $barang->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                @elseif($barang->kategori == 'JASA')
                                    <a href="{{ route('barang.edit.jasa', $barang->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                @elseif($barang->kategori == 'BAHAN_BAKU')
                                    <a href="{{ route('barang.edit.bahanbaku', $barang->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                @elseif($barang->kategori == 'PERALATAN')
                                    <a href="{{ route('barang.edit.peralatan', $barang->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                @endif
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

<!-- Modal Tambah Barang -->
<div class="modal fade" id="createBarangModal" tabindex="-1" aria-labelledby="createBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createBarangModalLabel">Tambah Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <!-- Tombol untuk memilih kategori barang -->
                <div class="d-grid gap-2">
                    <a href="{{ route('barang.tools') }}" class="btn btn btn-outline-primary mb-2"> <i class="bi bi-tools"></i>  Peralatan</a>
                    <a href="{{ route('barang.goods_and_material') }}" class="btn btn-outline-primary mb-2"> <i class="bi bi-bricks"></i>  Bahan Baku</a>
                    <a href="{{ route('barang.add.aftercraft') }}" class="btn btn btn-outline-primary mb-2"> <i class="bi bi-gear-wide-connected"></i>  Aftercraft</a>
                    <a href="" class="btn btn btn-outline-primary mb-2"> <i class="bi bi-people"></i>  Jasa</a>
                </div>
            </div>
        </div>
    </div>
</div>



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
                window.location.href = `/barang/${id}`;
            }
        });
    }

    function restoreBarang(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data akan dikembalikan!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, restore!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/barang/${id}/restore`;
            }
        });
    }
</script>
@endsection
