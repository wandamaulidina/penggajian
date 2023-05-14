<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $table      = 'Laporan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'id_pengguna',
        'jabatan_pegawai', 
        'status_pegawai', 
        'jumlah_jam', 
        'gaji_pokok', 
        'tunjangan_jabatan', 
        'total_gaji', 
        'created_at', 
        'created_by', 
        'updated_at', 
        'updated_by', 
        'is_deleted', 
        'deleted_at',
        'deleted_by'
    ];

    function read()
    {
        return $this->db->table($this->table)->get();
    }
     function data()
    {
    	// * = SEMUA FIELDS
    	return $this->db->query("SELECT id,jabatan_pegawai,status_pegawai,jumlah_jam,gaji_pokok,tunjangan_jabatan,total_gaji FROM laporan")->getResult();
    }


}