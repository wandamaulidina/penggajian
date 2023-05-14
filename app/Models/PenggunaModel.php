<?php

namespace App\Models;

use CodeIgniter\Model;

class PenggunaModel extends Model
{
    protected $table      = 'pengguna';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'id_jabatan',
        'nama',
        'email',
        'profil',
        'status_pegawai',
        'password',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'is_deleted', 
        'deleted_at',
        'deleted_by'];

    function read()
    {
        return $this->db->table($this->table)->get();
    }
     function data()
    {
    	// * = SEMUA FIELDS
    	return $this->db->query("SELECT id,id_jabatan,nama,email,profil,status_pegawai FROM pengguna")->getResult();
    }

}