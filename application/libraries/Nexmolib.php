<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nexmolib extends CI_Model{

    public $api_key;
    public $api_secret;
    public $base_url;
    public $from;

    public function __construct(){
        $this->api_key = "b9e13f92";
        $this->api_secret = "d9b1754f4da78a5a";
        $this->base_url = "https://rest.nexmo.com/";
        $this->from = "BKPSDM MDO";
    }

    public function hashTelegram(){
        $api_key = "b9e13f92";
        $api_secret = "d9b1754f4da78a5a";
        $url = "https://rest.nexmo.com/";
        return [
            'api_key' => $api_key,
            'api_secret' => $api_secret,
            'url' => $url
        ];
    }

  
    public function xrequest($url, $hashsignature, $uid, $timestmp)
    {
        $session = curl_init($url);
        $arrheader =  array(
            'X-Cons-ID: '.$uid,
            'X-Timestamp: '.$timestmp,
            'X-Signature: '.$hashsignature,
            'Accept: application/json'
        );
        curl_setopt($session, CURLOPT_HTTPHEADER, $arrheader);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE); 
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($session, CURLOPT_SSL_VERIFYHOST, FALSE);


        if (curl_exec($session) === false)
            {
            $result = curl_error($session);
            }
            else
            {
            $result = curl_exec($session);
            }

        //$response = curl_exec($session);
        return $result;
    }

    public function sendMessage($method, $data = [])
    {   
        $to = $data['nomor_tujuan'];

        if($to[0] == '0'){
            $to = "+62".substr($to,1,strlen($to));
        } else if($to[0] != '+'){
            $to = "+".$to;
        }
        // else {
        //     return ['result' => null, 'message' => "Nomor tidak sesuai format"];
        // }

        $data_body  =   "api_key=".$this->api_key.
                        "&api_secret=".$this->api_secret.
                        "&from=".$this->from.
                        "&to=".$to.
                        "&text=".urlencode($data['isi_pesan']);

        // dd($data_body);

        $session = curl_init();

        $header =  array(
            'Content-Type: application/x-www-form-urlencoded'
        );

        curl_setopt($session, CURLOPT_HTTPHEADER, $header);
        curl_setopt($session, CURLOPT_URL, $this->base_url."sms/json");
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($session, CURLOPT_POSTFIELDS, $data_body);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($session, CURLOPT_CONNECTTIMEOUT, 100);
        curl_setopt($session, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
        
        $result = curl_exec($session);

        $message = "OK";
        if(!$result){
            $message = curl_error($session);
        }
        curl_close($session);
        
        return ['request' => $data_body, 'response' => $result, 'message' => $message];
    }
}

