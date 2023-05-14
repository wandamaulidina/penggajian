<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<aside class="main-sidebar sidebar-light-info elevation-4">
    <a href="dashboard" class="brand-link" style="text-decoration: none;">
        <img src="lte/dist/img/logo.png" loading="lazy" draggable="false" alt="logo" class="brand-image img-circle elevation-3" style="opacity: 0.8;" />
        <span class="brand-text font-weight-light">Penggajian</span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo session()->get('profil') ?>" class="img-circle elevation-2" alt="profil untuk - <?php echo session()->get('pengguna');?>" />
            </div>
            <div class="info">
                <a href="#" class="d-block" style="text-decoration: none;">
                    <?php echo session()->get('pengguna');?>
                    <?php echo session()->get('nama'); ?>
                </a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php 

                    $jabatan = session()->get('jabatan');
                    if ($jabatan === "Tata Usaha") { ?>
                        <!-- ini tata usaha -->
                        <?php $uri = service('uri'); ?>
                        <li class="nav-item<?php echo ($uri->getSegment(1) == 'data-master') ? 'menu-open' : ''; ?>">
                            <a href="<?= site_url('data-master') ?>" class="nav-link <?php echo ($uri->getSegment(1) == 'data-master') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Data Master
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php $uri = service('uri'); ?>
                                <!-- getSegment(1) yang didapet adalah master, berarti getSegment(2) untuk mendapatkan jabatan -->
                                <li class="nav-item<?php echo ($uri->getSegment(2) == 'jabatan') ? 'menu-open' : ''; ?>">
                                    <a href="<?= site_url('master/jabatan') ?>" class="nav-link <?php echo ($uri->getSegment(2) == 'jabatan') ? 'active' : ''; ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data Jabatan</p>
                                    </a>
                                </li>
                                <li class="nav-item<?php echo ($uri->getSegment(2) == 'pengguna') ? 'menu-open' : ''; ?>">
                                    <a href="<?= site_url('master/pengguna') ?>" class="nav-link <?php echo ($uri->getSegment(2) == 'pengguna') ? 'active' : ''; ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data Pengguna</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php $uri = service('uri'); ?>
                        <li class="nav-item<?php echo ($uri->getSegment(2) == 'penggajian/komponen') ? 'menu-open' : ''; ?>">
                            <a href="<?= site_url('penggajian/komponen') ?>" class="nav-link <?php echo ($uri->getSegment(2) == 'penggajian/komponen') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Data Penggajian
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php $uri = service('uri'); ?>
                                <li class="nav-item<?php echo ($uri->getSegment(2) == 'komponen') ? 'menu-open' : ''; ?>">
                                    <a href="<?= site_url('penggajian/komponen') ?>" class="nav-link <?php echo ($uri->getSegment(2) == 'komponen') ? 'active' : ''; ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data Komponen Gaji</p>
                                    </a>
                                </li>
                                <?php $uri = service('uri'); ?>
                                <li class="nav-item<?php echo ($uri->getSegment(2) == 'transaksi') ? 'menu-open' : ''; ?>">
                                    <a href="<?= site_url('penggajian/transaksi') ?>" class="nav-link <?php echo ($uri->getSegment(2) == 'transaksi') ? 'active' : ''; ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data Transaksi Gaji</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- ini tata usaha -->
                    <?php } else if($jabatan === "Kepala Sekolah") { ?>
                        <!-- ini kepala sekolah -->
                        <li class="nav-item">
                            <a href="charts/index" class="nav-link" style="text-decoration: none;">
                                <i class="nav-icon fas fa-chart-area"></i>
                                <p>
                                    Dashboard
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                           
                        </li>
                        <li class="nav-item">
                            <a href="penggajian/komponen" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Data Penggajian
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php $uri = service('uri'); ?>
                                <li class="nav-item<?php echo ($uri->getSegment(2) == 'komponen') ? 'menu-open' : ''; ?>">
                                    <a href="<?= site_url('penggajian/komponen') ?>" class="nav-link <?php echo ($uri->getSegment(2) == 'komponen') ? 'active' : ''; ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data Komponen Gaji</p>
                                    </a>
                                </li>
                                <?php $uri = service('uri'); ?>
                                <li class="nav-item<?php echo ($uri->getSegment(2) == 'transaksi') ? 'menu-open' : ''; ?>">
                                    <a href="<?= site_url('penggajian/transaksi') ?>" class="nav-link <?php echo ($uri->getSegment(2) == 'transaksi') ? 'active' : ''; ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data Transaksi Gaji</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="laporan/laporan" class="nav-link">
                                <i class="nav-icon far fa-calendar-alt"></i>
                                <p>
                                    Laporan
                                    <span class="badge badge-info right">2</span>
                                </p>
                            </a>
                        </li>
                        <!-- ini kepala sekolah -->
                    <?php } ?>
                <li class="nav-item">
                    <a href="auth/logout" class="nav-link">
                        <i class="nav-icon fas fa-columns"></i>
                        <p> Keluar </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
