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
            $user = $this->user->loginWs($req);
            if($user['code'] == 200){
                if($user['data']['logo']){
                    $user['data']['logo'] = imageToBase64(URI_UPLOAD_LOGO_MERCHANT.'logo_merchant/'.$user['data']['logo']);
                }
                $res['data'] = $user['data'];
                $res['code'] = 200;
            } else {
                $res['code'] = $user['code'];
                $res['message'] = $user['message'];
            }
            $this->response($res, $res['code']);
        }
    }

}
