{{-- komponen list finance --}}
{{-- 1. Approval Barang Per Proyek dari setiap WBS --}}
{{-- 2. Purchase Requuisition (pemilihan Vendor) --}}
{{-- 3. Purchase Order --}}
{{-- 4. Invoice --}}

{{-- Controller :PROYEK CONTROLLER : SUB CONTROLLER --}}

@extends('Partials.main')

@section('content')


@section('title', 'Finance Workflow')
<div class="container">
    @if(session('message'))
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="messageModalLabel">Pemberitahuan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        {{ session('message') }}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
    messageModal.show();
});
</script>
@endif
    <h2 class="mb-4">Finance Workflow</h2>
    <div class="row">

        {{-- 1. Approval Barang Per Proyek --}}
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">1. Approval Barang Per Proyek (WBS)</h5>
                    <p class="card-text">Lakukan approval barang per proyek berdasarkan WBS.</p>
                    <a href="{{ route('site-request.INDEX_WBS', [$project->kode_project]) }}" class="btn btn-primary">Go to Approval</a>
                </div>
            </div>
        </div>

        {{-- 2. Pemilihan Vendor --}}
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">2. Pemilihan Vendor</h5>
                    <p class="card-text">Hitung dan pilih vendor berdasarkan WP hasil approval.</p>
                    <a href="{{ route('pr.pilih-vendor', [$project->kode_project]) }}" class="btn btn-primary">Go to Vendor Selection</a>
                </div>
            </div>
        </div>

        {{-- 3. Purchase Order --}}
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">3. Purchase Order</h5>
                    <p class="card-text">Daftar surat Purchase Order (PO) per vendor.</p>
                    <a href="{{ route('purchase-order.index_by_po',[$project->kode_project]) }}" class="btn btn-primary">Go to Purchase Order</a>
                </div>
            </div>
        </div>

        {{-- 4. Invoice --}}
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">4. Invoice</h5>
                    <p class="card-text">Input surat invoice berdasarkan PO yang dibuat.</p>
                    <a href="" class="btn btn-primary">Go to Invoice</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
