@extends('Partials.main')

@section('title', 'Kasbon')

@section('content')
<div class="container">
    <h1>Kasbon</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#kasbonModal">
        Tambah Kasbon
    </button>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Kode Kasbon</th>
                <th>Nama Karyawan</th>
                <th>Nominal</th>
                <th>Tanggal Kasbon</th>
                <th>Keterangan</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kasbons as $kasbon)
            <tr>
                <td>{{ $kasbon->Kode_Kasbon }}</td>
                <td>{{ $kasbon->user->name }}</td>
                <td>{{ $kasbon->Nominal }}</td>
                <td>{{ $kasbon->Tanggal_Kasbon }}</td>
                <td>{{ $kasbon->Keterangan }}</td>
                <td>
                    @if($kasbon->foto)
                    <img src="{{ asset('storage/kasbons/' . $kasbon->foto) }}" width="50">
                    @endif
                </td>
                <td>
                    <button class="btn btn-warning btn-edit" data-id="{{ $kasbon->id }}" data-toggle="modal" data-target="#kasbonModal">Edit</button>
                    <button class="btn btn-danger btn-delete" data-id="{{ $kasbon->id }}">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah Kasbon -->
<div class="modal fade" id="kasbonModal" tabindex="-1" role="dialog" aria-labelledby="kasbonModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kasbonModalLabel">Tambah Kasbon</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="kasbonForm">
                    @csrf
                    <input type="hidden" id="kasbonId" name="id">
                    <div class="form-group">
                        <label for="user_id">Karyawan</label>
                        <select class="form-control" id="user_id" name="user_id" required>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->Kode_Karyawan | $user->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Nominal">Nominal</label>
                        <input type="number" class="form-control" id="Nominal" name="Nominal" required>
                    </div>
                    <div class="form-group">
                        <label for="Tanggal_Kasbon">Tanggal Kasbon</label>
                        <input type="date" class="form-control" id="Tanggal_Kasbon" name="Tanggal_Kasbon" required>
                    </div>
                    <div class="form-group">
                        <label for="Keterangan">Keterangan</label>
                        <textarea class="form-control" id="Keterangan" name="Keterangan"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="saveKasbon">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Handle Tambah/Edit Kasbon
        $('#saveKasbon').click(function() {
            let formData = new FormData($('#kasbonForm')[0]);
            let url = $('#kasbonId').val() ? '/kasbon/update/' + $('#kasbonId').val() : '/kasbon/store';

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#kasbonModal').modal('hide');
                    Swal.fire('Sukses!', response.success, 'success').then(() => {
                        location.reload();
                    });
                },
                error: function(response) {
                    Swal.fire('Error!', 'Terjadi kesalahan.', 'error');
                }
            });
        });

        // Handle Edit Kasbon
        $('.btn-edit').click(function() {
            let kasbonId = $(this).data('id');
            $.get('/kasbon/edit/' + kasbonId, function(data) {
                $('#kasbonId').val(data.id);
                $('#user_id').val(data.user_id);
                $('#Nominal').val(data.Nominal);
                $('#Tanggal_Kasbon').val(data.Tanggal_Kasbon);
                $('#Keterangan').val(data.Keterangan);
                $('#kasbonModalLabel').text('Edit Kasbon');
            });
        });

        // Handle Delete Kasbon
        $('.btn-delete').click(function() {
            let kasbonId = $(this).data('id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/kasbon/destroy/' + kasbonId,
                        type: 'DELETE',
                        success: function(response) {
                            Swal.fire('Terhapus!', response.success, 'success').then(() => {
                                location.reload();
                            });
                        },
                        error: function(response) {
                            Swal.fire('Error!', 'Terjadi kesalahan.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
