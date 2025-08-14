@extends('Partials.main')

@section('content')
<div class="pagetitle">
    <h1>Data Site</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Site</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Site</h5>
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createSiteModal">
                        <i class="bi bi-plus-circle"></i> Tambah Site
                    </button>

                    <!-- Tabel Site -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Kode Site</th>
                                <th>Nama Site</th>
                                <th>Alamat</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sites as $site)
                            <tr>
                                <td>{{ $site->Kode_Site }}</td>
                                <td>{{ $site->nama_site }}</td>
                                <td>{{ $site->alamat }}</td>
                                <td>{{ $site->latitude }}</td>
                                <td>{{ $site->longitude }}</td>
                                <td>

                                    {{-- button modal details dari site --}}
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailSiteModal{{ $site->id }}">
                                        <i class="bi bi-eye"></i> Detail
                                    </button>
                                    {{-- button modal edit --}}
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSiteModal{{ $site->id }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    {{-- button delete --}}
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $site->id }})">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Edit Site -->
                            <div class="modal fade" id="editSiteModal{{ $site->id }}" tabindex="-1" aria-labelledby="editSiteModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editSiteModalLabel">Edit Site</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form id="editSiteForm{{ $site->id }}" onsubmit="updateSite(event, {{ $site->id }})" action="{{ route('site.update', $site->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label  class="form-label">Kode Site</label>
                                                            <input type="text" class="form-control" id="Kode_Site" name="Kode_Site" value="{{ $site->Kode_Site }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="site" class="form-label">Nama Site</label>
                                                            <input type="text" class="form-control" id="site" name="site" value="{{ $site->nama_site }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="site" class="form-label">Jenis Site</label>
                                                            <input type="text" class="form-control" id="site" name="jenis_site" value="{{ $site->jenis_site }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label  class="form-label">Alamat</label>
                                                            <input type="text" class="form-control" id="alamat" name="alamat" value="{{ $site->alamat }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label  class="form-label">Desa/Kelurahan</label>
                                                            <input type="text" class="form-control" id="desa_kelurahan" name="desa_kelurahan" value="{{ $site->desa_kelurahan }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label  class="form-label">Kecamatan</label>
                                                            <input type="text" class="form-control" id="kecamatan" name="kecamatan" value="{{ $site->kecamatan }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label  class="form-label">Kabupaten/Kota</label>
                                                            <input type="text" class="form-control" id="kabupaten_kota" name="kabupaten_kota" value="{{ $site->kabupaten_kota }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label  class="form-label">Provinsi</label>
                                                            <input type="text" class="form-control" id="provinsi" name="provinsi" value="{{ $site->provinsi }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label  class="form-label">Latitude</label>
                                                            <input type="number" step="any" class="form-control" id="latitude" name="latitude" value="{{ $site->latitude }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label  class="form-label">Longitude</label>
                                                            <input type="number" step="any" class="form-control" id="longitude" name="longitude" value="{{ $site->longitude }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div id="map{{ $site->id }}" style="height: 400px; width: 100%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal Detail Site -->
                            <div class="modal fade" id="detailSiteModal{{ $site->id }}" tabindex="-1" aria-labelledby="detailSiteModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detailSiteModalLabel">Detail Site</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label  class="form-label">Kode Site</label>
                                                            <input type="text" class="form-control" id="Kode_Site" name="Kode_Site" value="{{ $site->Kode_Site }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="site" class="form-label">Nama Site</label>
                                                            <input type="text" class="form-control" id="site" name="site" value="{{ $site->nama_site }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="site" class="form-label">Jenis Site</label>
                                                            <input type="text" class="form-control" id="site" name="jenis_site" value="{{ $site->jenis_site }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label  class="form-label">Alamat</label>
                                                            <input type="text" class="form-control" id="alamat" name="alamat" value="{{ $site->alamat }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label  class="form-label">Desa/Kelurahan</label>
                                                            <input type="text" class="form-control" id="desa_kelurahan" name="desa_kelurahan" value="{{ $site->desa_kelurahan }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label  class="form-label">Kecamatan</label>
                                                            <input type="text" class="form-control" id="kecamatan" name="kecamatan" value="{{ $site->kecamatan }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label  class="form-label">Kabupaten/Kota</label>
                                                            <input type="text" class="form-control" id="kabupaten_kota" name="kabupaten_kota" value="{{ $site->kabupaten_kota }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label  class="form-label">Provinsi</label>
                                                            <input type="text" class="form-control" id="provinsi" name="provinsi" value="{{ $site->provinsi }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label  class="form-label">Latitude</label>
                                                            <input type="number" step="any" class="form-control" id="latitude" name="latitude" value="{{ $site->latitude }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label  class="form-label">Longitude</label>
                                                            <input type="number" step="any" class="form-control" id="longitude" name="longitude" value="{{ $site->longitude }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div id="map{{ $site->id }}" style="height: 400px; width: 100%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah Site -->
<div class="modal fade" id="createSiteModal" tabindex="-1" aria-labelledby="createSiteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSiteModalLabel">Tambah Site</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createSiteForm" action="{{ route('site.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Bagian Atas: Form Input -->
                    <div class="row">
                        <!-- Kolom Pertama -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label  class="form-label">Kode Site</label>
                                <input type="text" class="form-control" id="Kode_Site" name="Kode_Site" value="{{ $kode_site }}" required>
                            </div>
                            <div class="mb-3">
                                <label  class="form-label">Nama Site</label>
                                <input type="text" class="form-control" id="site" name="nama_site" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis Site</label>
                                <select name="jenis_site" id="jenis_site" class="form-control">
                                    <option value="undefined">Pilih Jenis Lokasi</option>
                                    <option value="WAREHOUSE">Warehouse</option>
                                    <option value="WORKSHOP">Workshop</option>
                                    <option value="LOKASI_PEMBANGUNAN">Lokasi Pembangunan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label  class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat_site" required>
                            </div>
                        </div>
                        <!-- Kolom Kedua -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label  class="form-label">Desa/Kelurahan</label>
                                <input type="text" class="form-control" id="desa_kelurahan" name="desa_kelurahan" required>
                            </div>
                            <div class="mb-3">
                                <label  class="form-label">Kecamatan</label>
                                <input type="text" class="form-control" id="kecamatan" name="kecamatan" required>
                            </div>
                            <div class="mb-3">
                                <label  class="form-label">Kabupaten/Kota</label>
                                <input type="text" class="form-control" id="kabupaten_kota" name="kabupaten_kota" required>
                            </div>
                            <div class="mb-3">
                                <label  class="form-label">Provinsi</label>
                                <input type="text" class="form-control" id="provinsi" name="provinsi" required>
                            </div>
                        </div>
                    </div>

                    <!-- Garis Pemisah -->
                    <hr>

                    <!-- Latitude dan Longitude -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label  class="form-label">Latitude</label>
                                <input type="text" step="any" class="form-control" id="latitude" name="latitude" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label  class="form-label">Longitude</label>
                                <input type="text" step="any" class="form-control" id="longitude" name="longitude" required>
                            </div>
                        </div>
                    </div>

                    <!-- Garis Pemisah -->
                    <hr>

                    <!-- Bagian Bawah: Peta -->
                    <div class="row">
                        <div class="col-md-12">
                            <div id="map" style="height: 400px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert dan JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- <script>
    // Fungsi untuk menambahkan site
    document.getElementById('createSiteForm').addEventListener('submit', async function(event) {
        event.preventDefault();
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;

        try {
            const formData = new FormData(this);

            // Debug: Lihat data yang akan dikirim
            console.log('FormData:', Object.fromEntries(formData.entries()));

            const response = await fetch("{{ route('site.store') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                await Swal.fire({
                    title: 'Sukses!',
                    text: data.success || 'Data berhasil disimpan',
                    icon: 'success'
                });
                location.reload();
            } else {
                // Tampilkan error validasi jika ada
                const errorMsg = data.message || 'Gagal menyimpan data';
                if (data.errors) {
                    errorMsg = Object.values(data.errors).join('<br>');
                }
                throw new Error(errorMsg);
            }
        } catch (error) {
            await Swal.fire({
                title: 'Error!',
                html: error.message,
                icon: 'error'
            });
        } finally {
            submitBtn.disabled = false;
        }
    });

    // Fungsi untuk mengupdate site
    window.updateSite = async function(event, id) {
        event.preventDefault();
        const form = document.getElementById(`editSiteForm${id}`);
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;

        try {
            const formData = new FormData(form);

            // Debug: Lihat data yang akan dikirim
            console.log('FormData:', Object.fromEntries(formData.entries()));

            const response = await fetch(`/site/${id}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PUT',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                await Swal.fire({
                    title: 'Sukses!',
                    text: data.success || 'Data berhasil diupdate',
                    icon: 'success'
                });
                location.reload();
            } else {
                // Tampilkan error validasi jika ada
                const errorMsg = data.message || 'Gagal mengupdate data';
                if (data.errors) {
                    errorMsg = Object.values(data.errors).join('<br>');
                }
                throw new Error(errorMsg);
            }
        } catch (error) {
            await Swal.fire({
                title: 'Error!',
                html: error.message,
                icon: 'error'
            });
        } finally {
            submitBtn.disabled = false;
        }
    }

    // Fungsi untuk menghapus site
    window.confirmDelete = async function(id) {
        const { isConfirmed } = await Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        });

        if (!isConfirmed) return;

        try {
            const response = await fetch(`/site/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'DELETE'
                }
            });

            const data = await response.json();

            if (response.ok) {
                await Swal.fire({
                    title: 'Sukses!',
                    text: data.success || 'Data berhasil dihapus',
                    icon: 'success'
                });
                location.reload();
            } else {
                throw new Error(data.message || 'Gagal menghapus data');
            }
        } catch (error) {
            await Swal.fire({
                title: 'Error!',
                text: error.message,
                icon: 'error'
            });
        }
    }
</script> --}}

<!-- OPEN STREET MAP -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<script>
    // Inisialisasi peta untuk modal tambah site
    document.getElementById('createSiteModal').addEventListener('shown.bs.modal', function () {

        // Definisikan semua elemen form sebagai variabel
        const modal = this;
        const form = modal.querySelector('#createSiteForm');
        const latInput = form.querySelector('#latitude');
        const lngInput = form.querySelector('#longitude');
        const alamatInput = form.querySelector('#alamat');
        const desaInput = form.querySelector('#desa_kelurahan');
        const kecamatanInput = form.querySelector('#kecamatan');
        const kabupatenInput = form.querySelector('#kabupaten_kota');
        const provinsiInput = form.querySelector('#provinsi');

        console.log('Elemen form:', {
            latInput,
            lngInput,
            alamatInput,
            desaInput,
            kecamatanInput,
            kabupatenInput,
            provinsiInput
        });

        var map = L.map('map').setView([-6.9147, 107.6098], 10); // Set view ke Jawa Barat
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // Tambahkan tile layer dari Esri World Imagery (mode satelit)
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'SatMode | Tiles © Esri — Source: Esri, Maxar, Earthstar Geographics, and the GIS User Community'
        }).addTo(map);

        // Tambahkan layer jalan dari OpenStreetMap (transparan 70%)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            opacity: 0.5  // Atur transparansi agar satelit tetap terlihat
        }).addTo(map);

        var marker;
        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(e.latlng).addTo(map);

            console.log(e.latlng); // Debugging: Lihat koordinat yang diklik

            // Isi latitude dan longitude
            latInput.value = e.latlng.lat;
            lngInput.value = e.latlng.lng;
            // Debug: Cek nilai setelah di-set
            console.log('Nilai setelah di-set:', {
                latitude: latInput.value,
                longitude: lngInput.value
            });

            // Reverse geocoding
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}&zoom=18&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    console.log('Response Nominatim:', data);
                    const address = data.address || {};

                    // Isi form dengan fallback
                    alamatInput.value = [
                        address.road,
                        address.neighbourhood,
                        address.village
                    ].filter(Boolean).join(', ') || 'Alamat tidak ditemukan';

                    desaInput.value = address.village || address.town || '';
                    kecamatanInput.value = address.suburb || address.city_district || address.town || '';
                    kabupatenInput.value = address.city || address.county || '';
                    provinsiInput.value = address.state || '';

                    // Trigger event perubahan
                    const event = new Event('input', { bubbles: true });
                    [latInput, lngInput, alamatInput, desaInput, kecamatanInput, kabupatenInput, provinsiInput].forEach(input => {
                        input.dispatchEvent(event);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Gagal mengambil data alamat', 'error');
                });
            });
    });

    // Inisialisasi peta untuk modal edit site
    @foreach($sites as $site)
    document.getElementById('editSiteModal{{ $site->id }}').addEventListener('shown.bs.modal', function () {
        var map = L.map('map{{ $site->id }}').setView([{{ $site->latitude }}, {{ $site->longitude }}], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // Tambahkan tile layer dari Esri World Imagery (mode satelit)
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'SatMode | Tiles © Esri — Source: Esri, Maxar, Earthstar Geographics, and the GIS User Community'
        }).addTo(map);

        // Tambahkan layer jalan dari OpenStreetMap (transparan 70%)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            opacity: 0.5  // Atur transparansi agar satelit tetap terlihat
        }).addTo(map);

        var marker = L.marker([{{ $site->latitude }}, {{ $site->longitude }}]).addTo(map);
        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(e.latlng).addTo(map);

            console.log(e.latlng); // Debugging: Lihat koordinat yang diklik
            // Isi latitude dan longitude
            latInput.value = e.latlng.lat;
            lngInput.value = e.latlng.lng;


            // Reverse geocoding
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}&zoom=18&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    console.log('Response Nominatim:', data);
                    const address = data.address || {};

                    // Isi form dengan fallback
                    alamatInput.value = [
                        address.road,
                        address.neighbourhood,
                        address.village
                    ].filter(Boolean).join(', ') || 'Alamat tidak ditemukan';

                    desaInput.value = address.village || address.town || '';
                    kecamatanInput.value = address.suburb || address.city_district || address.town || '';
                    kabupatenInput.value = address.city || address.county || '';
                    provinsiInput.value = address.state || '';

                    // Trigger event perubahan
                    const event = new Event('input', { bubbles: true });
                    [latInput, lngInput, alamatInput, desaInput, kecamatanInput, kabupatenInput, provinsiInput].forEach(input => {
                        input.dispatchEvent(event);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Gagal mengambil data alamat', 'error');
                });
            });
    });
    @endforeach

    // Inisialisasi peta untuk modal Detail site
    @foreach($sites as $sitedetail)
    document.getElementById('detailSiteModal{{ $site->id }}').addEventListener('shown.bs.modal', function () {
        var map = L.map('map{{ $sitedetail->id }}').setView([{{ $sitedetail->latitude }}, {{ $sitedetail->longitude }}], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // Tambahkan tile layer dari Esri World Imagery (mode satelit)
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'SatMode | Tiles © Esri — Source: Esri, Maxar, Earthstar Geographics, and the GIS User Community'
        }).addTo(map);

        // Tambahkan layer jalan dari OpenStreetMap (transparan 70%)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            opacity: 0.5  // Atur transparansi agar satelit tetap terlihat
        }).addTo(map);

        var marker = L.marker([{{ $sitedetail->latitude }}, {{ $sitedetail->longitude }}]).addTo(map);
        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(e.latlng).addTo(map);

            // console.log(e.latlng); // Debugging: Lihat koordinat yang diklik
            // // Isi latitude dan longitude
            // latInput.value = e.latlng.lat;
            // lngInput.value = e.latlng.lng;


            // Reverse geocoding
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}&zoom=18&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    console.log('Response Nominatim:', data);
                    const address = data.address || {};

                    // Isi form dengan fallback
                    alamatInput.value = [
                        address.road,
                        address.neighbourhood,
                        address.village
                    ].filter(Boolean).join(', ') || 'Alamat tidak ditemukan';

                    desaInput.value = address.village || address.town || '';
                    kecamatanInput.value = address.suburb || address.city_district || address.town || '';
                    kabupatenInput.value = address.city || address.county || '';
                    provinsiInput.value = address.state || '';

                    // Trigger event perubahan
                    const event = new Event('input', { bubbles: true });
                    [latInput, lngInput, alamatInput, desaInput, kecamatanInput, kabupatenInput, provinsiInput].forEach(input => {
                        input.dispatchEvent(event);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Gagal mengambil data alamat', 'error');
                });
            });
    });
    @endforeach
</script>

@endsection
