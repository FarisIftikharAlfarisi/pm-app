@extends('Partials.main')

@section('title', 'Daftar Supplier')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Daftar Supplier</h4>
    </div>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('supplier-barang.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Supplier
        </a>

        <div class="col-md-4">
            <div class="input-group">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari supplier...">
                <button class="btn btn-outline-secondary" type="button" id="searchButton">
                    <i class="bi bi-search"></i>
                </button>
                <button class="btn btn-outline-secondary" type="button" id="resetSearch" title="Reset pencarian">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle" id="suppliersTable">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode Supplier</th>
                    <th>Nama Supplier</th>
                    <th>Alamat</th>
                    <th>Email</th>
                    <th>Kontak</th>
                    <th>Contact Person</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($supplierBarangs as $index => $supplier)
                <tr class="supplier-row">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $supplier->kode_supplier }}</td>
                    <td>{{ $supplier->nama_supplier }}</td>
                    <td>{{ $supplier->alamat ?? '-' }}</td>
                    <td>{{ $supplier->email ?? '-' }}</td>
                    <td>{{ $supplier->contact ?? '-' }}</td>
                    <td>{{ $supplier->nama_contact_person ?? '-' }}</td>
                    <td>
                        {{-- Action buttons --}}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada data supplier.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        const resetSearch = document.getElementById('resetSearch');
        const supplierRows = document.querySelectorAll('.supplier-row');

        // Fungsi untuk melakukan pencarian
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase();

            supplierRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchTerm) ? '' : 'none';
            });
        }

        // Event listener untuk tombol search
        searchButton.addEventListener('click', performSearch);

        // Event listener untuk tombol reset
        resetSearch.addEventListener('click', function() {
            searchInput.value = '';
            supplierRows.forEach(row => {
                row.style.display = '';
            });
        });

        // Live search saat mengetik (opsional)
        searchInput.addEventListener('input', function() {
            // Debounce untuk menghindari terlalu banyak event
            clearTimeout(this.timer);
            this.timer = setTimeout(performSearch, 300);
        });

        // Submit search saat tekan Enter
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    });
</script>

<style>
    /* Tambahan styling untuk search bar */
    #searchInput {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    #searchButton {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    #resetSearch {
        border-radius: 0;
    }

    .input-group {
        max-width: 400px;
    }
</style>
@endsection
