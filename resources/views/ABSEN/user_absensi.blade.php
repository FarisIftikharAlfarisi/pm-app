@extends('Partials.main')

@section('content')
<div class="pagetitle">
    <h1>Riwayat Absensi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Riwayat Absensi</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Riwayat Absensi Bulan {{ Carbon\Carbon::now()->translatedFormat('F Y') }}</h5>

                    <!-- Tampilkan kalender -->
                    <div class="row">
                        @foreach ($kalender as $hari)
                            <div class="col-md-2 mb-3">
                                <div class="card text-center {{ $hari['warna'] }}">
                                    <div class="card-body">
                                        <h5 class="card-title text-white">{{ $hari['tanggal'] }}</h5>
                                        <p class="card-text text-white">{{ $hari['status'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
