@extends('Partials.main')
@section('content')
<div class="container">
    <h4>{{ $title }}</h4>
    <form action="{{ route('site-request.approveItems') }}" id="approval-form" method="POST">
        @csrf
        <input type="hidden" name="project_id" value="{{ $projectId }}">

        <div class="table-responsive">
           <table class="table table-hover table-bordered align-middle text-center">
    <thead class="table-light">
        <tr>
            <th scope="col">No</th>
            <th scope="col" class="text-start">Nama Barang</th>
            <th scope="col">Kuantiti</th>
            <th scope="col">Satuan</th>
            <th scope="col" class="text-center align-middle">
                <div class="d-flex flex-row align-items-center justify-content-center gap-2">
                    <label for="checkAll" class="fw-semibold m-0">Approve</label>
                    <input class="form-check-input" type="checkbox" id="checkAll" style="transform: scale(1.2); cursor: pointer;" title="Centang semua">
                </div>
                <div class="text-muted" style="font-size: 0.75rem;">Pilih Semua</div>
            </th>
        </tr>
    </thead>
    <tbody>
    @forelse ($requirements as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td class="text-start">
                <div class="fw-bold">{{ $item['nama_barang'] }}</div>
                <small class="text-muted">{{ $item['kode_barang'] }}</small>
                <input type="hidden" name="approvals[{{ $index }}][barang_id]" value="{{ $item['barang_id'] }}">
                <input type="hidden" name="approvals[{{ $index }}][satuan]" value="{{ $item['satuan_id'] }}">
            </td>
            <td>
                <input type="number" name="approvals[{{ $index }}][quantity]" value="{{ $item['total_quantity'] }}"
                    class="form-control text-center" min="0" step="any" required>
            </td>
            <td>{{ $item['satuan'] }}</td>
            <td>
                <!-- Hidden input to send 'false' if checkbox is not checked -->
                <input type="hidden" name="approvals[{{ $index }}][is_approved]" value="0">

                <!-- Checkbox input to override value if checked -->
                <div class="form-check d-flex justify-content-center">
                    <input class="form-check-input approval-checkbox" type="checkbox"
                        name="approvals[{{ $index }}][is_approved]" value="1"
                        {{ !empty($item['is_approved']) ? 'checked' : '' }}
                        id="approval-{{ $index }}"
                        style="transform: scale(1.25);">
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <textarea name="approvals[{{ $index }}][notes]" class="form-control" rows="1" placeholder="Catatan (opsional)"></textarea>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center text-muted">Tidak ada kebutuhan barang ditemukan.</td>
        </tr>
    @endforelse
</tbody>

</table>
        </div>

        <div class="mt-4 text-end">
            <button type="button" class="btn btn-success" id="submit-approval">
                Selesai
            </button>
        </div>
    </form>
</div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('submit-approval').addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin ingin menyelesaikan proses ini?',
                text: "Barang yang di-approve akan dikirim untuk perhitungan PO.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Selesaikan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('approval-form').submit();
                }
            });
        });

        document.getElementById('checkAll').addEventListener('change', function () {
            const isChecked = this.checked;
            const checkboxes = document.querySelectorAll('input[name^="approvals["][name$="[is_approved]"]');
            checkboxes.forEach(cb => cb.checked = isChecked);
        });
    </script>
@endsection
