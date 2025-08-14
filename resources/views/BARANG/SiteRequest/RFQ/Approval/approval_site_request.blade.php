@extends('Partials.main')

@section('content')
<div class="pagetitle">
    <h1>Permintaan Barang Dari Site</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Permohonan</a></li>
            <li class="breadcrumb-item active">Permohonan Site</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Daftar Permohonan Dari Site</h5>

                  <!-- Bordered Tabs -->
                  <ul class="nav nav-pills mb-3" id="borderedTab" role="tablist">

                    <li class="nav-item" role="presentation">
                      <button class="nav-link active" id="home-pending" data-bs-toggle="tab" data-bs-target="#bordered-pending" type="button" role="tab" aria-controls="pending" aria-selected="true">Pending</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" id="halfapprove-tab" data-bs-toggle="tab" data-bs-target="#bordered-halfapprove" type="button" role="tab" aria-controls="halfapprove" aria-selected="false">Half Approve</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" id="fullapprove-tab" data-bs-toggle="tab" data-bs-target="#bordered-fullapprove" type="button" role="tab" aria-controls="fullapprove" aria-selected="false">Full Approve</button>
                    </li>
                  </ul>

                  <hr>

                  <!-- Tabel Tabel View -->
                  <div class="tab-content pt-2" id="borderedTabContent">
                    <div class="tab-pane fade show active" id="bordered-pending" role="tabpanel" aria-labelledby="home-tab">
                    <!-- Tabel Permintaan / Request Dari Site -->

                    {{-- Pending --}}

                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Kode Request</th>
                                <th>Nama Request</th>
                                <th>Asal Permohonan</th>
                                <th>Pemohon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingRequests as $request)
                            <tr>
                                <td>{{ $request->kode_request }}</td>
                                <td>{{ $request->nama_request }}</td>
                                <td>{{ $request->site->nama_site }}</td>
                                <td>{{ $request->creator->nama }}</td>
                                <td>
                                    <a href="{{ route('site_request.detail_review', $request->id) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-list"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>


                    <div class="tab-pane fade" id="bordered-halfapprove" role="tabpanel" aria-labelledby="halfapprove-tab">
                        {{-- Half Approve --}}
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Kode Request</th>
                                    <th>Nama Request</th>
                                    <th>Asal Permohonan</th>
                                    <th>Pemohon</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($halfApproveRequests as $request)
                                <tr>
                                    <td>{{ $request->kode_request }}</td>
                                    <td>{{ $request->nama_request }}</td>
                                    <td>{{ $request->site->nama_site }}</td>
                                    <td>{{ $request->creator->nama }}</td>
                                    <td>
                                        <a href="{{ route('site_request.detail_review', $request->id) }}" class="btn btn-info btn-sm">
                                            <i class="bi bi-list"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                    <div class="tab-pane fade" id="bordered-fullapprove" role="tabpanel" aria-labelledby="fullapprove-tab">
                        {{-- Full Approve --}}
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Kode Request</th>
                                    <th>Nama Request</th>
                                    <th>Asal Permohonan</th>
                                    <th>Pemohon</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fullApproveRequests as $request)
                                <tr>
                                    <td>{{ $request->kode_request }}</td>
                                    <td>{{ $request->nama_request }}</td>
                                    <td>{{ $request->site->nama_site }}</td>
                                    <td>{{ $request->creator->nama }}</td>
                                    <td>
                                        <a href="{{ route('site_request.detail_review', $request->id) }}" class="btn btn-info btn-sm">
                                            <i class="bi bi-list"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                  </div><!-- End Bordered Tabs -->
                </div>
              </div>


            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">History Permohonan</h5>




                </div>
            </div>
        </div>
    </div>
</section>

@endsection
