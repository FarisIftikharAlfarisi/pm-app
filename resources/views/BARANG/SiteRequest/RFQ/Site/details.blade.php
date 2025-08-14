@extends('Partials.main')

@section('content')
<div class="pagetitle">
    <h1>Detail Permohonan Barang dari Site </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Detail Permohonan</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Kode Permohonan : {{ $siteRequest->kode_request }}</h5>

                    @if ($siteRequest->approval_project_leader_status == "APPROVED" && $siteRequest->approval_accounting_status == "APPROVED")
                        <button class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#declineModal">
                            Tambah Barang
                        </button>
                        <br>
                        <span class="text-danger"> <i class="bi bi-exclamation-circle"></i> Penambahan Barang Sudah Tidak Diperbolehkan</span>
                    @else
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createDetailModal">
                        Tambah Barang
                    </button>
                    @endif

                    <div class="d-flex justify-content-between mb-3">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siteRequest->details as $detail)
                            <tr>
                                <td>{{ $detail->barang->Nama_Barang }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>{{ $detail->keterangan }}</td>
                                <td>

                                    @if ($siteRequest->approval_project_leader_status == "APPROVED" && $siteRequest->approval_accounting_status == "APPROVED")
                                        <button class="btn btn-danger btn-sm" disabled>
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    @else

                                    <form action="{{ route('site-request.destroyDetail', ["id" => $detail->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $detail->id }})">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
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

<!-- Modal Tambah Detail Barang -->
<div class="modal fade" id="createDetailModal" tabindex="-1" aria-labelledby="createDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDetailModalLabel">Tambah Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('site-request.storeDetail', $siteRequest->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ID_Barang" class="form-label">Barang <span class="text-danger">*</span></label>
                        <select class="form-control" id="ID_Barang" name="barang_id" required>
                            @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}">{{ $barang->Nama_Barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="text-center">
                <div class="modal-header">
                    <h5 class="modal-title" id="declineModalLabel">Penambahan Barang Sudah Tidak Diperbolehkan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Penambahan barang sudah tidak diperbolehkan karena permohonan sudah disetujui oleh Project Leader selaku Project Leader dan Accounting.</p>
                    <p>Silahkan hubungi Project Leader atau Accounting untuk informasi lebih lanjut.</p>
            </div>

        </div>
    </div>
</div>


{{-- Decline Modal --}}

<!-- SweetAlert untuk Konfirmasi Hapus -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                window.location.href = `/penerimaan_barang_site/detail/${id}/delete`;
            }
        });
    }
</script>
@endsection
