<?php

use chriskacerguis\RestServer\RestController;

class user extends RestController 
{
    public $response;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('general/M_General', 'm_general');
        $this->load->model('user/M_User', 'user');
    }

    public function login_post(){
        $res = $this->general_library->validateParam([], 'POST');
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $user = $this->m_user->loginWs($req);
            if($user){
                if($user['logo']){
                    $user['logo'] = imageToBase64(URI_UPLOAD_LOGO_MERCHANT.'logo_merchant/'.$user['logo']);
                }
                $res['data'] = $user;
                $res['code'] = 200;
            } else {
                $res['code'] = 404;
                $res['message'] = "User tidak ditemukan";
            }
            $this->response($res, $res['code']);
        }
    }

}
