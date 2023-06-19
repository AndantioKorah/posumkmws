<?php

use chriskacerguis\RestServer\RestController;

class master extends RestController 
{
    public $response;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('general/M_General', 'm_general');
        $this->load->model('user/M_User', 'user');
        $this->load->model('master/M_Master', 'master');
    }

    public function getAllMasterMenu_post(){
        $res = $this->general_library->validateParam(['id_m_merchant'], 'POST', 0);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $result['jenis_menu'] = $this->master->getAllJenisMenuByIdMerchant($req['id_m_merchant']);
            $result['kategori_menu'] = $this->master->getKategoriMenuByIdMerchant($req['id_m_merchant']);
            $result['menu_merchant'] = $this->master->getAllMenuMerchantByIdMerchant($req['id_m_merchant']);
            if(!$result){
                $res['code'] = 404;
                $res['message'] = 'Data Tidak Ditemukan';
                $res['data'] = null;
                $res['status'] = false;
            } else {
                $res['data'] = $result;
            }
            $this->response($res, $res['code']);
        }
    }

    public function getAllJenisMenu_post(){
        $res = $this->general_library->validateParam(['id_m_merchant'], 'POST', 0);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $result = $this->master->getAllJenisMenuByIdMerchant($req['id_m_merchant']);
            if(!$result){
                $res['code'] = 404;
                $res['message'] = 'Data Tidak Ditemukan';
                $res['data'] = null;
                $res['status'] = false;
            } else {
                $res['data'] = $result;
            }
            $this->response($res, $res['code']);
        }
    }

    public function getAllKategoriMenu_post(){
        $res = $this->general_library->validateParam(['id_m_merchant'], 'POST', 0);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $result = $this->master->getKategoriMenuByIdMerchant($req['id_m_merchant']);
            if(!$result){
                $res['code'] = 404;
                $res['message'] = 'Data Tidak Ditemukan';
                $res['data'] = null;
                $res['status'] = false;
            } else {
                $res['data'] = $result;
            }
            $this->response($res, $res['code']);
        }
    }

    public function getAllMenu_post(){
        $res = $this->general_library->validateParam(['id_m_merchant'], 'POST', 0);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $result = $this->master->getAllMenuMerchantByIdMerchant($req['id_m_merchant']);
            if(!$result){
                $res['code'] = 404;
                $res['message'] = 'Data Tidak Ditemukan';
                $res['data'] = null;
                $res['status'] = false;
            } else {
                $res['data'] = $result;
            }
            $this->response($res, $res['code']);
        }
    }

    public function editJenisMenu_post(){
        $res = $this->general_library->validateParam(['nama_jenis_menu', 'id', 'id_m_merchant'], 'POST', 0);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $user = $this->m_user->checkUserCredentialsLibrary($req);
            if($user){
                $result = $this->master->editJenisMenu($req, $user['id_m_user']);
                if($result == 1){
                    $res['code'] = 200;
                    $res['message'] = 'Data Berhasil Terupdate';
                    $res['data'] = null;
                    $res['status'] = true;
                } else {
                    $res['code'] = 500;
                    $res['message'] = 'Terjadi kesalahan saat menyimpan data';
                    $res['data'] = null;
                    $res['status'] = false;
                }
            } else {
                $res['code'] = 403;
                $res['message'] = 'Bad Credentials';
                $res['data'] = null;
                $res['status'] = false;
            }
            $this->response($res, $res['code']);
        }
    }

    public function deleteJenisMenu_post(){
        $res = $this->general_library->validateParam(['id', 'id_m_merchant'], 'POST', 0);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $user = $this->m_user->checkUserCredentialsLibrary($req);
            if($user){
                $result = $this->master->deleteJenisMenu($req, $user['id_m_user']);
                if($result == 1){
                    $res['code'] = 200;
                    $res['message'] = 'Data Berhasil Dihapus';
                    $res['data'] = null;
                    $res['status'] = true;
                } else {
                    $res['code'] = 500;
                    $res['message'] = 'Terjadi kesalahan saat menghapus data';
                    $res['data'] = null;
                    $res['status'] = false;
                }
            } else {
                $res['code'] = 403;
                $res['message'] = 'Bad Credentials';
                $res['data'] = null;
                $res['status'] = false;
            }
            $this->response($res, $res['code']);
        }
    }

    public function tambahJenisMenu_post(){
        $res = $this->general_library->validateParam(['nama_jenis_menu', 'id_m_merchant'], 'POST', 0);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $user = $this->m_user->checkUserCredentialsLibrary($req);
            if($user){
                $result = $this->master->tambahJenisMenu($req, $user['id_m_user']);
                if($result == 1){
                    $res['code'] = 201;
                    $res['message'] = 'Data Berhasil Ditambahkan';
                    $res['data'] = null;
                    $res['status'] = true;
                } else if($result == 2) {
                    $res['code'] = 500;
                    $res['message'] = 'Nama Jenis "'.$req['nama_jenis_menu'].'" sudah ada';
                    $res['data'] = null;
                    $res['status'] = false;
                } else {
                    $res['code'] = 500;
                    $res['message'] = 'Terjadi kesalahan saat menghapus data';
                    $res['data'] = null;
                    $res['status'] = false;
                }
            } else {
                $res['code'] = 403;
                $res['message'] = 'Bad Credentials';
                $res['data'] = null;
                $res['status'] = false;
            }
            $this->response($res, $res['code']);
        }
    }

    public function editKategoriMenu_post(){
        $res = $this->general_library->validateParam(['nama_kategori_menu', 'id', 'id_m_merchant', 'id_m_jenis_menu'], 'POST', 0);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $user = $this->m_user->checkUserCredentialsLibrary($req);
            if($user){
                $result = $this->master->editKategoriMenu($req, $user['id_m_user']);
                if($result == 1){
                    $res['code'] = 200;
                    $res['message'] = 'Data Berhasil Terupdate';
                    $res['data'] = null;
                    $res['status'] = true;
                } else {
                    $res['code'] = 500;
                    $res['message'] = 'Terjadi kesalahan saat menyimpan data';
                    $res['data'] = null;
                    $res['status'] = false;
                }
            } else {
                $res['code'] = 403;
                $res['message'] = 'Bad Credentials';
                $res['data'] = null;
                $res['status'] = false;
            }
            $this->response($res, $res['code']);
        }
    }

    public function deleteKategoriMenu_post(){
        $res = $this->general_library->validateParam(['id', 'id_m_merchant'], 'POST', 0);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $user = $this->m_user->checkUserCredentialsLibrary($req);
            if($user){
                $result = $this->master->deleteKategoriMenu($req, $user['id_m_user']);
                if($result == 1){
                    $res['code'] = 200;
                    $res['message'] = 'Data Berhasil Dihapus';
                    $res['data'] = null;
                    $res['status'] = true;
                } else {
                    $res['code'] = 500;
                    $res['message'] = 'Terjadi kesalahan saat menghapus data';
                    $res['data'] = null;
                    $res['status'] = false;
                }
            } else {
                $res['code'] = 403;
                $res['message'] = 'Bad Credentials';
                $res['data'] = null;
                $res['status'] = false;
            }
            $this->response($res, $res['code']);
        }
    }

    public function tambahKategoriMenu_post(){
        $res = $this->general_library->validateParam(['nama_kategori_menu', 'id_m_merchant'], 'POST', 0);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $user = $this->m_user->checkUserCredentialsLibrary($req);
            if($user){
                $result = $this->master->tambahKategoriMenu($req, $user['id_m_user']);
                if($result == 1){
                    $res['code'] = 201;
                    $res['message'] = 'Data Berhasil Ditambahkan';
                    $res['data'] = null;
                    $res['status'] = true;
                } else if($result == 2) {
                    $res['code'] = 500;
                    $res['message'] = 'Nama Kategori "'.$req['nama_kategori_menu'].'" sudah ada';
                    $res['data'] = null;
                    $res['status'] = false;
                } else {
                    $res['code'] = 500;
                    $res['message'] = 'Terjadi kesalahan saat menghapus data';
                    $res['data'] = null;
                    $res['status'] = false;
                }
            } else {
                $res['code'] = 403;
                $res['message'] = 'Bad Credentials';
                $res['data'] = null;
                $res['status'] = false;
            }
            $this->response($res, $res['code']);
        }
    }

    public function editMenuMerchant_post(){
        $res = $this->general_library->validateParam(['nama_menu_merchant', 'id', 'id_m_merchant', 'id_m_jenis_menu', 'id_m_kategori_menu', 'harga'], 'POST', 0);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $user = $this->m_user->checkUserCredentialsLibrary($req);
            if($user){
                $result = $this->master->editMenuMerchant($req, $user['id_m_user']);
                if($result == 1){
                    $res['code'] = 200;
                    $res['message'] = 'Data Berhasil Terupdate';
                    $res['data'] = null;
                    $res['status'] = true;
                } else {
                    $res['code'] = 500;
                    $res['message'] = 'Terjadi kesalahan saat menyimpan data';
                    $res['data'] = null;
                    $res['status'] = false;
                }
            } else {
                $res['code'] = 403;
                $res['message'] = 'Bad Credentials';
                $res['data'] = null;
                $res['status'] = false;
            }
            $this->response($res, $res['code']);
        }
    }

    public function deleteMenumerchant_post(){
        $res = $this->general_library->validateParam(['id', 'id_m_merchant'], 'POST', 0);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $user = $this->m_user->checkUserCredentialsLibrary($req);
            if($user){
                $result = $this->master->deleteMenumerchant($req, $user['id_m_user']);
                if($result == 1){
                    $res['code'] = 200;
                    $res['message'] = 'Data Berhasil Dihapus';
                    $res['data'] = null;
                    $res['status'] = true;
                } else {
                    $res['code'] = 500;
                    $res['message'] = 'Terjadi kesalahan saat menghapus data';
                    $res['data'] = null;
                    $res['status'] = false;
                }
            } else {
                $res['code'] = 403;
                $res['message'] = 'Bad Credentials';
                $res['data'] = null;
                $res['status'] = false;
            }
            $this->response($res, $res['code']);
        }
    }

    public function tambahMenuMerchant_post(){
        $res = $this->general_library->validateParam(['nama_menu_merchant', 'id_m_merchant', 'id_m_jenis_menu', 'id_m_kategori_menu', 'harga'], 'POST', 0);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $user = $this->m_user->checkUserCredentialsLibrary($req);
            if($user){
                $result = $this->master->tambahMenuMerchant($req, $user['id_m_user']);
                if($result == 1){
                    $res['code'] = 201;
                    $res['message'] = 'Data Berhasil Ditambahkan';
                    $res['data'] = null;
                    $res['status'] = true;
                } else if($result == 2) {
                    $res['code'] = 500;
                    $res['message'] = 'Nama Menu "'.$req['nama_menu_merchant'].'" sudah ada dengan jenis, kategori dan harga yang sama';
                    $res['data'] = null;
                    $res['status'] = false;
                } else {
                    $res['code'] = 500;
                    $res['message'] = 'Terjadi kesalahan saat menghapus data';
                    $res['data'] = null;
                    $res['status'] = false;
                }
            } else {
                $res['code'] = 403;
                $res['message'] = 'Bad Credentials';
                $res['data'] = null;
                $res['status'] = false;
            }
            $this->response($res, $res['code']);
        }
    }

}
