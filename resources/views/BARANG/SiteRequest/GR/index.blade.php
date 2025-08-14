@extends('Partials.main')

@section('content')
<div class="container">
    <h3>Goods Receipt per Supplier</h3>

    @foreach($purchaseOrders->groupBy('supplier_id') as $supplierId => $orders)
        <div class="card mb-4">
            <div class="card-header">
                <h5>{{ $orders->first()->supplier->nama_supplier }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Kode PO</th>
                            <th>Tanggal</th>
                            <th>Total Item</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $po)
                            <tr>
                                <td>{{ $po->kode_purchase_order }}</td>
                                <td>{{ \Carbon\Carbon::parse($po->tanggal_purchase_order)->translatedFormat('d F Y') }}</td>
                                <td>{{ $po->details->count() }}</td>
                                <td>
                                    @if($po->status == 'completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalGR{{ $po->id }}">
                                        Terima Barang
                                    </button>
                                </td>
                            </tr>

                            {{-- Modal Goods Receipt --}}
                            <div class="modal fade" id="modalGR{{ $po->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form action="{{ route('good-receipt.store') }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Terima Barang - {{ $po->kode_po }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="purchase_order_id" value="{{ $po->id }}">

                                                <table class="table table-sm table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Nama Barang</th>
                                                            <th>Qty PO</th>
                                                            <th>Satuan</th>
                                                            <th>Qty Sudah Diterima</th>
                                                            <th>Qty Diterima Sekarang</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($po->details as $detail)
                                                            @php
                                                                $qtyReceived = $po->goodsReceipts
                                                                    ->flatMap->details
                                                                    ->where('barang_id', $detail->barang_id)
                                                                    ->sum('qty');
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $detail->barang->nama_barang }}</td>
                                                                <td>{{ $detail->jumlah }}</td>
                                                                <td>{{ $detail->satuan }}</td>
                                                                <td>{{ $qtyReceived }}</td>
                                                                <td>
                                                                    <input type="number" name="items[{{ $detail->id }}][qty_received]" class="form-control form-control-sm" min="0" max="{{ $detail->qty - $qtyReceived }}">
                                                                </td>
                                                                <td>
                                                                    <select name="items[{{ $detail->id }}][status]" class="form-select form-select-sm">
                                                                        <option value="accepted_all">Diterima Semua</option>
                                                                        <option value="accepted_partial">Parsial</option>
                                                                        <option value="rejected">Ditolak</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{-- End Modal --}}

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
@endsection
