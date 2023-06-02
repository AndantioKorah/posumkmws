<?php

use chriskacerguis\RestServer\RestController;

class user extends RestController 
{
    public $res;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('general/M_General', 'm_general');
        $this->load->model('user/M_User', 'user');
        $this->res = ['code' => 200, 'status' => false, 'message' => null, 'data' => null];
    }

    public function menu_get(){
        $this->res = $this->general_library->validateParam(['id_m_merchant'], 'GET');
        if($this->res['code'] == 200){
            
        }
        $this->response($this->res, $this->res['code']);
    }
}
