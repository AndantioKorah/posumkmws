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

//master
$route['api/master/menu/jenis'] = 'api/master/getAllJenisMenu';
$route['api/master/menu/kategori'] = 'api/master/getAllKategoriMenu';
$route['api/master/menu'] = 'api/master/getAllMenu';