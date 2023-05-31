<?php

class General_library
{
    protected $nikita;
    public $userLoggedIn;
    public $params;
    public $bios_serial_num;
    public $responseMessage;

    public function __construct()
    {
        $this->nikita = &get_instance();
        if($this->nikita->session->userdata('user_logged_in')){
            $this->userLoggedIn = $this->nikita->session->userdata('user_logged_in')[0];
        }
        $this->params = $this->nikita->session->userdata('params');
        $this->bios_serial_num = shell_exec('wmic bios get serialnumber 2>&1');
        date_default_timezone_set("Asia/Singapore");
        $this->nikita->load->model('general/M_General', 'm_general');
        $this->nikita->load->model('user/M_User', 'm_user');
    }

    public function getBiosSerialNum(){
        $info = $this->bios_serial_num;
        return trim($info);
    }

    function validateParam($arr, $method){
        $res = ['code' => 200,'data' => null,'message' => ""];
        array_push($arr, 'username');
        array_push($arr, 'password');
        if($method == 'POST'){
            foreach($arr as $a){
                if(!$this->nikita->input->post($a)){
                    $res['code'] = 403;
                    $res['message'] = strtoupper($a)." Tidak Boleh Kosong";
                }
            }
        } else if($method == 'DELETE'){
            foreach($arr as $a){
                if(!$this->nikita->delete($a)){
                    $res['code'] = 403;
                    $res['message'] = strtoupper($a)." Tidak Boleh Kosong";
                }
            }
        } else if($method == 'GET'){
            foreach($arr as $a){
                if(!$this->nikita->get($a)){
                    $res['code'] = 403;
                    $res['message'] = strtoupper($a)." Tidak Boleh Kosong";
                }
            }
        } else {
            $res['code'] = 403;
            $res['message'] = "Undefined Method";
        }
        return $res;
    }

    public function refreshUserLoggedInData(){
        $this->userLoggedIn = $this->nikita->session->userdata('user_logged_in')[0];
    }

    public function refreshParams(){
        $params = $this->nikita->m_general->getAll('m_parameter');
        $this->nikita->session->set_userdata('params', null);
        $this->nikita->session->set_userdata([
            'params' => $params
        ]);
        if($params){
            foreach($params as $p){
                $this->nikita->session->set_userdata([$p['parameter_name'] => $p]);
            }
        }
        $this->params = $this->nikita->session->userdata('params');
        if($this->params){
            foreach($this->params as $p){
                $this->nikita->session->set_userdata([$p['parameter_name'] => null]);
                $this->nikita->session->set_userdata([$p['parameter_name'] => $p]);
            }
        }
    }

    public function getProfilePicture(){
        $photo = 'assets/img/default-profile-picture.png';
        if($this->userLoggedIn['profile_picture']){
            $photo = 'assets/profile_picture/'.$this->userLoggedIn['profile_picture'];
        }
        return base_url().$photo;
    }

    public function getParams($parameter_name = ''){
        return $this->nikita->session->userdata($parameter_name);
        // $this->params = $this->nikita->session->userdata('params');
        // if($parameter_name != ''){
        //     foreach($this->params as $p){
        //         if($p['parameter_name'] == $parameter_name){
        //             return $p;
        //         }
        //     }
        // } else {
        //     return $this->params;
        // }
    }

    public function getListMenu($id_role = 0, $role_name = 0){
        if($id_role == 0){
            $id_role = $this->nikita->session->userdata('active_role_id');
        }
        if($role_name == 0){
            $role_name = $this->nikita->session->userdata('active_role_name');
        }
        return $this->nikita->m_user->getListMenu($id_role, $role_name);
    }

    public function getListUrl($id_role){
        if($id_role == 0){
            $id_role = $this->nikita->session->userdata('active_role_name');
        }

        return $this->nikita->m_user->getListUrl($id_role);
    }

    public function getRole(){
        // $this->userLoggedIn = $this->nikita->session->userdata('user_logged_in');
        return $this->nikita->session->userdata('active_role_name');
    }

    public function getListRole(){
        return $this->nikita->session->userdata('list_role');
    }

    public function getActiveRoleId(){
        return $this->nikita->session->userdata('active_role_id');
    }

    public function getActiveRoleName(){
        return $this->nikita->session->userdata('active_role_name');
    }

    public function getActiveRole(){
        return $this->nikita->session->userdata('active_role');
    }

    public function setActiveRole($id_role){
        $this->nikita->session->set_userdata([
            'active_role_id' => null,
            'active_role_name' => null,
            'active_role' => null
        ]);
        
        $role = $this->nikita->m_general->getOne('m_role', 'id', $id_role, 1);
        
        $this->nikita->session->set_userdata([
            'active_role_id' => $role['id'],
            'active_role_name' => $role['role_name'],
            'landing_page' => $role['landing_page'],
            'active_role' => $role
        ]);

        $this->refreshMenu();
    }
    
    public function refreshMenu(){
        $list_menu = $this->nikita->user->getListMenu($this->nikita->session->userdata('active_role_id'), $this->nikita->session->userdata('active_role_name'));
        $this->nikita->session->set_userdata('list_menu', null);
        $this->nikita->session->set_userdata([
            'list_menu' => $list_menu
        ]);
    }

    public function isNotMenu(){
        // return true;
        // logic belum jalan for ni menu
        return $this->isSessionExpired();
        // $res = 0;
        // if($this->isSessionExpired()){
        //     $current_url = substr($_SERVER["REDIRECT_QUERY_STRING"], 1, strlen($_SERVER["REDIRECT_QUERY_STRING"])-1);
        //     $list_url = $this->nikita->session->userdata('list_url');
        //     foreach($list_url as $lu){
        //         if($current_url == $lu['url']){
        //             $res = 1;
        //             break;
        //         }
        //     }
        // }
        // return $res == 0 ? false : true;
    }

    public function getDataProfilePicture(){
        return $this->userLoggedIn['profile_picture'];
    }

    public function getPassword(){
        return $this->userLoggedIn['password'];
    }

    public function isNotAppExp(){
        // $exp_app = $this->getParams('PARAM_EXP_APP');
        // if(date('Y-m-d H:i:s') <= $exp_app['parameter_value']){
        //     return true;
        // } else {
        //     return false;
        // }
        return true;
    }

    public function isNotBackDateLogin(){
        // $login_param = $this->getParams('PARAM_LAST_LOGIN');
        // if(date('Y-m-d H:i:s') >= $login_param['parameter_value']){
        //     return true;
        // } else {
        //     return false;
        // }
        return true;
    }

    public function isNotThisDevice(){
        // $param_bios = $this->getParams('PARAM_BIOS_SERIAL_NUMBER');
        // if(DEVELOPMENT_MODE == 0){
        //     $info = encrypt('nikita', trim($this->getBiosSerialNum()));
        //     if($info != trim($param_bios['parameter_value'])){
        //         return true;
        //     } else {
        //         return false;
        //     }
        // } else {
        //     return false;
        // }
        return false;
    }

    public function isProgrammer(){
        return $this->getActiveRoleName() == 'programmer';
    }

    public function isSessionExpired(){
        if(!$this->userLoggedIn){
            $this->nikita->session->set_userdata(['apps_error' => 'Sesi Anda telah habis. Silahkan Login kembali']);
            return null;
        }
        return $this->userLoggedIn;
    }

    public function isLoggedIn($exclude_role = []){
        if(!$this->userLoggedIn){
            $this->nikita->session->set_userdata(['apps_error' => 'Sesi Anda telah habis. Silahkan Login kembali']);
            return null;
        }
        if(!$this->isNotBackDateLogin()){
            $this->nikita->session->set_userdata(['apps_error' => 'Back Date detected. Make sure Your Date and Time is not less than today. If this message occur again, call '.PROGRAMMER_PHONE.'']);
            return null;
        }
        if(!$this->isNotAppExp()){
            $this->nikita->session->set_userdata(['apps_error' => 'Masa Berlaku Aplikasi Anda sudah habis']);
            return null;
        }
        if($this->isNotThisDevice()){
            $this->nikita->session->set_userdata(['apps_error' => 'Device tidak terdaftar']);
            return null;
        }
        // if(count($exclude_role) > 1 && in_array($this->getRole(), $exclude_role)){
        //     $this->nikita->session->set_userdata(['apps_error' => 'Role User tidak diizinkan untuk masuk ke menu tersebut']);
        //     return null;
        // }
        return $this->userLoggedIn;
    }

    public function getUserName(){
        // $this->userLoggedIn = $this->nikita->session->userdata('user_logged_in');
        return $this->userLoggedIn['username'];
    }

    public function getNamaUser(){
        // $this->userLoggedIn = $this->nikita->session->userdata('user_logged_in');
        return $this->userLoggedIn['nama_user'];
    }

    public function getId(){
        // $this->userLoggedIn = $this->nikita->session->userdata('user_logged_in');
        return $this->userLoggedIn['id'];
    }

    public function test(){
        return 'tiokors';
    }

    public function encrypt($username, $password)
    {
        $key = 'nikitalab';
        $userKey = substr($username, -3);
        $passKey = substr($password, -3);
        $generatedForHash = strtoupper($userKey).$username.$key.strtoupper($passKey).$password;
       
        return md5($generatedForHash);
    }

    public function uploadImage($path, $input_file_name){
        if (!file_exists(URI_UPLOAD.$path)) {
            mkdir(URI_UPLOAD.$path, 0777, true);
        }
        $file = $_FILES["$input_file_name"];
        $fileName = $this->getUserName().'_profile_pict_'.date('ymdhis').'_'.$file['name'];
        
        $_FILES[$input_file_name]['name'] = $file['name'];
        $_FILES[$input_file_name]['type'] = $file['type'];
        $_FILES[$input_file_name]['tmp_name'] = $file['tmp_name'];
        $_FILES[$input_file_name]['error'] = $file['error'];
        $_FILES[$input_file_name]['size'] = $file['size'];
        
        $config['upload_path'] = URI_UPLOAD.$path; //buat folder dengan nama assets di root folder
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = '2000';

        $this->nikita->load->library('upload', $config);

        if(!$this->nikita->upload->do_upload($input_file_name)){
            $this->nikita->upload->display_errors();
        }
        if($this->nikita->upload->error_msg){
            return ['code' => '500', 'message' => $this->nikita->upload->error_msg[0]];
        }
        $image = $this->nikita->upload->data();
     
        return ['code' => '0', 'data' => $image];
    }

    public function uploadLogoMerchant($path, $input_file_name){
        if (!file_exists(URI_UPLOAD_LOGO_MERCHANT.$path)) {
            mkdir(URI_UPLOAD_LOGO_MERCHANT.$path, 0777, true);
        }
        $file = $_FILES["$input_file_name"];
        $fileName = date('ymdhis').'_'.$file['name'];
        
        $_FILES[$input_file_name]['name'] = $file['name'];
        $_FILES[$input_file_name]['type'] = $file['type'];
        $_FILES[$input_file_name]['tmp_name'] = $file['tmp_name'];
        $_FILES[$input_file_name]['error'] = $file['error'];
        $_FILES[$input_file_name]['size'] = $file['size'];
        
        $config['upload_path'] = URI_UPLOAD_LOGO_MERCHANT.$path; //buat folder dengan nama assets di root folder
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = '2000';

        $this->nikita->load->library('upload', $config);

        if(!$this->nikita->upload->do_upload($input_file_name)){
            $this->nikita->upload->display_errors();
        }
        if($this->nikita->upload->error_msg){
            return ['code' => '500', 'message' => $this->nikita->upload->error_msg[0]];
        }
        $image = $this->nikita->upload->data();
     
        return ['code' => '0', 'data' => $image];
    }
}
?>