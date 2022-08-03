<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Auth;
use Storage;
use Str;
use DB;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;


class PembayaranController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'pembayaran';
        $this->index = 'pembayaran';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name'    => 'Anggota',
                'alias'    => 'Nama Anggota',
            ],
            [
                'name'    => 'total_bayar',
                'input'    => 'rupiah',
                'alias'    => 'Total Bayar',
            ],
            [
                'name'    => 'bulan_tampil',
                'alias'    => 'Bulan Iuran',
            ],
            [
                'name'    => 'aktif',
                'alias'    => 'Status',
            ],
            [
                'name'    => 'tanggal',
                'alias'    => 'Tanggal Bayar',
            ]
        ];
    }
    public function configSearch()
    {
        $month = [];

        for ($m = 0; $m <= 11; $m++) {
            $month[$m] = [
                "id" => $m + 1,
                "value" => bulan_indonesia(Carbon::create()->addMonths($m + 1)->year(date('Y')))
            ];
        }
        if (!auth()->user()->hasRole('anggota')) {
            return [
                [
                    'name'    => 'anggota_id',
                    'input'    => 'combo',
                    'alias'    => 'Anggota',
                    'value' => $this->combobox(
                        'Anggota'
                    )
                ],
                [
                    'name'    => 'bulan',
                    'input'    => 'combo',
                    'alias'    => 'Bulan',
                    'value' => $month,
                ],
                [
                    'name'    => 'created_at',
                    'input'    => 'daterange',
                    'alias'    => 'Tanggal',
                ],
            ];
        } else {
            return [
                [
                    'name'    => 'bulan',
                    'input'    => 'combo',
                    'alias'    => 'Bulan',
                    'value' => $month,
                ],
                [
                    'name'    => 'created_at',
                    'input'    => 'daterange',
                    'alias'    => 'Tanggal',
                ],
            ];
        }
    }


    public function configForm()
    {
        $month = [];

        for ($m = 0; $m <= 11; $m++) {
            $month[$m] = [
                "id" => $m + 1,
                "value" => bulan_indonesia(Carbon::create()->addMonths($m)->year(date('Y')))
            ];
        }

        if (!auth()->user()->hasRole('anggota')) {
            return [
                [
                    'name'    => 'total_bayar',
                    'input'    => 'rupiah',
                    'alias'    => 'Total Bayar',
                    'validasi'    => ['required', 'min:1'],
                ],
                [
                    'name'    => 'bulan',
                    'input'    => 'combo',
                    'alias'    => 'Bulan',
                    'value' => $month,
                    'validasi'    => ['required'],
                ],
                [
                    'name'    => 'bukti_bayar',
                    'input'    => 'image',
                    'alias'    => 'Bukti Bayar',
                    'validasi'    => ['required'],
                ],
                [
                    'name'    => 'anggota_id',
                    'input'    => 'combo',
                    'alias'    => 'Anggota',
                    'value' => $this->combobox(
                        'anggota'
                    ),
                    'validasi'    => ['required'],
                ]
            ];
        } else {
            return [
                [
                    'name'    => 'bulan',
                    'input'    => 'combo',
                    'alias'    => 'Bulan',
                    'value' => $month,
                    'validasi'    => ['required'],
                ],
                [
                    'name'    => 'bukti_bayar',
                    'input'    => 'image',
                    'alias'    => 'Bukti Bayar',
                    'validasi'    => ['required'],
                ],
                [
                    'name'    => 'total_bayar',
                    'input'    => 'rupiah',
                    'alias'    => 'Total Bayar',
                    'validasi'    => ['required', 'min:1'],
                ],
            ];
        }
    }
    public function configShow()
    {
        if (!auth()->user()->hasRole('anggota')) {
            return [
                [
                    'name'    => 'total_bayar',
                    'input'    => 'rupiah',
                    'alias'    => 'Total Bayar',
                    'validasi'    => ['required', 'min:1'],
                ],
                [
                    'name'    => 'bulan',

                    'alias'    => 'Bulan',
                ],
                [
                    'name'    => 'bukti_bayar',
                    'input'    => 'image',
                    'alias'    => 'Bukti Bayar',
                    'validasi'    => ['required'],
                ],
                [
                    'name'    => 'Anggota',
                    'alias'    => 'Anggota',
                ]
            ];
        } else {
            return [
                [
                    'name'    => 'bulan',
                    'alias'    => 'Bulan',
                ],
                [
                    'name'    => 'bukti_bayar',
                    'input'    => 'image',
                    'alias'    => 'Bukti Bayar',
                ],
                [
                    'name'    => 'total_bayar',
                    'input'    => 'rupiah',
                    'alias'    => 'Total Bayar',
                ],
            ];
        }
    }

    public function model()
    {
        return new Pembayaran();
    }


    public function index()
    {
        //nama title
        if (!isset($this->title)) {
            $title =  ucwords($this->route);
        } else {
            $title =  ucwords($this->title);
        }



        //nama route
        $route =  $this->route;

        //nama relation
        $relations =  $this->relations;

        //nama jumlah pagination
        $paginate =  $this->paginate;

        //declare nilai serch pertama
        $search = null;

        //memanggil configHeaders
        $configHeaders = $this->configHeaders();

        //memangil model peratama
        $query = $this->model()::query();

        //button
        $button = null;

        //tambah data
        $tambah = $this->tambah;

        //tambah data
        $upload = $this->upload;

        $export = null;

        if ($this->configButton()) {
            $button = $this->configButton();
        }
        //mulai pencarian --------------------------------
        $searches = $this->configSearch();
        $searchValues = [];
        $n = 0;
        $countAll = 0;
        $queryArray = [];
        $queryRaw = '';
        foreach ($searches as $key => $val) {
            $search[$key] = request()->input($val['name']);
            $hasilSearch[$val['name']] = $search[$key];

            if ($search[$key]) {
                if ($val['input'] != 'daterange') {
                    # code...
                    $searchValues[$key] = preg_split('/\s+/', $search[$key], -1, PREG_SPLIT_NO_EMPTY);

                    if (count($searchValues[$key]) == 1) {
                        foreach ($searchValues[$key] as $index => $value) {
                            $query->where($val['name'], 'like', "%{$value}%");
                            $countAll = $countAll + 1;
                        }
                    } else {
                        $lastquery = '';

                        foreach ($searchValues[$key] as $index => $word) {
                            if (preg_match("/^[a-zA-Z0-9]+$/", $word) == 1) {

                                if ($queryRaw) {
                                    $count =  $this->model()->whereRaw(rtrim($queryRaw, " and"))->count();
                                    if ($count > 0) {
                                        $countAll = $countAll + 1;
                                        $lastquery = $queryRaw;

                                        $queryRaw .= $val['name'] . ' LIKE "%' . $word . '%" and ';
                                        if ($this->model()->whereRaw(rtrim($queryRaw, " and"))->count() == 0) {
                                            $queryRaw = $lastquery;
                                        }
                                    }
                                } else {
                                    $count =  $this->model()->where($val['name'], 'like', "%{$word}%")->count();
                                    if ($count > 0) {
                                        $countAll = $countAll + 1;

                                        $queryRaw .= $val['name'] . ' LIKE "%' . $word . '%" and ';
                                        continue;
                                    }
                                }
                            }
                        }
                    }

                    if ($queryRaw) {
                        $query->whereRaw(rtrim($queryRaw, " and "));
                    }
                    if (count($queryArray) > 0) {
                        $query->where($queryArray);
                    }
                } else {
                    $date = explode(' - ', request()->input($val['name']));
                    $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
                    $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
                    $query = $query->whereBetween(DB::raw('DATE(' . $val['name'] . ')'), array($start, $end));

                    $export .= 'from=' . $start . '&to=' . $end;
                    $countAll = $countAll + 1;
                }

                if ($countAll == 0) {
                    $query->where('id',  "");
                }
            }
            $export .= $val['name'] . '=' . $search[$key] . '&';
        }

        // return $ayam;

        //akhir pencarian --------------------------------
        // relatio
        // sort by
        if ($this->user) {
            if (!Auth::user()->hasRole('superadmin') && !Auth::user()->hasRole('admin')) {
                $query->where('user_id', Auth::user()->id);
            }
        }
        if (Auth::user()->hasRole('anggota')) {
            $query->where('anggota_id', Auth::user()->id_anggota);
        }
        if ($this->sort) {
            if ($this->desc) {
                $data = $query->orderBy($this->sort, $this->desc);
            } else {
                $data = $query->orderBy($this->sort);
            }
        }
        //mendapilkan data model setelah query pencarian
        if ($paginate) {
            $data = $query->paginate($paginate);
        } else {
            $data = $query->get();
        }

        // return $button;
        $template = 'template.index';
        if ($this->index) {
            $template = $this->index . '.index';
        }
        // return  $data;

        return view($template,  compact(
            "title",
            "data",
            'searches',
            'hasilSearch',
            'button',
            'tambah',
            'upload',
            'search',
            'export',
            'configHeaders',
            'route'
        ));
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama',
            'same' => 'Password dan konfirmasi password harus sama',
        ];

        $status = 'non-aktif';
        if (!auth()->user()->hasRole('anggota')) {

            $this->validate(request(), [
                'total_bayar' => 'required|min:1',
                'anggota_id' => 'required',
            ], $messages);

            $anggota_id = $request->anggota_id;
            $status = 'aktif';
        } else {

            $this->validate(request(), [
                'total_bayar' => 'required|min:1',
            ], $messages);
            $anggota_id = auth()->user()->id_anggota;
        }

        // request bulan lebih kecil dari bulan sebelumnya di anggota pembayarann makann error
        $bulan = $request->bulan;
        $checkbulan = $this->model()->where('anggota_id', $anggota_id)->where('bulan', '<', $bulan)->first();

        if (!isset($checkbulan)) {
            return redirect()->route('pembayaran.index')->with('message', ' tidak boleh membayaran di bulan sebelumnya')->with('Class', 'danger');
        }

        $total_bayar = str_replace(".", "", $request->total_bayar);
        $bukti_bayar = $request->bukti_bayar;
        if ($total_bayar  < 130000 || $total_bayar  > 130000) {
            return redirect()->back()->with('message', 'Maaf pembayaran anda kurang')->with('Class', 'danger');;
        }

        $pembayaran = $this->model()->where('bulan',  $bulan)->where('anggota_id', $anggota_id)->first();

        $dataPembayaran = Pembayaran::count();
        if ($dataPembayaran >= 1) {
            $no = str_pad($dataPembayaran + 1, 4, "0", STR_PAD_LEFT);
            $noPembayaran =  $no . "/" . "DVA-PEN/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        } else {
            $no = str_pad(1, 4, "0", STR_PAD_LEFT);
            $noPembayaran =  $no . "/" . "DVA-PEN/" . date('Y')  . "/" . date('d') . "/" . date('m') . "/" . rand(0, 900);
        }


        if (!$pembayaran) {
            $pembayaran = $this->model();
            if ($request->hasFile('bukti_bayar')) {
                $foto = $request->bukti_bayar;
                $route =  $this->route;
                $this->validate(request(), [
                    'foto' => 'mimes:jpeg,bmp,png,jpg'
                ], $messages);

                $nama_gambar = Str::slug($route) . '-' . Str::Random(15) . '.' . $request->file('bukti_bayar')->getClientOriginalExtension();

                if (!Storage::disk('public')->exists('pembayaran')) {
                    Storage::disk('public')->makeDirectory('pembayaran');
                }

                $path = public_path('storage/pembayaran/' . $nama_gambar);

                $gambar_original = Image::make($request->bukti_bayar)->resize(720, 720)->save($path);
                Storage::disk('public')->put('pembayaran/' . $nama_gambar, $gambar_original);

                if (!Storage::disk('public')->exists('pembayaran/thumbnail')) {
                    Storage::disk('public')->makeDirectory('pembayaran/thumbnail');
                }
                $thumbnail = Image::make($foto)->resize(360, 360)->save($path);
                Storage::disk('public')->put('pembayaran/thumbnail/' . $nama_gambar, $thumbnail);

                $pembayaran->bukti_bayar = $nama_gambar;
            }
            $pembayaran->no_pembayaran =  $noPembayaran;
            $pembayaran->bulan =  $bulan;
            $pembayaran->total_bayar =  $total_bayar;
            $pembayaran->anggota_id =  $anggota_id;
            $pembayaran->aktif  =  $status;
            $pembayaran->save();
            return redirect()->route('pembayaran.index')->with('message', 'Pembayaran iuran berhasil')->with('Class', 'success');
        } else {
            return redirect()->route('pembayaran.index')->with('message', 'ada sudah membayaran iuran')->with('Class', 'danger');;
        }
    }

    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->model()->find($id);
        if (!isset($this->title)) {
            $title =  "Detail " . ucwords(str_replace('-', ' ', $this->route)) . ' - : ' . $data->nama;
        } else {
            $title =  "Detail " . ucwords(str_replace('-', ' ', $this->title)) . ' - : ' . $data->nama;
        }

        if (isset($this->manyToMany)) {
            if (isset($this->extraFrom)) {
                if (isset($this->relations)) {
                    foreach ($this->relations as $item) {
                        $hasRalation = 'has' . ucfirst($item);
                        foreach ($this->manyToMany as  $value) {
                            try {
                                $field = $value . '_id';
                                $valueField = $data->$hasRalation->$value()->first()->id;
                                $data->$field = $valueField;
                            } catch (\Throwable $th) {
                                try {
                                    $field = $value . '_id';
                                    $valueField = $data->$hasRalation()->$value()->first()->id;
                                    $data->$field = $valueField;
                                } catch (\Throwable $th) {
                                    //throw $th;
                                }
                            }
                        }
                    }
                }
            }
        }
        if (isset($this->oneToMany)) {
            foreach ($this->oneToMany as $item) {
                $hasRalation = 'has' . ucfirst($item);
                $field = $item . '_id';
                if (count($data->$hasRalation) != 0) {
                    $valueField = $data->$hasRalation()->first()->id;
                    $data->$field = $valueField;
                }
            }
        }

        //nama route dan action route
        $route =  $this->route;
        $store =  "update";

        $form = $this->configShow();
        $count = count($form);

        $colomField = $this->colomField($count);

        $countColom = $this->countColom($count);
        $countColomFooter = $this->countColomFooter($count);

        return view('pembayaran.show', compact(
            'route',
            'store',
            'colomField',
            'countColom',
            'countColomFooter',
            'title',
            'form',
            'data'
        ));
    }


    public function aktif($id)
    {
        $pembayaran = Pembayaran::find($id);
        $pembayaran->aktif = "aktif";
        $pembayaran->save();

        return redirect()->route('pembayaran.index')->with('message', 'pembayaran berhasil diaktifkan')->with('Class', 'success');
    }
}
