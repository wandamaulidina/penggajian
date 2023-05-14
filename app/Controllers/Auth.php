<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AuthModel;

class Auth extends BaseController
{
    public function __construct()
    {
        $this->auth = new AuthModel();
    }

    public function index()
    {
        $data = [
            'judul' => 'Login',
        ];
        return view('v_login', $data);
    }

    public function process_login()
    {
        if ($this->validate([
           'email' => [
            'label' => 'email',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} Tidak Boleh Kosong',
            ]
        ],
        'password' => [
            'label' => 'Password',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} Tidak Boleh Kosong',
            ]
        ],
    ])) {

            $email      = $this->request->getPost('email'); # tambahin validasi email
            $password   = $this->request->getPost('password');

            # lempar email dan password, ke model auth, fungsi proses_login
            $verify = $this->auth->process_login($email, $password);
            if (!$verify) {
                # jika verify nilainya false (salah)
                session()->setFlashdata('pesan', 'Email Atau Password Salah');
                return redirect()->to(base_url());
            }

            # jika verify nilainya true (benar)
            # set session login
            session()->set([
                'nama'      =>  $verify->nama,
                'email'     =>  $verify->email,
                'jabatan'   =>  $verify->jabatan,
                'profil'    =>  base_url('uploads').'/'.$verify->profil,
                'is_login'  =>  true,
            ]);
            return redirect()->to('/dashboard');
        }
    }
    public function process_logout()
    {
        session()->destroy();
        return redirect()->to(base_url());
    }
}