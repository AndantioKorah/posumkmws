<?php
$route['users'] = 'user/C_User/users';
$route['user/setting'] = 'user/C_User/userSetting';
$route['roles'] = 'user/C_User/roles';
$route['menu'] = 'user/C_User/menu';
$route['master/merchant'] = 'master/C_Master/masterMerchant';
$route['master/merchant/menu'] = 'master/C_Master/menuMerchant';
$route['master/jenis-menu'] = 'master/C_Master/masterJenisMenu';
$route['master/kategori-menu'] = 'master/C_Master/masterKategoriMenu';


//api
$route['api/login'] = 'api/C_User/';
$route['api/logout'] = 'api/User/logout';

//master
$route['api/master/menu/jenis'] = 'api/Master/getAllJenisMenu';
$route['api/master/menu/jenis/edit'] = 'api/Master/editJenisMenu';
$route['api/master/menu/jenis/delete'] = 'api/Master/deleteJenisMenu';
$route['api/master/menu/jenis/create'] = 'api/Master/tambahJenisMenu';

$route['api/master/menu/kategori'] = 'api/Master/getAllKategoriMenu';
$route['api/master/menu/kategori/edit'] = 'api/Master/editKategoriMenu';
$route['api/master/menu/kategori/delete'] = 'api/Master/deleteKategoriMenu';
$route['api/master/menu/kategori/create'] = 'api/Master/tambahKategoriMenu';

$route['api/master/merchant/menu'] = 'api/Master/getAllMenu';
$route['api/master/merchant/menu/edit'] = 'api/Master/editMenuMerchant';
$route['api/master/merchant/menu/delete'] = 'api/Master/deleteMenuMerchant';
$route['api/master/merchant/menu/create'] = 'api/Master/tambahMenuMerchant';

//transaction
$route['api/transaction/create'] = 'api/Transaction/createTransaction';

//payment
$route['api/payment/detail/get'] = 'api/Transaction/getPembayaranDetail';
$route['api/payment/create'] = 'api/Transaction/createPembayaran';
$route['api/payment/delete'] = 'api/Transaction/deletePembayaran';


$route['api/master/menu'] = 'api/Master/getAllMasterMenu';