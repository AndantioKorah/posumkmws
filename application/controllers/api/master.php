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
        $res = $this->general_library->validateParam(['id_m_merchant'], 'POST', 1);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $result['jenis_menu'] = $this->master->getAllJenisMenuByIdMerchant($req['id_m_merchant']);
            $result['kategori_menu'] = $this->master->getKategoriMenuByIdMerchant($req['id_m_merchant']);
            $result['menu_merchant'] = $this->master->getAllMenuMerchantByIdMerchant($req['id_m_merchant']);
            if(!$result){
                $res['code'] = '404';
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
        $res = $this->general_library->validateParam(['id_m_merchant'], 'POST', 1);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $result = $this->master->getAllJenisMenuByIdMerchant($req['id_m_merchant']);
            if(!$result){
                $res['code'] = '404';
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
        $res = $this->general_library->validateParam(['id_m_merchant'], 'POST', 1);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $result = $this->master->getKategoriMenuByIdMerchant($req['id_m_merchant']);
            if(!$result){
                $res['code'] = '404';
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
        $res = $this->general_library->validateParam(['id_m_merchant'], 'POST', 1);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $result = $this->master->getAllMenuMerchantByIdMerchant($req['id_m_merchant']);
            if(!$result){
                $res['code'] = '404';
                $res['message'] = 'Data Tidak Ditemukan';
                $res['data'] = null;
                $res['status'] = false;
            } else {
                $res['data'] = $result;
            }
            $this->response($res, $res['code']);
        }
    }

}
