<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
//$routes->setAutoRoutes(true);

// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

# halaman yang pertama kali diload
$routes->get('/', 'Auth::index');
$routes->post('/auth/login', 'Auth::process_login');
$routes->get('/auth/logout', 'Auth::process_logout');
$routes->get('/coba', 'Coba::index');

//$routes->post('/login/process', 'Login::proccess');
//$routes->get('/logout/login', 'logout:login');
//CHARTS
$routes->get('/charts/index', 'Backend::Charts');
//JABATAN
$routes->get('/dashboard', 'Backend::index');
$routes->get('/master/jabatan', 'Backend::jabatan');
$routes->post('/master/jabatan/submit', 'Backend::submit_jabatan');
$routes->post('/master/jabatan/edit', 'Backend::edit_jabatan');
$routes->post('/master/jabatan/list', 'Backend::list_jabatan');
$routes->post('/master/jabatan/delete', 'Backend::delete_jabatan');
$routes->post('/master/jabatan/get', 'Backend::get_jabatan');
$routes->get('/master/jabatan/data', 'Backend::data_jabatan');
//PENGGUNA
$routes->get('/master/pengguna', 'Backend::pengguna');
$routes->post('/master/pengguna/submit', 'Backend::submit_pengguna');
$routes->post('/master/pengguna/edit', 'Backend::edit_pengguna');
$routes->post('/master/pengguna/list', 'Backend::list_pengguna');
$routes->post('/master/pengguna/delete', 'Backend::delete_pengguna');
$routes->post('/master/pengguna/get', 'Backend::get_pengguna');
$routes->get('/master/pengguna/data', 'Backend::data_pengguna');
//KOMPONEN
$routes->get('/penggajian/komponen', 'Backend::komponen');
$routes->post('/penggajian/komponen/submit', 'Backend::submit_komponen');
$routes->post('/penggajian/komponen/edit', 'Backend::edit_komponen');
$routes->post('/penggajian/komponen/list', 'Backend::list_komponen');
$routes->post('/penggajian/komponen/delete', 'Backend::delete_komponen');
$routes->post('/penggajian/komponen/get', 'Backend::get_komponen');
$routes->get('/penggajian/komponen/data', 'Backend::data_komponen');
$routes->post('/penggajian/komponen/detail', 'Backend::detail_komponen');
//TRANSAKSI
$routes->get('/penggajian/transaksi', 'Backend::transaksi');
$routes->post('/penggajian/transaksi/submit', 'Backend::submit_transaksi');
$routes->post('/penggajian/transaksi/edit', 'Backend::edit_transaksi');
$routes->post('/penggajian/transaksi/list', 'Backend::list_transaksi');
$routes->post('/penggajian/transaksi/delete', 'Backend::delete_transaksi');
$routes->post('/penggajian/transaksi/get', 'Backend::get_transaksi');
$routes->get('/penggajian/transaksi/data', 'Backend::data_transaksi');
//LAPORAN
$routes->get('/laporan/laporan', 'Backend::laporan');
$routes->post('/laporan/submit', 'Backend::submit_laporan');
$routes->post('/laporan/edit', 'Backend::edit_laporan');
$routes->post('/laporan/list', 'Backend::list_laporan');
$routes->post('/laporan/delete', 'Backend::delete_laporan');
$routes->post('/laporan/get', 'Backend::get_laporan');
$routes->get('/laporan/data', 'Backend::data_laporan');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
