<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Penggajian | <?= $judul ?></title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
        <link rel="stylesheet" href="<?= base_url('lte/plugins/fontawesome-free/css/all.min.css')?>" />
        <link rel="stylesheet" href="<?= base_url('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')?>" />
        <link rel="stylesheet" href="<?= base_url('lte/dist/css/adminlte.min.css')?>" />
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="card card-outline card-info">
                <div class="card-body">
                    <p class="login-box-msg">Silahkan Login</p>

                    <?php
                    session();
                    $validasi = \Config\Services::Validation();
                    if (session()->get('pesan')) { ?>
                      <div class="alert alert-danger">
                        <?php echo session()->get('pesan'); ?>
                      </div>
                    <?php } ?>
                    <form id="form-login" action="<?php echo base_url('auth/login') ?>" method="POST">
                        <div class="form-group">
                            <label>Email</label>
                            <input name="email" type="email" class="form-control" placeholder="Masukkan Email" />
                            <p class="text-danger"><?= $validasi->getError('email') ?></p>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input name="password" type="password" class="form-control" placeholder="Masukkan Password" />
                            <p class="text-danger"><?= $validasi->getError('password') ?></p>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> Login</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <script src="<?php echo base_url('lte/plugins/jquery/jquery.min.js') ?>"></script>
            <script src="<?php echo base_url('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
            <script src="<?php echo base_url('lte/dist/js/adminlte.min.js') ?>"></script>
        </div>
    </body>
</html>
