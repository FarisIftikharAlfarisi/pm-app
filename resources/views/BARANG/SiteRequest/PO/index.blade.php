@extends('Partials.main')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Daftar Purchase Order (PO)</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>No. PO</th>
                        <th>Nama Supplier</th>
                        <th style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseOrders as $po)
                        @php $modalId = 'itemModal'.$po->id; @endphp
                        <tr>
                            <td>{{ $po->kode_purchase_order ?? $po->po_number }}</td>
                            <td>{{ optional($po->supplier)->nama_supplier ?? optional($po->supplier)->name ?? '-' }}</td>
                            <td>
                                <button type="button"
                                        class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#{{ $modalId }}">
                                    Lihat Barang
                                </button>

                                {{-- download surat PO --}}
                                 <a href="{{ route('purchase_order.download_pdf', $po->id) }}"
                                    class="btn btn-danger btn-sm"
                                    target="_blank">
                                    Download PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Kumpulan modal (diletakkan di luar tabel agar DOM valid) --}}
@foreach($purchaseOrders as $po)
    @php $modalId = 'itemModal'.$po->id; @endphp
    <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $modalId }}Label">
                        Daftar Barang — PO {{ $po->kode_purchase_order ?? $po->po_number }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Quantity</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($po->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->barang->nama_barang ?? $detail->barang->nama ?? '-' }}</td>
                                    <td>{{ $detail->jumlah }}</td>
                                    <td>{{ $detail->satuan }}</td>
                                    <td>Rp {{ number_format($detail->harga ?? 0, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format(($detail->jumlah ?? 0) * ($detail->harga ?? 0), 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada item pada PO ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
