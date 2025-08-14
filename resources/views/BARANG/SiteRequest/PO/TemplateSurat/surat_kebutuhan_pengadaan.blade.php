{{-- resources/views/purchase_orders/print.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pemesanan Barang - {{ $po->nomor_po }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .company-info {
            text-align: center;
            margin-bottom: 10px;
        }
        .section-title {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        .no-border {
            border: none !important;
        }
        .text-right {
            text-align: right;
        }
        .signature {
            margin-top: 50px;
            width: 100%;
        }
        .signature td {
            border: none;
            text-align: center;
            vertical-align: bottom;
            height: 100px;
        }
    </style>
</head>
<body>

    {{-- HEADER PERUSAHAAN --}}
    <div class="company-info">
        <h2>{{ $company->nama }}</h2>
        <p>{{ $company->alamat }}</p>
        <p>Tel: {{ $company->telepon }} | Email: {{ $company->email }}</p>
    </div>

    <hr>

    {{-- INFORMASI PO --}}
    <table class="no-border">
        <tr>
            <td><strong>Nomor PO:</strong> {{ $po->kode_purchase_order }}</td>
            <td class="text-right"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($po->tanggal)->format('d/m/Y') }}</td>
        </tr>
    </table>

    {{-- INFORMASI SUPPLIER --}}
    <div class="section-title">Kepada Yth:</div>
    <p>
        <strong>{{ $supplier->nama_supplier }}</strong><br>
        {{ $supplier->alamat }}<br>
        @if($supplier->kontak) {{ $supplier->kontak }}<br> @endif
        Tel: {{ $supplier->contact }} / {{ $supplier->nama_contact_person }}<br>
        Email: {{ $supplier->email }}
    </p>

    <div class="section-title">Perihal: Pemesanan Barang</div>
    <p>Dengan hormat,</p>
    <p>Kami dari <strong>{{ $company->nama }}</strong> bermaksud untuk melakukan pemesanan barang kepada perusahaan Anda. Mohon untuk memproses pesanan kami sesuai rincian berikut:</p>

    {{-- TABEL BARANG --}}
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Barang</th>
                <th>Kode Barang</th>
                <th>Kuantitas</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($po->details as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->barang->kode_barang }}</td>
                    <td>{{ $item->barang->nama_barang }}</td>
                    <td>{{ number_format($item->jumlah, 2, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->jumlah * $item->harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- RINCIAN TOTAL --}}
    <table class="no-border" style="margin-top:10px;">
        <tr>
    <td class="text-right"><strong>Subtotal:</strong></td>
    <td class="text-right">Rp {{ number_format($totals->subtotal, 0, ',', '.') }}</td>
</tr>
<tr>
    <td class="text-right"><strong>Pajak:</strong></td>
    <td class="text-right">Rp {{ number_format($totals->pajak, 0, ',', '.') }}</td>
</tr>
<tr>
    <td class="text-right"><strong>Biaya Pengiriman:</strong></td>
    <td class="text-right">Rp {{ number_format($totals->biaya_pengiriman, 0, ',', '.') }}</td>
</tr>
<tr>
    <td class="text-right"><strong>Total:</strong></td>
    <td class="text-right"><strong>Rp {{ number_format($totals->total, 0, ',', '.') }}</strong></td>
</tr>
    </table>

    {{-- KETENTUAN --}}
    <div class="section-title">Ketentuan Pemesanan:</div>
    <p>
        <strong>Tanggal Pengiriman:</strong> {{ \Carbon\Carbon::parse($po->tanggal_pengiriman)->format('d/m/Y') }}<br>
        <strong>Alamat Pengiriman:</strong> {{ $po->site->alamat }}, {{ $po->site->desa_kelurahan }}, {{ $po->site->kecamatan }}, {{ $po->site->kabupaten_kota}}, {{ $po->site->provinsi }}<br>
        <strong>Syarat Pembayaran:</strong> {{ $po->syarat_pembayaran }}<br>
        <strong>Metode Pembayaran:</strong> {{ $po->metode_pembayaran }}<br>
        <strong>Informasi Tambahan:</strong> {{ $po->informasi_tambahan }}
    </p>

    {{-- TANDA TANGAN --}}
    <table class="signature">
        <tr>
            <td>Hormat kami,</td>
            <td>Penerima Pesanan</td>
        </tr>
        <tr>
            <td>{{ $company->penanggung_jawab }}</td>
            <td>{{ $supplier->nama }}</td>
        </tr>
    </table>

</body>
</html>
