@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))

@section('content')
    <div class="container-fluid">
        @if (!Auth::user()->hasRole('anggota'))
            <div class="row">
                <!-- page statustic chart start -->
                <div class="col-xl-6 col-md-6">
                    <div class="card card-red text-white">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-0">{{ __($anggota) }}</h4>
                                    <p class="mb-0">{{ __('Anggota') }}</p>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="fas fa-cube f-30"></i>
                                </div>
                            </div>
                            <div id="Widget-line-chart1" class="chart-line chart-shadow"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="card card-blue text-white">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-0">{{ __('Rp. ' . format_uang($pembayaranSum)) }}</h4>
                                    <p class="mb-0">{{ __('Pembayaran') }}</p>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="ik ik-shopping-cart f-30"></i>
                                </div>
                            </div>
                            <div id="Widget-line-chart2" class="chart-line chart-shadow"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <span>Daftar Pembayaran Terakhir</span>
                    </div>
                    <div class="card-body">
                        <div class="col-12">
                            <table class="table table-bordered " id="example">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Bulan</th>
                                        <th class="text-center">Tanggal Bayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($listPembayaran as $index => $pem)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $pem->nama }}</td>
                                            <td>{{ $pem->bulan }}</td>
                                            <td>{{ tanggal_indonesi($pem->created_at) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                @foreach ($PembayaranPerbulan as $item)
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="widget {{ $item['jumlah'] > 0 ? 'bg-primary' : 'bg-danger' }}">
                            <div class="widget-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="state">
                                        <h6>Bulan {{ $item['bulan'] }}</h6>
                                        <h2>{{ $item['jumlah'] > 0 ? 'Sudah Bayar' : 'belum bayar' }}</h2>
                                    </div>
                                    <div class="icon">
                                        <i class="ik ik-file-text"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif


    </div>
@stop

@push('chart')
@endpush
@push('style')
    <style>
        @media (max-width: 500px) {
            #perda {
                height: 52px;
            }
        }

        .modal {
            text-align: center;
        }

        @media screen and (min-width: 768px) {
            .modal:before {
                display: inline-block;
                vertical-align: middle;
                content: " ";
                position: absolute;
                height: 100%;

            }
        }

        .modal-dialog {
            display: inline-block;
            text-align: left;
            vertical-align: middle;
            top: 50%;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset('plugins/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('plugins/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/jquery.flot.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/jquery.flot.categories.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/curvedLines.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/jquery.flot.tooltip.min.js') }}"></script>

    <script src="{{ asset('plugins/amcharts/amcharts.js') }}"></script>
    <script src="{{ asset('plugins/amcharts/serial.js') }}"></script>
    <script src="{{ asset('plugins/amcharts/themes/light.js') }}"></script>


    <script src="{{ asset('js/widget-statistic.js') }}"></script>
    <script src="{{ asset('js/widget-data.js') }}"></script>
    <script src="{{ asset('js/dashboard-charts.js') }}"></script>
    <script>
        let grafikperbulan = @json($aduanPerbulanGrafik);
        console.log(grafikperbulan);

        var chart = AmCharts.makeChart("line_chart", {
            "type": "serial",
            "theme": "light",
            "dataDateFormat": "YYYY-MM-DD",
            "precision": 2,
            "valueAxes": [{
                "id": "v1",
                "position": "left",
                "autoGridCount": false,
                "labelFunction": function(value) {
                    return "$" + Math.round(value) + "M";
                }
            }, {
                "id": "v2",
                "gridAlpha": 0,
                "autoGridCount": false
            }],
            "graphs": [{
                "id": "g1",
                "valueAxis": "v2",
                "bullet": "round",
                "bulletBorderAlpha": 1,
                "bulletColor": "#FFFFFF",
                "bulletSize": 8,
                "hideBulletsCount": 50,
                "lineThickness": 3,
                "lineColor": "#2ed8b6",
                "title": "Aduan",
                "useLineColorForBulletBorder": true,
                "valueField": "aduan",
                "balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
            }, {
                "id": "g2",
                "valueAxis": "v2",
                "bullet": "round",
                "bulletBorderAlpha": 1,
                "bulletColor": "#FFFFFF",
                "bulletSize": 8,
                "hideBulletsCount": 50,
                "lineThickness": 3,
                "lineColor": "#e95753",
                "title": "Pekerjaan",
                "useLineColorForBulletBorder": true,
                "valueField": "pekerjaan",
                "balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
            }],
            "chartCursor": {
                "pan": true,
                "valueLineEnabled": true,
                "valueLineBalloonEnabled": true,
                "cursorAlpha": 0,
                "valueLineAlpha": 0.2
            },
            "categoryField": "date",
            "categoryAxis": {
                "parseDates": true,
                "dashLength": 1,
                "minorGridEnabled": true
            },
            "legend": {
                "useGraphSettings": true,
                "position": "top"
            },
            "balloon": {
                "borderThickness": 1,
                "shadowAlpha": 0
            },
            "dataProvider": grafikperbulan
        });
    </script>
@endpush
