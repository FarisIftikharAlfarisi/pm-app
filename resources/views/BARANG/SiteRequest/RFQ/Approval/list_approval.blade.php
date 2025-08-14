@extends('Partials.main')

@section('content')
<div class="pagetitle">
    <h1>Detail Permohonan Barang Dari Site</h1>
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
                    <h5 class="card-title text-black">Kode Permohonan : {{ $siteRequest->kode_request }}</h5>
                    <h5 class="card-title text-black">Pemohon : ({{ $siteRequest->creator->Kode_Karyawan }}) {{ $siteRequest->creator->nama}}</h5>
                    <h5 class="card-title text-black">Site Pemohon : {{ $siteRequest->site->nama_site }}</h5>

                    <form action="{{ route('site_request.save_reviews', $siteRequest->id) }}" method="POST">
                        @csrf

                        <input type="hidden" name="site_request_id" value="{{ $siteRequest->id }}">
                        <input type="hidden" name="site_request_code" value="{{ $siteRequest->kode_request }}">

                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                    <th>Status PL</th>
                                    <th>Status Accounting</th>
                                    @if(Auth::user()->role == 'PROJECT_LEADER' || Auth::user()->role == 'ACCOUNTING')
                                        <th>Approve</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siteRequest->details as $detail)
                                <tr>
                                    <td>{{ $detail->barang->Nama_Barang }}</td>
                                    <td>{{ $detail->jumlah }}</td>
                                    <td>{{ $detail->keterangan }}</td>
                                    <td>
                                        @if($detail->approval_project_leader_status === 'APPROVED')
                                            <span class="badge bg-success">Approved</span>

                                        @elseif ($detail->approval_project_leader_status === 'REJECTED')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($detail->approval_accounting_status === 'APPROVED')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif ($detail->approval_accounting_status === 'REJECTED')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    @if(Auth::user()->role == 'PROJECT_LEADER' || Auth::user()->role == 'ACCOUNTING')
                                    <td class="text-center">
                                        @if((Auth::user()->role == 'PROJECT_LEADER' && $detail->approval_project_leader_status == null) ||
                                            (Auth::user()->role == 'ACCOUNTING' && $detail->approval_accounting_status == null))
                                            <input type="checkbox" class="form-check-input" name="approved_items[]" value="{{ $detail->id }}">
                                        @else
                                            @if(Auth::user()->role == 'PROJECT_LEADER' && $detail->approval_project_leader_status == 'APPROVED')
                                                {{-- text persetujuan project leader --}}
                                                <small class="d-block"> Anda menyetujui item ini pada {{ Carbon\Carbon::parse($detail->accounting_approval_date)->format('d/m/Y H:i') }}</small>
                                            @elseif(Auth::user()->role == 'PROJECT_LEADER' && $detail->approval_project_leader_status == 'REJECTED')
                                                <span class="badge bg-danger">Rejected</span>
                                            @elseif(Auth::user()->role == 'ACCOUNTING' && $detail->approval_accounting_status == 'APPROVED')
                                                 {{-- text persetujuan accounting   --}}
                                                 <small class="d-block"> Anda menyetujui item ini pada {{ Carbon\Carbon::parse($detail->accounting_approval_date)->format('d/m/Y H:i') }}</small>
                                            @elseif(Auth::user()->role == 'ACCOUNTING' && $detail->approval_accounting_status == 'REJECTED')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <input type="checkbox" class="form-check-input" name="approved_items[]" value="{{ $detail->id }}">
                                            @endif
                                        @endif
                                    </td>
                                @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if(Auth::user()->role == 'PROJECT_LEADER' || Auth::user()->role == 'ACCOUNTING')

                            @if($detail->approval_project_leader_status == 'PENDING')
                            <div class="mb-3">
                                <label for="comment" class="form-label">Komentar (Opsional)</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Approval</button>
                            @else
                                <div class="alert alert-info" role="alert">
                                    Anda sudah melakukan approval untuk permohonan ini, <a href="{{ route('site_request.review') }}">Kembali.</a>
                                </div>
                            @endif
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
