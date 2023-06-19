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
$route['api/logout'] = 'api/user/logout';

//master
$route['api/master/menu/jenis'] = 'api/master/getAllJenisMenu';
$route['api/master/menu/jenis/edit'] = 'api/master/editJenisMenu';
$route['api/master/menu/jenis/delete'] = 'api/master/deleteJenisMenu';
$route['api/master/menu/jenis/create'] = 'api/master/tambahJenisMenu';

$route['api/master/menu/kategori'] = 'api/master/getAllKategoriMenu';
$route['api/master/menu/kategori/edit'] = 'api/master/editKategoriMenu';
$route['api/master/menu/kategori/delete'] = 'api/master/deleteKategoriMenu';
$route['api/master/menu/kategori/create'] = 'api/master/tambahKategoriMenu';

$route['api/master/merchant/menu'] = 'api/master/getAllMenu';
$route['api/master/merchant/menu/edit'] = 'api/master/getAllMenu';

$route['api/master/menu'] = 'api/master/getAllMasterMenu';