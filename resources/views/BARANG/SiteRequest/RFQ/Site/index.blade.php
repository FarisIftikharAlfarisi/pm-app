@extends('Partials.main')
@section('content')
<div class="pagetitle">
    <h1>Permintaan Barang Site</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Permintaan Barang</li>
        </ol>
    </nav>
</div>
<section class="section">
    <div class="row">
        <!-- Daftar Barang -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Katalog Barang</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari barang..." id="search-barang">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" id="kategori-filter">
                                <option value="">Semua Kategori</option>
                                @foreach($data_barang->unique('Kategori') as $kategori)
                                    <option value="{{ $kategori->Kategori }}">{{ $kategori->Kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" id="barang-container">
                        @foreach($data_barang as $barang)
                        <div class="col-md-4 mb-4 barang-item" data-kategori="{{ $barang->kategori }}">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="position-relative">
                                        {{-- <img src="{{ asset('storage/' . $barang->gambar) }}" class="img-fluid rounded mb-3" alt="{{ $barang->nama_barang }}" style="height: 120px; object-fit: contain;"> --}}
                                        <span class="badge bg-primary position-absolute top-0 start-0">{{ $barang->kategori }}</span>
                                    </div>
                                    <h6 class="card-title">{{ $barang->nama_barang }}</h6>
                                    <p class="text-muted small mb-2">Stok: </p>
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-sm btn-outline-primary add-to-cart"
                                            data-barang-id="{{ $barang->id }}"
                                            data-barang-nama="{{ $barang->nama_barang }}">
                                            <i class="bi bi-plus"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <!-- Keranjang Belanja -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Keranjang Permintaan</h5>
                    <form action="" method="POST" id="request-form">
                        @csrf
                        <input type="hidden" name="creator_id" value="{{ Auth::user()->id }}">
                       @if (Auth::user()->role !== 'ADMIN_SITE')
                        <div class="mb-3">
                            <label for="site_id" class="form-label">Site</label>

                        </div>
                       @endif
                        <div class="mb-3">
                            <label for="kode_request" class="form-label">Kode Request</label>
                            <input type="text" readonly class="form-control disabled" id="kode_request" name="kode_request" value="{{ $kode_request }}">
                        </div>
                        <div class="mb-3">
                            <label for="nama_request" class="form-label">Judul Permintaan</label>
                            <input type="text" class="form-control" id="nama_request" name="nama_request" required>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="cart-table">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th width="100px">Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-items">
                                    <!-- Item keranjang akan dimasukkan di sini via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        <div class="d-grid gap-2 mt-3">
                            <button type="button" class="btn btn-success" id="save-request-btn">
                                <i class="bi bi-save"></i> Simpan Permintaan
                            </button>
                            <button type="button" class="btn btn-outline-danger" id="clear-cart-btn">
                                <i class="bi bi-trash"></i> Kosongkan Keranjang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Modal Konfirmasi Simpan -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Permintaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Setelah disimpan, permintaan tidak dapat diubah lagi. Pastikan semua barang dan jumlah sudah benar.</p>
                <p>Yakin ingin menyimpan permintaan ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirm-save">Ya, Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const cart = {};

        const cartItemsContainer = document.getElementById("cart-items");
        const saveButton = document.getElementById("save-request-btn");
        const confirmButton = document.getElementById("confirm-save");
        const clearCartButton = document.getElementById("clear-cart-btn");

        // Tambah barang ke keranjang
        document.querySelectorAll(".add-to-cart").forEach(button => {
            button.addEventListener("click", function () {
                const barangId = this.dataset.barangId;
                const barangNama = this.dataset.barangNama;

                if (cart[barangId]) {
                    cart[barangId].jumlah += 1;
                } else {
                    cart[barangId] = {
                        nama: barangNama,
                        jumlah: 1
                    };
                }

                renderCart();
            });
        });

        // Render isi keranjang ke tabel
        function renderCart() {
            cartItemsContainer.innerHTML = "";

            Object.entries(cart).forEach(([id, item]) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>
                        ${item.nama}
                        <input type="hidden" name="barang_id[]" value="${id}">
                        <input type="hidden" name="jumlah[]" value="${item.jumlah}">
                    </td>
                   <td>
                        <div class="input-group input-group-sm">
                            <button type="button" class="btn btn-outline-secondary btn-decrease" data-id="${id}">-</button>
                            <input type="number" min="1" value="${item.jumlah}" class="form-control text-center quantity-input" data-id="${id}">
                            <button type="button" class="btn btn-outline-secondary btn-increase" data-id="${id}">+</button>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-item" data-id="${id}">
                            <i class="bi bi-x"></i>
                        </button>
                    </td>
                `;
                cartItemsContainer.appendChild(row);

                // Listener untuk tombol +
                document.querySelectorAll(".btn-increase").forEach(button => {
                    button.addEventListener("click", function () {
                        const id = this.dataset.id;
                        cart[id].jumlah += 1;
                        renderCart();
                    });
                });

                // Listener untuk tombol -
                document.querySelectorAll(".btn-decrease").forEach(button => {
                    button.addEventListener("click", function () {
                        const id = this.dataset.id;
                        if (cart[id].jumlah > 1) {
                            cart[id].jumlah -= 1;
                        } else {
                            delete cart[id];
                        }
                        renderCart();
                    });
                });
            });

            // Listener untuk update jumlah
            document.querySelectorAll(".quantity-input").forEach(input => {
                input.addEventListener("change", function () {
                    const id = this.dataset.id;
                    const newVal = parseInt(this.value);
                    if (newVal > 0) {
                        cart[id].jumlah = newVal;
                    } else {
                        delete cart[id];
                    }
                    renderCart();
                });
            });

            // Listener untuk hapus item
            document.querySelectorAll(".remove-item").forEach(btn => {
                btn.addEventListener("click", function () {
                    const id = this.dataset.id;
                    delete cart[id];
                    renderCart();
                });
            });
        }

        // Tampilkan modal konfirmasi
        saveButton.addEventListener("click", function () {
            if (Object.keys(cart).length === 0) {
                alert("Keranjang masih kosong.");
                return;
            }
            new bootstrap.Modal(document.getElementById("confirmModal")).show();
        });

        // Submit form setelah konfirmasi
        confirmButton.addEventListener("click", function () {
            document.getElementById("request-form").submit();
        });

        // Kosongkan keranjang
        clearCartButton.addEventListener("click", function () {
            if (confirm("Yakin ingin mengosongkan keranjang?")) {
                for (let id in cart) delete cart[id];
                renderCart();
            }
        });
    });
    </script>

<style>
    /* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
