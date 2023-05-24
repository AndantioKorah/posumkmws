<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Telegramlib extends CI_Model{

  public function hashTelegram()
  {
      $token = "1827474004:AAH8TDfeAh8WR_iXIG-vL0CDuF0KZbwtNUk";
      $url = "https://api.telegram.org/bot$token/";
      return [
          'token' => $token,
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

  public function send_curl_exec($method, $method_telegram, $send_to, $data = [])
  {
    $url = $this->hashTelegram()['url'];

    if($method_telegram == 'sendMessage'){
        $url = $url.$method_telegram.'?chat_id='.$send_to.'&text='.$data['message'];
    }
    
    $session = curl_init();

    $header[] = "Content-Type: application/json";

    curl_setopt($session, CURLOPT_HTTPHEADER, $header);
    curl_setopt($session, CURLOPT_URL, $url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($session, CURLOPT_CONNECTTIMEOUT, 100);
    curl_setopt($session, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec($session);

    $message = null;
    if(!$result){
        $message = curl_error($session);
    }
    curl_close($session);
    
    return ['result' => $result, 'message' => $message];
  }
}

