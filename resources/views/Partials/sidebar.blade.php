  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

    {{-- KARYAWAN --}}
        @if(Auth::user()->role == 'KARYAWAN_SITE')
        @endif
        {{-- END KARYAWAN --}}
        {{-- ADMIN --}}
        @if(Auth::user()->role == 'ADMIN_SITE')



        @endif
    {{-- END ADMIN --}}

    {{-- Project Leader --}}
    @if(Auth::user()->role == 'PROJECT_LEADER' || Auth::user()->role == 'ACCOUNTING' || Auth::user()->role == 'KEUANGAN')
        <li>
            <a class="nav-link collapsed" href="{{ route('site.index') }}" class="nav-link">
                <span> Site </span>
            </a>
        </li>
        <li>
            <a class="nav-link collapsed" href="{{ route('projects.index') }}" class="nav-link">
                <span> Proyek </span>
            </a>
        </li>
        {{-- <li>
            <a class="nav-link collapsed" href="{{ route('site-task-jobs.index') }}" class="nav-link">
                <span>Tugas Pengerjaan</span>
            </a>
        </li> --}}

        {{-- <li>
            <a class="nav-link collapsed" href="{{ route('absensi.index') }}" class="nav-link">
                <span>Absensi</span>
            </a>
        </li>
        <li>
            <a class="nav-link collapsed" href="{{ route('penerimaan_barang_site.index') }}" class="nav-link">
                <span>Penerimaan Barang di site</span>
            </a>
        </li> --}}

        {{-- <li>
            <a class="nav-link collapsed" href="{{ route('task-to-do.index') }}" class="nav-link">
                <span>Daftar Tugas</span>
            </a>
        </li>
        <li>
            <a class="nav-link collapsed" href="" class="nav-link">
                <span>Laporan Harian</span>
            </a>
        </li> --}}

        <li>
            <a class="nav-link collapsed" href="{{ route('barang.index') }}" class="nav-link">
                <span>Barang</span>
            </a>
        </li>

        <li>
            <a class="nav-link collapsed" href="{{ route('supplier.index') }}" class="nav-link">
                <span>Supplier</span>
            </a>
        </li>

        {{-- Produksi Barang --}}
         <li>
            <a class="nav-link collapsed" href="{{ route('produksi.gantt') }}" class="nav-link">
                <span>Produksi Barang </span>
            </a>
        </li>


        {{-- approving permintaan barang --}}
        {{-- <li>
            <a class="nav-link collapsed" href="{{ route('site-request.review') }}" class="nav-link">
                <span>Request dari Site (PL & ACCT)</span>
            </a>
        </li> --}}



        {{-- Untuk Site --}}
        {{-- <li>
            <a class="nav-link collapsed" href="{{ route('site-request.index') }}" class="nav-link">
                <span>Permohonan / Site Request</span>
            </a>
        </li> --}}

        {{-- <li>
            <a class="nav-link collapsed" href="{{ route('pengguna.index') }}" class="nav-link">
                <span>Pengguna</span>
            </a>
        </li>
        <li>
            <a class="nav-link collapsed" href="{{ route('site_request.review') }}" class="nav-link">
                <span>Purchase Requisition</span>
            </a>
        </li> --}}

        {{-- <li>
            <a class="nav-link collapsed" href="{{ route('karyawan-lapangan.index') }}" class="nav-link">
                <span>Karyawan</span>
            </a>
        </li>
        <li>
            <a class="nav-link collapsed" href="{{ route('karyawan-lapangan.index') }}" class="nav-link">
                <span>Transfer Stock (Pengeluaran)</span>
            </a>
        </li> --}}
        <li>
            <a class="nav-link collapsed" href="{{ route('good-receipt.index') }}" class="nav-link">
                <span>Penerimaan Barang Warehouse</span>
            </a>
        </li>
        {{-- <li>
            <a class="nav-link collapsed" href="{{ route('karyawan-lapangan.index') }}" class="nav-link">
                <span>Inventaris Stok</span>
            </a>
        </li> --}}

    @endif
    {{-- END Project Leader --}}

    {{-- KEUANGAN --}}
    @if(Auth::user()->role == 'ACCOUNTING' || Auth::user()->role == 'KEUANGAN')


    @endif
    {{-- END KEUANGAN --}}

    </ul>

  </aside>

  <!-- End Sidebar-->
