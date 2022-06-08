@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))

@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <form>
            <div class="row">

                @foreach ($colomField as $index => $value)
                    <div class="col-md-{{ $countColom }}">
                        <div class="card">
                            <!-- /.card-header -->

                            <div class="card-body">
                                @foreach (array_slice($form, $value[0], $value[1]) as $key => $item)
                                    @include('pembayaran.input')
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- ./col -->
            </div>
        </form>
        <!-- /.row -->
        <!-- Main row -->
        <!-- /.row (main row) -->
        <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <img src="" class="imagepreview" style="width: 100%;">
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
@endsection

@push('script')
    <script>
        $('.pop').on('click', function() {
            $('.imagepreview').attr('src', $(this).find('img').attr('src'));
            $('#imagemodal').modal('show');
        });
    </script>
@endpush
