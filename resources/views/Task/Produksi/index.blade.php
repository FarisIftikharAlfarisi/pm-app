@extends('Partials.main')

@section('content')
<div class="container mt-4">
    <h4>Gantt Chart Produksi</h4>
    <div id="gantt"></div>

    <hr>
    <h5>Daftar Barang yang Harus Diproduksi</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Produksi</th>
                <th>Barang ID</th>
                <th>Nama Produksi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produksis as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->barang_id }}</td>
                <td>{{ $p->nama_produksi }}</td>
                <td>{{ $p->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Library Gantt --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/frappe-gantt/0.6.1/frappe-gantt.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/frappe-gantt/0.6.1/frappe-gantt.css"/>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const tasks = [
        @foreach($produksis as $p)
        {
            id: "{{ $p->id }}",
            name: "{{ $p->nama_produksi }}",
            start: "{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('Y-m-d') }}",
            end: "{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('Y-m-d') }}",
            progress: 0,
            dependencies: ""
        },
        @endforeach
    ];

    const gantt = new Gantt("#gantt", tasks, {
        view_mode: 'Day',
        date_format: 'YYYY-MM-DD',
        custom_popup_html: function(task) {
            return `
                <div class="p-2">
                    <h5>${task.name}</h5>
                    <p><b>Start:</b> ${task.start}</p>
                    <p><b>End:</b> ${task.end}</p>
                    <p><b>ID:</b> ${task.id}</p>
                </div>
            `;
        }
    });
});
</script>
@endsection
