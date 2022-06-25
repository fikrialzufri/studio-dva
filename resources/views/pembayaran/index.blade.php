@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))
@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title">Daftar {{ ucwords(str_replace([':', '_', '-', '*'], ' ', $title)) }}
                        </h3>
                        {{ $data->appends(request()->input())->links() }}
                        <div class="">
                            @if ($tambah == 'true')
                                <a href="{{ route($route . '.create') }}"
                                    class="btn btn-sm btn-primary float-right text-light">
                                    <i class="fa fa-plus"></i> Tambah Data
                                </a>
                            @endif
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="" role="form" id="form" enctype="multipart/form-data">
                            <div class="row">
                                @foreach ($searches as $key => $item)
                                    <div class="col-lg-2">

                                        <label for="{{ $item['name'] }}">{{ ucfirst($item['alias']) }}</label>
                                        @include('template.formsearch')
                                    </div>
                                @endforeach

                                <div class="col-lg-3">
                                    <label for="">Aksi</label>
                                    <div class="input-group">


                                        <button type="submit" class="btn btn-warning">
                                            <span class="fa fa-search"></span>
                                            Cari
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                        <table class="table table-bordered " id="example">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    @foreach ($configHeaders as $key => $header)
                                        @if (isset($header['alias']))
                                            <th>{{ ucfirst($header['alias']) }}</th>
                                        @else
                                            <th>{{ ucfirst($header['name']) }}</th>
                                        @endif
                                    @endforeach
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)

                                    <tr>
                                        <td>{{ $index + 1 + ($data->CurrentPage() - 1) * $data->PerPage() }}</td>
                                        @foreach ($configHeaders as $key => $header)
                                            @if (isset($header['input']))
                                                @if ($header['input'] == 'rupiah')
                                                    <td>Rp. {{ format_uang($item[$header['name']]) }}</td>
                                                @elseif ($header['input'] == 'date')
                                                    <td>
                                                        @if ($item[$header['name']] != null || $item[$header['name']] != '')
                                                            {{ tanggal_indonesia($item[$header['name']]) }}
                                                        @endif
                                                    </td>
                                                @endif
                                            @else
                                                <td>{{ $item[$header['name']] }}</td>
                                            @endif
                                        @endforeach
                                        <td class="text-center">
                                            @if (isset($button))
                                                @foreach ($button as $key => $val)
                                                    @include('template.button')
                                                @endforeach
                                            @endif
                                            <a href="{{ route($route . '.show', $item->id) }}"
                                                class="btn btn-sm btn-warning text-light" data-toggle="tooltip"
                                                data-placement="top" title="lihat">
                                                <i class="nav-icon fas fa-search"></i> Lihat
                                            </a>
                                            @if ($item->aktif == 'non-aktif')
                                                @role('admin')
                                                    <form id="formAktif-{{ $item->id }}"
                                                        action="{{ route($route . '.aktif', $item->id) }}" method="POST"
                                                        style="display: none;">
                                                        {{ csrf_field() }}
                                                        {{ method_field('PUT') }}
                                                    </form>
                                                    <button class="btn btn-primary btn-sm" data-toggle="tooltip"
                                                        data-placement="top" title="Aktif"
                                                        onclick=ubah("{{ $item->id }}")>
                                                        <i class="fa fa-edit"></i> Aktif
                                                    </button>
                                                    <form id="form-{{ $item->id }}"
                                                        action="{{ route($route . '.destroy', $item->id) }}" method="POST"
                                                        style="display: none;">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                    </form>
                                                    <button class="btn btn-danger btn-sm" data-toggle="tooltip"
                                                        data-placement="top" title="Hapus"
                                                        onclick=deleteconf("{{ $item->id }}")>
                                                        <i class="fa fa-trash"></i> Hapus
                                                    </button>
                                                @endrole
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10">Data
                                            {{ ucwords(str_replace([':', '_', '-', '*'], ' ', $title)) }} tidak ada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        {{ $data->appends(request()->input())->links('template.pagination') }}
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/DataTables/css/datatables.css') }}">
@endpush
@push('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/DataTables/datatables.js') }}"></script>
    <script>
        function ubah(id) {
            const swalWithBootstrapButtons = swal.mixin({
                confirmButtonClass: "btn btn-success",
                cancelButtonClass: "btn btn-danger",
                buttonsStyling: false,
            });

            swalWithBootstrapButtons({
                title: "Anda Yakin ?",
                text: "Mengkonfirmasi pembayaran anggota ini ",
                type: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, Yakin!",
                cancelButtonText: "Tidak, kembali!",
            }).then((result) => {
                if (result.value) {
                    swalWithBootstrapButtons(
                        "question!",
                        "Mohon berhati-hati untuk mengkonfirmasi pembayaran anggota",
                        "question"
                    );
                    document.getElementById("formAktif-" + id).submit();
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons(
                        "Kembali",
                        "Mohon berhati-hati untuk Mengaktifkan data",
                        "error"
                    );
                }
            });
        }
    </script>
@endpush
