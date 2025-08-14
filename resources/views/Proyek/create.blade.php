@extends('Partials.main')

@section('title', 'Buat Proyek Baru')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Buat Proyek Baru</h3>
            </div>
            <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Bagian 1: Informasi Proyek -->
                <div class="card-body">
                    <h4 class="mb-4">Informasi Proyek</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_project">Kode Proyek</label>
                                <input type="text" class="form-control" id="kode_project" name="kode_project" required value="{{ $kodeProyek }}s">
                            </div>

                            <div class="form-group">
                                <label for="nama_project">Nama Proyek</label>
                                <input type="text" class="form-control" id="nama_project" name="nama_project" required>
                            </div>

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi Proyek</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="anggaran">Anggaran (Rp)</label>
                                <input type="number" class="form-control" id="anggaran" name="anggaran" step="0.01">
                            </div>
                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="jenis_proyek">Jenis Proyek</label>
                                <select class="form-control" id="jenis_proyek" name="jenis_proyek" required>
                                    <option value="konstruksi">Konstruksi</option>
                                    <option value="renovasi">Renovasi</option>
                                    <option value="pengadaan">Pengadaan</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div class="form-group" id="jenis-lainnya-group" style="display: none;">
                                <label for="jenis_proyek_lainnya">Jenis Proyek Lainnya</label>
                                <input type="text" class="form-control" id="jenis_proyek_lainnya" name="jenis_proyek_lainnya">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_mulai">Tanggal Mulai</label>
                                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_selesai">Tanggal Selesai</label>
                                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="penanggung_jawab">Penanggung Jawab</label>
                                <input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab">
                            </div>

                            <div class="form-group">
                                <label for="kontak_penanggung_jawab">Kontak Penanggung Jawab</label>
                                <input type="text" class="form-control" id="kontak_penanggung_jawab" name="kontak_penanggung_jawab">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="klien">Klien</label>
                                <input type="text" class="form-control" id="klien" name="klien">
                            </div>

                            <div class="form-group">
                                <label for="kontak_klien">Kontak Klien</label>
                                <input type="text" class="form-control" id="kontak_klien" name="kontak_klien">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="catatan">Catatan</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                    </div>
                </div>

                <!-- Bagian 2: Lokasi Proyek (Site) -->
                <div class="card-body border-top">
                    <h4 class="mb-4">Lokasi Proyek</h4>

                     <div class="row">
                        <div class="col-md-12">
                            <div id="map" style="height: 400px; width: 100%;"></div>
                            <small class="text-muted">Klik pada peta untuk menentukan koordinat lokasi</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">


                            <div class="form-group">
                                <label for="Kode_Site">Kode Site</label>
                                <input type="text" class="form-control" id="Kode_Site" name="site[Kode_Site]" required value="{{ $kodeSite }}s">
                            </div>

                            <div class="form-group">
                                <label for="nama_site">Nama Site</label>
                                <input type="text" class="form-control" id="nama_site" name="site[nama_site]" required>
                            </div>

                            <div class="form-group">
                                <label for="jenis_site">Jenis Site</label>
                                <select class="form-control" id="jenis_site" name="site[jenis_site]">
                                    <option value="LOKASI_PEMBANGUNAN">Lokasi Pembangunan</option>
                                    <option value="WORKSHOP">Workshop</option>
                                    <option value="WAREHOUSE">Warehouse</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="latitude">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="site[latitude]" readonly>
                            </div>

                            <div class="form-group">
                                <label for="longitude">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="site[longitude]" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="site[alamat]" rows="2" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="desa_kelurahan">Desa/Kelurahan</label>
                                <input type="text" class="form-control" id="desa_kelurahan" name="site[desa_kelurahan]">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kecamatan">Kecamatan</label>
                                <input type="text" class="form-control" id="kecamatan" name="site[kecamatan]">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kabupaten_kota">Kabupaten/Kota</label>
                                <input type="text" class="form-control" id="kabupaten_kota" name="site[kabupaten_kota]">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="provinsi">Provinsi</label>
                        <input type="text" class="form-control" id="provinsi" name="site[provinsi]">
                    </div>
                </div>

                <!-- Bagian 3: Dokumen Bisnis -->
                <div class="card-body border-top">
                    <h4 class="mb-4">Dokumen Bisnis</h4>

                    <div id="document-container">
                        <div class="document-item border p-3 mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama Dokumen</label>
                                        <input type="text" class="form-control" name="documents[0][nama_dokumen]" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Jenis Dokumen</label>
                                        <select class="form-control" name="documents[0][jenis_dokumen]" required>
                                            <option value="">-- Pilih Jenis Dokumen --</option>
                                            @foreach($jenis_dokumen as $jenis)
                                                <option value="{{ $jenis }}">{{ $jenis }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>File Dokumen</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="documents[0][file_path]" required>
                                            <label class="custom-file-label">Pilih file</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-document">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-info" id="add-document">
                        <i class="bi bi-plus"></i> Tambah Dokumen
                    </button>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Proyek
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<!-- OPEN STREET MAP -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Counter untuk dokumen
    let docCounter = 1;

    // Fungsi untuk mengupdate nama file yang dipilih
    function updateFileName(input) {
        const fileName = input.files[0]?.name || "Pilih file";
        $(input).next('.custom-file-label').text(fileName);
    }

    // Event untuk menambah dokumen
    $('#add-document').click(function() {
        const newDoc = `
        <div class="document-item border p-3 mb-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nama Dokumen</label>
                        <input type="text" class="form-control" name="documents[${docCounter}][nama_dokumen]" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Jenis Dokumen</label>
                        <select class="form-control" name="documents[${docCounter}][jenis_dokumen]" required>
                            <option value="">-- Pilih Jenis Dokumen --</option>
                            @foreach($jenis_dokumen as $jenis)
                                <option value="{{ $jenis }}">{{ $jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>File Dokumen</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="documents[${docCounter}][file_path]" required>
                            <label class="custom-file-label">Pilih file</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-document">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        `;

        $('#document-container').append(newDoc);

        // Inisialisasi event handler untuk file input yang baru ditambahkan
        $('#document-container .document-item').last().find('.custom-file-input').on('change', function() {
            updateFileName(this);
        });

        docCounter++;
    });

    // Event untuk menghapus dokumen (menggunakan event delegation)
    $(document).on('click', '.remove-document', function() {
        $(this).closest('.document-item').remove();
    });

    // Inisialisasi event handler untuk file input pertama
    $('.custom-file-input').on('change', function() {
        updateFileName(this);
    });
});
</script>

<script>
// Inisialisasi peta setelah DOM siap
document.addEventListener('DOMContentLoaded', function() {
    // Pastikan elemen map ada
    if (!document.getElementById('map')) {
        console.error('Element with id "map" not found');
        return;
    }

    // Inisialisasi peta dengan view default
    var map = L.map('map').setView([-6.9147, 107.6098], 13); // Zoom lebih dekat (level 13)

    // Tambahkan base layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    // Tambahkan layer satelit
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'SatMode | Tiles © Esri — Source: Esri, Maxar, Earthstar Geographics, and the GIS User Community'
        }).addTo(map);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            opacity: 0.5  // Atur transparansi agar satelit tetap terlihat
        }).addTo(map);

    // Variabel marker
    var marker = null;

    // Dapatkan elemen input
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const alamatInput = document.getElementById('alamat');
    const desaInput = document.getElementById('desa_kelurahan');
    const kecamatanInput = document.getElementById('kecamatan');
    const kabupatenInput = document.getElementById('kabupaten_kota');
    const provinsiInput = document.getElementById('provinsi');

    // Event klik peta
    map.on('click', function(e) {
        // Hapus marker lama jika ada
        if (marker) {
            map.removeLayer(marker);
        }

        // Buat marker baru
        marker = L.marker(e.latlng).addTo(map);

        // Isi koordinat
        latInput.value = e.latlng.lat.toFixed(6);
        lngInput.value = e.latlng.lng.toFixed(6);

        // Reverse geocoding
        getAddressFromCoordinates(e.latlng.lat, e.latlng.lng);
    });

    // Fungsi untuk reverse geocoding
    function getAddressFromCoordinates(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const address = data.address || {};

                // Format alamat
                const jalan = address.road || '';
                const desa = address.village || address.town || '';
                const kecamatan = address.suburb || address.city_district || '';

                alamatInput.value = [jalan, desa, kecamatan].filter(Boolean).join(', ') || 'Alamat tidak ditemukan';
                desaInput.value = address.village || address.town || '';
                kecamatanInput.value = address.suburb || address.city_district || '';
                kabupatenInput.value = address.city || address.county || '';
                provinsiInput.value = address.state || '';
            })
            .catch(error => {
                console.error('Error fetching address:', error);
                Swal.fire('Error', 'Gagal mengambil data alamat', 'error');
            });
    }

    // Debug: Cek ukuran peta setelah load
    setTimeout(function() {
        console.log('Map size:', map.getSize());
        if (map.getSize().x === 0 || map.getSize().y === 0) {
            console.warn('Map has zero size - triggering resize');
            map.invalidateSize();
        }
    }, 500);
});

    // Tampilkan field jenis_proyek_lainnya jika dipilih
    $('#jenis_proyek').change(function() {
        if ($(this).val() === 'lainnya') {
            $('#jenis-lainnya-group').show();
        } else {
            $('#jenis-lainnya-group').hide();
        }
    });


</script>

