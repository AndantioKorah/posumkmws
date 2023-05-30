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

    public function changePassword_post(){
        $res = $this->general_library->validateParam(['old_password', 'new_password', 'confirm_new_password'], 'POST');
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $user = $this->m_user->checkUserCredentials($req);
            if($user){
                $old_password = $this->general_library->encrypt($req['username'], $req['old_password']);
                $new_password = $this->general_library->encrypt($req['username'], $req['new_password']);
                $confirm_new_password = $this->general_library->encrypt($req['username'], $req['confirm_new_password']);

                if($user['password'] == $old_password){
                    if($new_password == $confirm_new_password){
                        if(strlen($req['new_password']) >= 6){
                            $res['code'] = 200;
                            $res['status'] = true;
                            $res['data'] = $user;
                            $res['message'] = "Password Berhasil diubah";
                            // if($user['logo']){
                            //     $user['logo'] = imageToBase64(URI_UPLOAD_LOGO_MERCHANT.'logo_merchant/'.$user['logo']);
                            // }
                        } else {
                            $res['code'] = 400;
                            $res['status'] = false;
                            $res['message'] = "Password harus lebih dari 6 karakter";
                        }
                    } else {
                        $res['code'] = 400;
                        $res['status'] = false;
                        $res['message'] = "Password Baru dan Konfirmasi Password Baru tidak sama";    
                    }
                } else {
                    $res['code'] = 400;
                    $res['status'] = false;
                    $res['message'] = "Password Lama salah";
                }
            } else {
                $res['code'] = 404;
                $res['message'] = "User tidak ditemukan";
            }
            $this->response($res, $res['code']);
        }
    }

}
