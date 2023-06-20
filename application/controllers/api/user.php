<?php

use chriskacerguis\RestServer\RestController;

class User extends RestController 
{
    public $response;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('general/M_General', 'm_general');
        $this->load->model('user/M_User', 'user');
    }

    public function login_post(){
        $res = $this->general_library->validateParam([], 'POST', 1);
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

    public function logout_post(){
        $res = $this->general_library->validateParam([], 'POST', 2);
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $user = $this->user->logoutWs($req);
            if($user['code'] == 200){
                $res['data'] = null;
                $res['status'] = true;
                $res['message'] = 'Logout berhasil';
            } else {
                $res['code'] = $user['code'];
                $res['message'] = $user['message'];
            }
            $this->response($res, $res['code']);
        }
    }

    public function changePassword_post(){
        $res = $this->general_library->validateParam(['old_password', 'new_password', 'confirm_new_password',], 'POST');
        if($res['code'] != 200){
            $this->response($res, $res['code']);
        } else {
            $req = $this->input->post();
            $user = $this->m_user->checkUserCredentials($req);
            if($user){
                $old_password = $this->general_library->encrypt($req['username'], $req['old_password']);
                $new_password = $this->general_library->encrypt($req['username'], $req['new_password']);
                $confirm_new_password = $this->general_library->encrypt($req['username'], $req['confirm_new_password']);
                if($new_password != $old_password){
                    if($user['password'] == $old_password){ //cek jika password lama sama
                        if($new_password == $confirm_new_password){ //cek jika password baru = konfirmasi passsword baru
                            if(strlen($req['new_password']) >= 6){ //cek panjang password
                                $change = $this->m_user->changePasswordWs($req);
                                if($change['code'] == 0){ //cek jika ada error dari database
                                    $res['code'] = 200;
                                    $res['status'] = true;
                                    $res['data'] = $change['data'];
                                    $res['message'] = $change['message'];
                                } else {
                                    $res['code'] = 500;
                                    $res['status'] = false;
                                    $res['data'] = null;
                                    $res['message'] = $change['message'];
                                }
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
                    $res['code'] = 400;
                    $res['status'] = false;
                    $res['message'] = "Password Baru sama dengan Password Lama";
                }
            } else {
                $res['code'] = 404;
                $res['message'] = "User tidak ditemukan";
            }
            $this->response($res, $res['code']);
        }
    }

}
