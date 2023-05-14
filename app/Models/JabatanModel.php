<?php

namespace App\Models;

use CodeIgniter\Model;

class JabatanModel extends Model
{
    protected $table      = 'jabatan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'id', 
        'nama',
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
    	return $this->db->query("SELECT id,nama FROM jabatan")->getResult();
    }
}