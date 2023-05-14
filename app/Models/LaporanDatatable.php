<?php

namespace App\Models;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Model;

class LaporanDatatable extends Model
{
    protected $table = 'komponen';
    protected $column_order = [
        'k.id',
        'j.nama as jabatan',
        'p.nama as pengguna',
        'k.jumlah_jam',
        'k.gaji_pokok',
        'k.tunjangan_jabatan',
        'k.total_gaji',
    ];
    protected $column_search = [
        'k.id',
        'j.nama as jabatan',
        'p.nama as pengguna',
        'k.jumlah_jam',
        'k.gaji_pokok',
        'k.tunjangan_jabatan',
        'k.total_gaji',
    ];
    protected $order = ['id' => 'DESC'];
    protected $request;
    protected $db;
    protected $dt;

    public function __construct(RequestInterface $request)
    {
        parent::__construct();
        $this->db = db_connect();
        $this->request = $request;
        $this->dt = $this->db->table('pengguna p')
        ->select('
            p.id, 
            j.nama as jabatan, 
            p.nama as pengguna, 
            k.jumlah_jam, 
            k.gaji_pokok, 
            k.tunjangan_jabatan, 
            k.total_gaji'
        )
        ->join('jabatan j', 'p.id_jabatan = j.id')
        ->join('komponen k', 'k.id_pengguna = p.id');
    }

    private function getDatatablesQuery()
    {
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($this->request->getPost('search')['value']) {
                if ($i === 0) {
                    $this->dt->groupStart();
                    $this->dt->like($item, $this->request->getPost('search')['value']);
                } else {
                    $this->dt->orLike($item, $this->request->getPost('search')['value']);
                }
                if (count($this->column_search) - 1 == $i)
                    $this->dt->groupEnd();
            }
            $i++;
        }

        if ($this->request->getPost('order')) {
            $this->dt->orderBy($this->column_order[$this->request->getPost('order')['0']['column']], $this->request->getPost('order')['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->dt->orderBy(key($order), $order[key($order)]);
        }
    }

    public function getDatatables()
    {
        $this->getDatatablesQuery();
        if ($this->request->getPost('length') != -1)
            $this->dt->limit($this->request->getPost('length'), $this->request->getPost('start'));
        $query = $this->dt->get();
        return $query->getResult();
    }

    public function countFiltered()
    {
        $this->getDatatablesQuery();
        return $this->dt->countAllResults();
    }

    public function countAll()
    {
        $tbl_storage = $this->db->table($this->table);
        return $tbl_storage->countAllResults();
    }
}