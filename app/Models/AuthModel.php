<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
	public function process_login(string $email, string $password)
	{
		if (empty($email) || empty($password)) {
			return false; # email atau password tidak ada di parameter
    	}
    	$pengguna = $this->db->table('pengguna p')
    						->select('p.id, p.nama, p.email, p.password, p.profil, j.nama as jabatan')
    						->join('jabatan j', 'p.id_jabatan = j.id')
    						->where('p.email', $email)
    						->get()
    						->getRow();

    	if (!$pengguna) {
    		return false; # akun tidak ditemukan
    	}

    	if (password_verify($password, $pengguna->password)) {
    		return $pengguna;
    	} else {
    		return false; # email atau password salah
    	}
    }
}