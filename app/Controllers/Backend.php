<?php
namespace App\Controllers;

use App\Models\JabatanModel;
use App\Models\PenggunaModel;
use App\Models\KomponenModel;
use App\Models\TransaksiModel;
use App\Models\LaporanModel;
use App\Models\JabatanDatatable;
use App\Models\PenggunaDatatable;
use App\Models\KomponenDatatable;
use App\Models\TransaksiDatatable;
use App\Models\LaporanDatatable;
use Config\Services;

use Midtrans\Config;
use Midtrans\Snap;


class Backend extends BaseController
{

    protected $model_jabatan;
    protected $model_komponen;
    protected $model_pengguna;
    protected $model_transaksi;
    protected $model_laporan;

    public function __construct()
    {
        $this->model_jabatan = new JabatanModel();
        $this->model_komponen = new KomponenModel();
        $this->model_pengguna = new PenggunaModel();
        $this->model_transaksi = new TransaksiModel();
        $this->model_laporan = new LaporanModel();
    }

    private function template($folder, $header)
    {
        echo view('template/header', $header);
        echo view('template/top_menu');
        echo view('template/side_menu');
        echo view($folder . '/index'); // template -> $folder
        echo view('template/footer');
    }

    private function response($status, $code, $message, $data = null)
    {
        http_response_code($code);
        return json_encode(
            [
                'message' => $message,
                'status' => $status,
                'data' => $data,
            ],
            JSON_PRETTY_PRINT
        );
    }

    private function show_all_sessions()
    {
        # tampilkan seluruh session
        echo json_encode(session()->get()); return;
    }

    private function create_transaction()
    {
        # return token

        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$serverKey    = SERVER_KEY_MIDTRANS_SANDBOX;

        // create sample transaction
        $transaction_details = [
            'order_id' => rand(),
            'gross_amount' => "50000000",
        ];

        $customer_details = [
            'first_name' => "Hafiz Ramadhan",
            'email' => "hfzrmd@gmail.com",
            'phone' => "083819954386",
        ];

        $time = time();
        $custom_expiry = [
            'start_time' => date("Y-m-d H:i:s O", $time),
            'unit' => 'day',
            'duration' => 30
        ];

        $payload = [
            'transaction_details'   => $transaction_details,
            'customer_details'      => $customer_details,
            'credit_card' => [
                'secure' => true,
            ],
            'expiry' => $custom_expiry,
        ];
        
        try {
            $snapToken = \Midtrans\Snap::getSnapToken($payload);
            if ($snapToken === null) {
                throw new Exception('Failed to get Snap Token');
            }
            // json(response(true, 200, 'success', $snapToken));
            return $snapToken;
        } catch (\Exception $e) {
            echo $e->getMessage();
            http_response_code(404);
            die();
        }
    }

    function index()
    {
        # ini halaman dashboard
        // $this->show_all_sessions();

        $folder = 'modul/dashboard';
        $header = [
            'title' => 'Halaman Dashboard',
        ];
        $this->template($folder, $header);
    }
    //charts
    function charts()
    {
        # sql
        $db = \Config\Database::connect();
        $builder = $db->table('pengguna p');
        $builder->select('j.nama, COUNT(p.id) as total');
        $builder->join('jabatan j', 'p.id_jabatan = j.id');
        $builder->groupBy('p.id_jabatan');
        $query = $builder->get();

        $data = $query->getResult();

        $labels = array();
        $totals = array();

        foreach ($data as $row) {
            array_push($labels, $row->nama);
            array_push($totals, $row->total);
        }

        $variabels = [
            'title'     => 'Halaman Charts',
            'labels'    => $labels,
            'totals'    => $totals,
        ];
        # sql

        # ini halaman chartjs
        $folder = 'modul/Charts';
        $this->template($folder, $variabels);
    }

    # jabatan
    function jabatan()
    {
        # ini halaman jabatan
        $folder = 'modul/jabatan';
        $header = [
            'title' => 'Halaman Jabatan',
        ];
        $this->template($folder, $header);
    }

    function submit_jabatan()
    {

        $rules = [
            'nama' => [
                'label' => 'Nama Jabatan',
                'rules' => 'required|is_unique[jabatan.nama]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'is_unique' => '{field} sudah terdaftar!',
                ],
            ],
            
        ];

        // $validation = $this->validate($rules);
        $validation = \Config\Services::validation()->setRules($rules);
        if (!$validation->withRequest($this->request)->run()) {
            // Form tidak valid, tampilkan halaman form dengan error message
            $response['errors'] = $validation->getErrors();
            echo $this->response(false, 400, $response);
            return;
        }

        $create = $this->model_jabatan->save([
            'nama'  => $this->request->getVar('nama'),
        ]);

        if (!$create) {
            echo $this->response(false, 500, 'internal server error');
        }

        echo $this->response(true, 200, 'success');
    }

    function edit_jabatan()
    {
        # data untuk diupdate
        $data = [
            "nama"    =>  $this->request->getPost('nama')
        ];

        # kondisi where, diupdate berdasarkan apa ?
        $where = [
            'id'    =>  $this->request->getPost('id')
        ];

        # menggunakna fungsi built-in codeigniter
        $update = $this->model_jabatan->update($where, $data);
        if (!$update) {
            echo $this->response(false, 500, 'failed update');
        }
        echo $this->response(true, 200, 'success update');
    }

    function list_jabatan()
    {
        $request = Services::request();
        $datatable = new JabatanDatatable($request);

        if ($request->getMethod(true) === 'POST') {
            $lists = $datatable->getDatatables();
            $data = [];
            $no = $request->getPost('start');

            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama;
                $row[] = "<button id='button-edit' data-id='$list->id' class='btn btn-warning btn-sm'> Edit </button>
                <button id='button-delete' data-id='$list->id' class='btn btn-danger btn-sm'> Hapus </button>";
                $data[] = $row;
            }

            $output = [
                'draw' => $request->getPost('draw'),
                'recordsTotal' => $datatable->countAll(),
                'recordsFiltered' => $datatable->countFiltered(),
                'data' => $data,
            ];

            echo json_encode($output);
        }
    }

    function delete_jabatan()
    {
        $id = $this->request->getVar('id');
        $delete = $this->model_jabatan->delete($id);
        if (!$delete) {
            echo $this->response(false, 500, 'delete jabatan failed');
        }
        echo $this->response(true, 200, 'delete jabatan success');
    }

    function get_jabatan()
    {
        header('Content-Type: application/json');
        $id = $this->request->getVar('id');
        $data = $this->model_jabatan->find($id);
        if (!$data) {
            echo $this->response(false, 500, 'internal server error');
        }
        echo $this->response(true, 200, 'success', $data);
    }

    function data_jabatan()
    {
        // json data array jabatan
        // ambil data id, nama dari table jabatan
        header('Content-Type: application/json');
        $data = $this->model_jabatan->data();
        echo $this->response(true, 200, 'success', $data);
    }
    # jabatan

    function komponen()
    {
        # ini halaman komponen gaji
        $folder = 'modul/komponen';
        $header = [
            'title' => 'Halaman Komponen',
        ];
        $this->template($folder, $header);
    }

    function submit_komponen()
    {
        $rules = [
            'jabatan_pegawai' => [
                'label' => 'Nama Jabatan pegawai',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field}  jabatan pegawai harus diisi',
                ],
            ],
            
            'jumlah_jam' => [
                'label' => 'jumlah jam',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} jumlah jam harus diisi',
                ],
            ],
             'gaji_pokok' => [
                'label' => 'gaji_pokok',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} jumlah jam harus diisi',
                ],
            ],
             'jumlah_jam' => [
                'label' => 'jumlah jam',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} jumlah jam harus diisi',
                ],
            ],
             'tunjangan_jabatan' => [
                'label' => 'tunjangan_jabatan',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} jumlah jam harus diisi',
                ],
            ],
             'total_gaji' => [
                'label' => 'total_gaji',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} jumlah jam harus diisi',
                ],
            ],
        ];

       // $validation = $this->validate($rules);
        $validation = \Config\Services::validation()->setRules($rules);
        if (!$validation->withRequest($this->request)->run()) {
           // Form tidak valid, tampilkan halaman form dengan error message
           $response['errors'] = $validation->getErrors();
           echo $this->response(false, 400, $response);
           return;
       }

       $pengguna           = $this->request->getPost('pengguna');
       $jabatan_pegawai    = $this->request->getPost('jabatan_pegawai');
       $jumlah_jam         = $this->request->getPost('jumlah_jam');
       $gaji_pokok         = $this->request->getPost('gaji_pokok');
       $tunjangan_jabatan  = $this->request->getPost('tunjangan_jabatan');
       $total_gaji         = $this->request->getPost('total_gaji');


       $create = $this->model_komponen->save([
        'jabatan_pegawai'   => $jabatan_pegawai,
        'id_pengguna'       => $pengguna,
        'jumlah_jam'        => $jumlah_jam,
        'gaji_pokok'        => $gaji_pokok,
        'tunjangan_jabatan' => $tunjangan_jabatan,
        'total_gaji'        => $total_gaji,
    ]);
       if (!$create) {
        echo $this->response(false, 500, 'internal server error');
    }

    echo $this->response(true, 200, 'success. Nilai pengguna: '.$pengguna);
}
function detail_komponen()
{
    header('Content-Type: application/json');
    $id = $this->request->getVar('id');
    $data = $this->model_komponen->find($id);
    if (!$data) {
        echo $this->response(false, 500, 'internal server error');
    }
    echo $this->response(true, 200, 'success', $data);     
}
function edit_komponen()
{
        # data untuk diupdate
    $data = [
        "id_pengguna"          =>  $this->request->getPost('pengguna'),
        "jabatan_pegawai"   =>  $this->request->getPost('jabatan_pegawai'),
        "jumlah_jam"        =>  $this->request->getPost('jumlah_jam'),
        "gaji_pokok"        =>  $this->request->getPost('gaji_pokok'),
        "tunjangan_jabatan" =>  $this->request->getPost('tunjangan_jabatan'),
        "total_gaji"        =>  $this->request->getPost('total_gaji'),
    ];

        # kondisi where, diupdate berdasarkan apa ?
    $where = [
        'id'    =>  $this->request->getPost('id')
    ];

        # menggunakna fungsi built-in codeigniter
    $update = $this->model_komponen->update($where, $data);
    if (!$update) {
        echo $this->response(false, 500, 'failed update');
    }
    echo $this->response(true, 200, 'success update');
}

function list_komponen()
{
    $request = Services::request();
    $datatable = new KomponenDatatable($request);

    if ($request->getMethod(true) === 'POST') {
        $lists = $datatable->getDatatables();
        $data = [];
        $no = $request->getPost('start');

        // panggil helper
        helper('Rupiah');

        foreach ($lists as $ls) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $ls->pengguna;
            $row[] = $ls->jabatan_pegawai;
            $row[] = $ls->jumlah_jam;
            $row[] = rupiah_format($ls->gaji_pokok);
            $row[] = rupiah_format($ls->tunjangan_jabatan);
            $row[] = rupiah_format($ls->total_gaji);
            $row[] = "<button id='button-edit' data-id='$ls->id' class='btn btn-warning btn-sm'> Edit </button>
            <button id='button-delete' data-id='$ls->id' class='btn btn-danger btn-sm'> Hapus </button>";
            $data[] = $row;
        }

        $output = [
            'draw' => $request->getPost('draw'),
            'recordsTotal' => $datatable->countAll(),
            'recordsFiltered' => $datatable->countFiltered(),
            'data' => $data,
        ];

        echo json_encode($output);
    }
}

function delete_komponen()
{
    $id = $this->request->getVar('id');
    $delete = $this->model_komponen->delete($id);
    if (!$delete) {
        echo $this->response(false, 500, 'delete komponen failed');
    }
    echo $this->response(true, 200, 'delete komponen success');
}

function get_komponen()
{
    header('Content-Type: application/json');
    $id = $this->request->getVar('id');
    $data = $this->model_komponen->find($id);
    if (!$data) {
        echo $this->response(false, 500, 'internal server error');
    }
    echo $this->response(true, 200, 'success', $data);
}
function data_komponen()
{
        // json data array jabatan
        // ambil data id, nama dari table komponen
    header('Content-Type: application/json');
    $data = $this->model_komponen->data();
    echo $this->response(true, 200, 'success', $data);
}
    # komponen

function pengguna()
{
        # ini halaman pengguna
    $folder = 'modul/pengguna';
    $header = [
        'title' => 'Halaman Pengguna',
    ];
    $this->template($folder, $header);
}

private function do_upload()
{
    $uploadedFile = $this->request->getFile('profil');

    // Validasi file yang diunggah
    if ($uploadedFile->isValid() && ! $uploadedFile->hasMoved())
    {
        // Tentukan path penyimpanan file
        $path = ROOTPATH.'public/uploads/';
        
        // Pindahkan file ke path yang ditentukan
        $uploadedFile->move($path, $uploadedFile->getName());   

        # kembalikan filename
        return $uploadedFile->getName();
    }
}

function submit_pengguna()
{
    $rules = [
        'nama' => [
            'rules' => 'required[pengguna.nama]',
            'errors' => [
                'required' => '{field} pengguna harus diisi',
               
            ],
        ],

        'email' => [
            'rules' => 'required|is_unique[pengguna.email]',
            'errors' => [
                'required' => '{field} email pengguna harus diisi',
                'is_unique' => '{field} email pengguna sudah terdaftar!',
            ],
        ],
        'password' => [
            'rules' => 'required[pengguna.password]',
            'errors' => [
                'required' => '{field} password pengguna harus diisi',
                
            ],
        ],
        'status_pegawai' => [
            'rules' => 'required[pengguna.status_pegawai]',
            'errors' => [
                'required' => '{field} status pegawai pengguna harus diisi',
                
            ],
        ],
    ];

    $validation = $this->validate($rules);
    if (!$validation) {
        echo json_encode(\Config\Services::validation()->listErrors());
        return;
    }

    $jabatan        = $this->request->getPost('jabatan');
    $nama           = $this->request->getPost('nama');
    $email          = $this->request->getPost('email');
    $password       = $this->request->getPost('password');
    $status_pegawai = $this->request->getPost('status_pegawai');

    # proses upload file
    $profil = $this->do_upload();

    $create = $this->model_pengguna->save([
        'id_jabatan'        => $jabatan,
        'nama'              => $nama,
        'email'             => $email,
        'password'          => password_hash($password, PASSWORD_DEFAULT),
        'profil'            => $profil,
        'status_pegawai'    => $status_pegawai,
    ]);

    if (!$create) {
        echo $this->response(false, 500, 'internal server error');
    }

    echo $this->response(true, 200, 'success');
}

function edit_pengguna()
{
    $jabatan        = $this->request->getPost('jabatan');
    $nama           = $this->request->getPost('nama');
    $email          = $this->request->getPost('email');
    $password       = $this->request->getPost('password');
    $status_pegawai = $this->request->getPost('status_pegawai');

    # proses upload file
    $profil = $this->do_upload();

    # data untuk diupdate
    $data = [
        'id_jabatan'        => $jabatan,
        'nama'              => $nama,
        'email'             => $email,
        'password'          => password_hash($password, PASSWORD_DEFAULT),
        'profil'            => $profil,
        'status_pegawai'    => $status_pegawai,
    ];

    # kondisi where, diupdate berdasarkan apa ?
    $where = [
        'id'    =>  $this->request->getPost('id')
    ];

        # menggunakna fungsi built-in codeigniter
    $update = $this->model_pengguna->update($where, $data);
    if (!$update) {
        echo $this->response(false, 500, 'failed update');
    }
    echo $this->response(true, 200, 'success update');
}

function list_pengguna()
{
    $request = Services::request();
    $datatable = new PenggunaDatatable($request);

    if ($request->getMethod(true) === 'POST') {
        $lists = $datatable->getDatatables();
        $data = [];
        $no = $request->getPost('start');

        foreach ($lists as $list) {
            $url = base_url('uploads').'/'.esc($list->profil);
            $profil = "<img src='" . $url . "' style='width: 5rem; height: 6rem;' loading='lazy' draggable='false'>";
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $list->jabatan;
            $row[] = $list->nama;
            $row[] = $list->email;
            $row[] = $profil;
            $row[] = $list->status_pegawai;
            $row[] = "<button id='button-edit' data-id='$list->id' class='btn btn-warning btn-sm'> Edit </button>
            <button id='button-delete' data-id='$list->id' class='btn btn-danger btn-sm'> Hapus </button>";
            $data[] = $row;
        }

        $output = [
            'draw' => $request->getPost('draw'),
            'recordsTotal' => $datatable->countAll(),
            'recordsFiltered' => $datatable->countFiltered(),
            'data' => $data,
        ];

        echo json_encode($output);
    }
}

function delete_pengguna()
{
    $id = $this->request->getVar('id');
    $delete = $this->model_pengguna->delete($id);
    if (!$delete) {
        echo $this->response(false, 500, 'delete pengguna failed');
    }
    echo $this->response(true, 200, 'delete pengguna success');
}

function get_pengguna()
{
    header('Content-Type: application/json');
    $id = $this->request->getVar('id');
    $data = $this->model_pengguna->find($id);
    if (!$data) {
        echo $this->response(false, 500, 'internal server error');
    }
    echo $this->response(true, 200, 'success', $data);
}
function data_pengguna()
{
        // json data array jabatan
        // ambil data id, nama dari table komponen
    header('Content-Type: application/json');
    $data = $this->model_pengguna->data();
    echo $this->response(true, 200, 'success', $data);
}
    # pengguna

function transaksi()
{
        # ini halaman komponen gaji
    $folder = 'modul/transaksi';
    $header = [
        'title' => 'Halaman Transaksi Gaji',
    ];
    $this->template($folder, $header);
}

function submit_transaksi()
    {
        $rules = [
            'tanggal_transaksi' => [
                'label' => 'tanggal transaksi pegawai',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field}  jabatan pegawai harus diisi',
                ],
            ],
            
            'jumlah_transaksi' => [
                'label' => 'jumlah transaksi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} jumlah transaksi harus diisi',
                ],
            ],
             'keterangan_transaksi' => [
                'label' => 'gaji_pokok',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} keterangan transaksi harus diisi',
                ],
            ],

        ];

       // $validation = $this->validate($rules);
        $validation = \Config\Services::validation()->setRules($rules);
        if (!$validation->withRequest($this->request)->run()) {
           // Form tidak valid, tampilkan halaman form dengan error message
           $response['errors'] = $validation->getErrors();
           echo $this->response(false, 400, $response);
           return;
       }

       $pengguna                = $this->request->getPost('pengguna');
       $tanggal_transaksi       = $this->request->getPost('tanggal_transaksi');
       $jumlah_transaksi        = $this->request->getPost('jumlah_transaksi');
       $keterangan_transaksi    = $this->request->getPost('keterangan_transaksi');

       $create = $this->model_transaksi->save([
        'tanggal_transaksi'     => $tanggal_transaksi,
        'id_pengguna'           => $pengguna,
        'jumlah_transaksi'      => $jumlah_transaksi,
        'keterangan_transaksi'  => $keterangan_transaksi,
       
    ]);
       if (!$create) {
        echo $this->response(false, 500, 'internal server error');
    }

    $create_trx_midtrans = $this->create_transaction();
    if ($create_trx_midtrans) {
        echo $this->response(true, 200, 'success. Nilai pengguna: '.$pengguna, $create_trx_midtrans);
    }
}

function edit_transaksi()
{
        # data untuk diupdate
    $data = [
        "tanggal_transaksi"     =>  $this->request->getPost('tanggal_transaksi'),
        "jumlah_transaksi"      =>  $this->request->getPost('jumlah_transaksi'),
        "keterangan_transaksi"  =>  $this->request->getPost('keterangan_transaksi'),
        
    ];

        # kondisi where, diupdate berdasarkan apa ?
    $where = [
        'id'    =>  $this->request->getPost('id')
    ];

        # menggunakna fungsi built-in codeigniter
    $update = $this->model_transaksi->update($where, $data);
    if (!$update) {
        echo $this->response(false, 500, 'failed update');
    }
    echo $this->response(true, 200, 'success update');
}
function list_transaksi()
{
    $request = Services::request();
    $datatable = new TransaksiDatatable($request);

    if ($request->getMethod(true) === 'POST') {
        $lists = $datatable->getDatatables();
        $data = [];
        $no = $request->getPost('start');


        foreach ($lists as $ls) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $ls->pengguna;
            $row[] = $ls->tanggal_transaksi;
            $row[] = $ls->jumlah_transaksi;
            $row[] = $ls->keterangan_transaksi;
            $row[] = "<button id='button-edit' data-id='$ls->id' class='btn btn-warning btn-sm'> Edit </button>
            <button id='button-delete' data-id='$ls->id' class='btn btn-danger btn-sm'> Hapus </button>";
            $data[] = $row;
        }

        $output = [
            'draw' => $request->getPost('draw'),
            'recordsTotal' => $datatable->countAll(),
            'recordsFiltered' => $datatable->countFiltered(),
            'data' => $data,
        ];

        echo json_encode($output);
    }
}

function delete_transaksi()
{
    $id = $this->request->getVar('id');
    $delete = $this->model_transaksi->delete($id);
    if (!$delete) {
        echo $this->response(false, 500, 'delete transaksi failed');
    }
    echo $this->response(true, 200, 'delete transaksi success');
}

function get_transaksi()
{
    header('Content-Type: application/json');
    $id = $this->request->getVar('id');
    $data = $this->model_transaksi->find($id);
    if (!$data) {
        echo $this->response(false, 500, 'internal server error');
    }
    echo $this->response(true, 200, 'success', $data);
}
function data_transaksi()
{
        // json data array jabatan
        // ambil data id, nama dari table komponen
    header('Content-Type: application/json');
    $data = $this->model_transaksi->data();
    echo $this->response(true, 200, 'success', $data);
}
    # transaksi

function laporan()
{
        # ini halaman laporan
    $folder = 'modul/laporan';
    $header = [
        'title' => 'Halaman Laporan',
    ];
    $this->template($folder, $header);
}

function list_laporan()
{
    $request = Services::request();
    $datatable = new LaporanDatatable($request);

    if ($request->getMethod(true) === 'POST') {
        $lists = $datatable->getDatatables();
        $data = [];
        $no = $request->getPost('start');

        // panggil helper
        helper('Rupiah');

        foreach ($lists as $ls) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $ls->jabatan;
            $row[] = $ls->pengguna;
            $row[] = $ls->jumlah_jam;
            $row[] = rupiah_format($ls->gaji_pokok);
            $row[] = rupiah_format($ls->tunjangan_jabatan);
            $row[] = rupiah_format($ls->total_gaji);
            // $row[] = $ls->tanggal_transaksi;
            // $row[] = $ls->jumlah_transaksi;
            // $row[] = $ls->keterangan_transaksi;
            //$row[] = $ls->jabatan_pegawai;
            //$row[] = $ls->jumlah_jam;
            //$row[] = $ls->gaji_pokok;
            $data[] = $row;
        }

        $output = [
            'draw' => $request->getPost('draw'),
            'recordsTotal' => $datatable->countAll(),
            'recordsFiltered' => $datatable->countFiltered(),
            'data' => $data,
        ];

        echo json_encode($output);
    }
}

function get_laporan()
{
    header('Content-Type: application/json');
    $id = $this->request->getVar('id');
    $data = $this->model_laporan->find($id);
    if (!$data) {
        echo $this->response(false, 500, 'internal server error');
    }
    echo $this->response(true, 200, 'success', $data);
}
function data_laporan()
{
        // json data array jabatan
        // ambil data id, nama dari table komponen
    header('Content-Type: application/json');
    $data = $this->model_laporan->data();
    echo $this->response(true, 200, 'success', $data);
}
}

