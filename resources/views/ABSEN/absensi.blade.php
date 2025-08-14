@extends('Partials.main')

@section('content')
<div class="pagetitle">
    <h1>Absensi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Absensi</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">


                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Form Absensi Selfie </h5>
                        <div>
                            | {{ Carbon\Carbon::now()->translatedFormat('l, d F Y') }} | {{ Carbon\Carbon::now()->translatedFormat('H:i') }} |
                        </div>
                    </div>

                    @if($isAbsenMasukTime || $isAbsenPulangTime)
                        <!-- Form Absensi -->
                        <form id="absensiForm" action="{{ route('absensi.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            {{-- <input type="hidden" name="Kode_Site" id="Kode_Site" value="{{ Auth::user()->userDetail->Kode_Site }}"> --}}

                            @if ($now->between($jamMasukStart, $jamMasukEnd))
                                <input type="hidden" name="absen_type" value="masuk">
                                <h6>Absen Masuk</h6>
                            @elseif ($now->between($jamPulangStart, $jamPulangEnd))
                                <input type="hidden" name="absen_type" value="pulang">
                                <h6>Absen Pulang</h6>

                            @endif
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                            <input type="file" accept="image/*" style="display:none;" name="selfie" id="selfieInput">

                            <div class="mb-3">
                                <label for="Kode_Karyawan" class="form-label">Pilih Karyawan:</label>
                                <select class="form-select" name="Kode_Karyawan" id="Kode_Karyawan" required>
                                    <option value="">-- Pilih Karyawan --</option>
                                    {{-- @foreach($karyawan as $k)
                                        <option value="{{ $k->Kode_Karyawan }}">{{ $k->Nama_Karyawan }} ({{ $k->Kode_Karyawan }})</option>
                                    @endforeach --}}
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="selfie" class="form-label">Ambil Selfie:</label>
                                <div class="camera-container">
                                    <!-- Video untuk menampilkan kamera -->
                                    <video id="video" width="100%" height="auto" autoplay class="mirrored"></video>
                                    <!-- Gambar untuk menampilkan foto yang diambil -->
                                    <img id="photo" src="" alt="Foto Selfie" style="display:none; width:100%; height:auto;" class="mirrored">
                                    <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>
                                </div>
                                <div class="btn-group mt-3" role="group">
                                    <button type="button" id="capture" class="btn btn-primary">
                                        <i class="bi bi-camera"></i> Ambil Foto
                                    </button>
                                    <button type="button" id="cancelCapture" class="btn btn-danger" style="display:none;">
                                        <i class="bi bi-x-circle"></i> Batalkan
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Submit Absensi
                            </button>
                        </form>
                        @else
                        <div class="alert alert-danger" role="alert">
                            @if($now < $jamMasukStart)
                                Form absensi masuk akan tersedia pukul 08:00-09:00
                            @elseif($now > $jamMasukEnd && $now < $jamPulangStart)
                                Form absensi pulang akan tersedia pukul 15:00-16:00
                            @else
                                Form Absensi sudah ditutup. Silakan hubungi admin untuk informasi lebih lanjut.
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script untuk Kamera dan Geolokasi -->
<script>
    // Error display function
    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: message,
        });
    }

    // Get geolocation
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
            },
            error => showError("Akses lokasi ditolak atau tidak tersedia. Pastikan Anda mengizinkan akses lokasi.")
        );
    } else {
        showError("Browser tidak mendukung geolokasi.");
    }

    // Camera elements
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const photo = document.getElementById('photo');
    const selfieInput = document.getElementById('selfieInput');
    const captureBtn = document.getElementById('capture');
    const cancelBtn = document.getElementById('cancelCapture');

    // Initialize camera
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => video.srcObject = stream)
        .catch(err => showError("Akses kamera ditolak. Pastikan Anda mengizinkan akses kamera."));

    // Toggle between camera and photo
    function toggleCameraMode(isPhotoTaken) {
        // Toggle visibility of video and photo
        video.style.display = isPhotoTaken ? 'none' : 'block';
        photo.style.display = isPhotoTaken ? 'block' : 'none';

        // Toggle button visibility
        captureBtn.style.display = isPhotoTaken ? 'none' : 'block';
        cancelBtn.style.display = isPhotoTaken ? 'block' : 'none';
    }

    // Capture photo handler
    captureBtn.addEventListener('click', () => {
        const employeeCode = document.getElementById('Kode_Karyawan').value;

        if (!employeeCode) {
            showError("Silakan pilih karyawan terlebih dahulu");
            return;
        }

        if (!video.srcObject) {
            showError("Kamera belum siap. Silakan refresh halaman.");
            return;
        }

        // Capture image
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = canvas.toDataURL('image/png');

        // Set filename with timestamp
        const now = new Date();
        const fileName = `ABSEN_${employeeCode}_${now.toISOString().slice(0,10)}_${now.getHours()}${now.getMinutes()}${now.getSeconds()}`;

        // Create file object
        const blob = dataURLtoBlob(imageData);
        const file = new File([blob], `${fileName}.png`, { type: 'image/png' });

        // Update file input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        selfieInput.files = dataTransfer.files;

        // Display captured image
        photo.src = imageData;
        toggleCameraMode(true);
    });

    // Cancel photo handler
    cancelBtn.addEventListener('click', () => {
        // Reset to camera mode
        selfieInput.value = '';
        toggleCameraMode(false);
    });

    // Helper function
    function dataURLtoBlob(dataURL) {
        const arr = dataURL.split(',');
        const mime = arr[0].match(/:(.*?);/)[1];
        const bstr = atob(arr[1]);
        const u8arr = new Uint8Array(bstr.length);

        for (let i = 0; i < bstr.length; i++) {
            u8arr[i] = bstr.charCodeAt(i);
        }

        return new Blob([u8arr], { type: mime });
    }

    // Success notification
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: '{{ session('success') }}',
        });
    @endif
</script>

<!-- Style untuk Kamera -->
<style>
    .camera-container {
        max-width: 100%;
        border: 2px solid #ddd;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 15px;
        text-align: center;
    }

    video, img {
        max-width: 100%;
        height: auto;
        background: #000;
    }

    /* Mirror efek untuk video */
    .mirrored {
        transform: scaleX(-1);
    }

    canvas {
        display: none;
    }

    .btn-group {
        display: flex;
        gap: 10px;
    }

    /* Responsive design untuk handphone */
    @media (max-width: 768px) {
        .camera-container {
            max-width: 80%;
            margin: 0 auto 15px;
        }

        video, img {
            max-width: 100%;
            height: auto;
        }

        /* Tetap potrait di handphone */
        video {
            transform: scaleX(-1) rotate(0deg);
        }
    }
</style>
@endsection
