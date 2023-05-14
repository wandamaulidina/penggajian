<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table      = 'transaksi';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'id',
        'id_pengguna', 
        'tanggal_transaksi', 
        'jumlah_transaksi',
        'keterangan_transaksi',
        'created_at',
        'updated_at', 
        ];

    function read()
    {
        return $this->db->table($this->table)->get();
    }
    function data()
    {
    	// * = SEMUA FIELDS
    	return $this->db->query("SELECT id,id_pengguna,tanggal_transaksi,jumlah_transaksi,keterangan_transaksi FROM transaksi")->getResult();
    }

}