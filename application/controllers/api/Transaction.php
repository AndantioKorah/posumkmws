<?php

use chriskacerguis\RestServer\RestController;

class Transaction extends RestController 
{
    public $response;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('general/M_General', 'm_general');
        $this->load->model('transaction/M_Transaction', 'trx');
    }

    public function createTransaction_post(){
        $res = $this->general_library->validateParam(['data', 'nama', 'tanggal_transaksi'], 'POST', 0);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $user = $this->m_user->checkUserCredentials($req);
            $result = $this->trx->createTransaction($req, $user);
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

}
