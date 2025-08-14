{{-- Halaman ini digunakan untuk mencari Vendor Barang berdasarkan Perhitungan WP --}}
{{-- Controller : PurchaseRequisitionController --}}
{{-- Data yang harus dikirim : Request dari Barang yang sudah Di approve --}}

@extends('Partials.main')

@section('content')
<div class="container">
  <form action="{{ route('purchase-order.automate', ['kode_proyek' => $project->kode_project]) }}" method="POST">
    @csrf
    <input type="hidden" name="project_code" value="{{ $project->kode_project }}">

    <input type="hidden" name="purchase_requisition_id" value="{{ $pr_id}}">
    <input type="hidden" name="project_id" value="{{ $project->id }}">
    <input type="hidden" name="site_id" value="{{ $siteId }}">
    <input type="hidden" name="site_request_id" value="{{ $siteRequest->id }}">


    @foreach($results as $barangId => $data)
    <div class="card mb-4">
      <div class="card-header bg-gray-100 p-4 rounded-t-lg shadow">
        <h3 class="text-lg font-bold">{{ $data['nama_barang'] }}</h3>
        <div class="text-gray-600">
          Kebutuhan: {{ $data['kebutuhan']['jumlah'] }} {{ $data['kebutuhan']['unit_name'] }}
        </div>
      </div>

      <div class="card-body p-0">
        <div class="overflow-x-auto">
          <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-200">
              <tr>
                <th class="px-4 py-2 text-left">Vendor</th>
                <th class="px-4 py-2">Harga/{{ $data['suppliers'][$data['best_supplier']['supplier_id']]['konversi']['to_unit'] }}</th>
                <th class="px-4 py-2">Harga Konversi</th>
                <th class="px-4 py-2">Waktu</th>
                <th class="px-4 py-2">Min Order</th>
                <th class="px-4 py-2">Jarak</th>
                <th class="px-4 py-2">Diskon</th>
                <th class="px-4 py-2">Jumlah Dibeli</th>
                <th class="px-4 py-2">Total</th>
                <th class="px-4 py-2">Skor WP</th>
                <th class="px-4 py-2">Pilih</th>
              </tr>
            </thead>
            <tbody>
              @foreach($data['suppliers'] as $supplierId => $supplier)
                <input type="hidden" name="items[{{ $barangId }}][jumlah]" value="{{ $supplier['jumlah_dibeli'] }}">
                <input type="hidden" name="items[{{ $barangId }}][satuan_id]" value="{{ $supplier['konversi']['to_unit'] }}">
                <input type="hidden" name="items[{{ $barangId }}][harga]" value="{{ $supplier['harga_satuan'] }}">

              <tr class="border-t hover:bg-gray-50 {{ $supplierId == $data['best_supplier']['supplier_id'] ? 'bg-blue-50' : '' }}">
                <td class="px-4 py-2 font-medium">
                  {{ $supplier['nama_supplier'] }}
                  @if($supplierId == $data['best_supplier']['supplier_id'])
                    <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Rekomendasi</span>
                  @endif
                </td>
                <td class="px-4 py-2 text-right">Rp {{ number_format($supplier['harga_satuan'], 0, ',', '.') }}</td>
                <td class="px-4 py-2 text-right">Rp {{ number_format($supplier['harga_konversi'], 0, ',', '.') }}</td>
                <td class="px-4 py-2 text-center">{{ $supplier['waktu_pengiriman'] }} hari</td>
                <td class="px-4 py-2 text-right">{{ number_format($supplier['min_order_asli'], 0,',') }} {{ $supplier['konversi']['to_unit'] }}</td>
                <td class="px-4 py-2 text-center">{{ $supplier['jarak'] }} km</td>
                <td class="px-4 py-2 text-center">{{ $supplier['diskon'] }}%</td>
                <td class="px-4 py-2 text-right">{{ number_format($supplier['jumlah_dibeli'], 0,',') }} {{ $supplier['konversi']['to_unit'] }}</td>
                <td class="px-4 py-2 text-right font-semibold text-green-700">
                  Rp {{ number_format($supplier['total_belanja'], 0, ',', '.') }}
                </td>
              <td class="px-4 py-2 text-right">{{ number_format($supplier['wp_score'], 4) }}</td>
                <td class="px-4 py-2 text-center">
                  <input type="radio"
                         name="selected_vendor[{{ $barangId }}]"
                         value="{{ $supplierId }}"
                         {{ $supplierId == $data['best_supplier']['supplier_id'] ? 'checked' : '' }}>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer bg-gray-50 p-3 text-sm">
        <div class="font-semibold">Konversi Satuan:</div>
        <div>1 {{ $data['kebutuhan']['unit_name'] }} =
          {{ $data['suppliers'][$data['best_supplier']['supplier_id']]['konversi']['ratio'] }}
          {{ $data['suppliers'][$data['best_supplier']['supplier_id']]['konversi']['to_unit'] }}
        </div>
      </div>
    </div>
    @endforeach

    <div class="fixed bottom-0 left-0 right-0 bg-white border-t p-4 shadow-lg">
      <div class="container mx-auto flex justify-between items-center">
        <div>
          <span class="font-semibold">Total Biaya Pengadaan Bahan Baku:</span>
          <span class="ml-2 text-xl fw-bold text-green-700">
            Rp {{ number_format(array_sum(array_map(function($item) {
              return $item['best_supplier']['total_belanja'];
            }, $results)), 0, ',', '.') }}
          </span>
        </div>
        <button type="submit" class="btn btn-primary">
            Buat Purchase Order
        </button>
      </div>
    </div>
  </form>
</div>

<style>
  .card {
    margin-bottom: 1.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    overflow: hidden;
  }
  .card-header {
    border-bottom: 1px solid #e2e8f0;
  }
  table {
    border-collapse: collapse;
    width: 100%;
  }
  th, td {
    border: 1px solid #e2e8f0;
  }
  tr:nth-child(even) {
    background-color: #f8fafc;
  }
</style>
@endsection
