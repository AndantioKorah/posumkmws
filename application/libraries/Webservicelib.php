<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webservicelib extends CI_Model{

    protected $nikita;
    public $userLoggedIn;
    public $params;
    public $bios_serial_num;

    public function __construct(){
        $this->nikita = &get_instance();
        if($this->nikita->session->userdata('user_logged_in')){
            $this->userLoggedIn = $this->nikita->session->userdata('user_logged_in')[0];
        }
        $this->params = $this->nikita->session->userdata('params');
        $this->nikita->load->model('general/M_General', 'm_general');
        date_default_timezone_set("Asia/Singapore");
    }

    public function hashWebservice(){
        $url = "http://ec2-13-212-212-155.ap-southeast-1.compute.amazonaws.com/";
    //   $url = base_url();
        return $url;
    }

    
    public function xrequest($url, $method, $data){
        $completeUrl = $this->hashWebservice().$url;
        $session = curl_init($completeUrl);
        $arrheader =  array(
            'Accept: application/json',
            'x-token: '.encrypt('nikita', $data['kode_merchant'])
        );
        curl_setopt($session, CURLOPT_HTTPHEADER, $arrheader);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE); 
        curl_setopt($session, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($session, CURLOPT_POSTFIELDS, json_encode($data)); 

        $result = curl_exec($session);

        $message = null;
        if(!$result){
            $message = curl_error($session);
        } else {
            $result = json_decode($result, true);
        }
        curl_close($session);

        $res['result'] = $result;
        $res['message'] = $message;

        $log['url'] = $completeUrl;
        $log['request'] = json_encode($data);
        $log['response'] = json_encode($res);
        $log['username'] = $data['username'];
        $log['ip_address'] = get_client_ip();
        $this->nikita->m_general->insert('t_ws_log', $log);

        return $res;
    }
}

