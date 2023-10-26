<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Telegramlib extends CI_Model{

  protected $telelib;

  public function __construct()
  {
      $this->telelib = &get_instance();
      $this->telelib->load->model('general/M_General', 'general');
  }

  public function hashTelegram()
  {
      $token = "6512973023:AAGdBTzhV9cN_0Sh652TsHWNEE2BxQKnd0E";
      $url = "https://api.telegram.org/bot$token/";
      return [
          'token' => $token,
          'url' => $url
      ];
  }

  function webhookCapture() {
    $webhookContent = '';

    $webhook = fopen('php://input' , 'rb');
    
    while (!feof($webhook)) {
        $webhookContent .= fread($webhook, 4096);
    }

    fclose($webhook);

    error_log($webhookContent);

    return json_decode($webhookContent);
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

  public function send_curl_exec($method_telegram, $send_to, $message)
  {
    $url = $this->hashTelegram()['url'];
    $parameter = null;

    if($method_telegram == 'sendMessage'){
        $url = $url.$method_telegram;
        $parameter = [
          'chat_id' => $send_to,
          'text' => $message,
          'parse_mode' => "html"
        ];
    }
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, count($parameter));
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameter));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($curl);
    $message = null;
    if(!$result){
        $message = curl_error($curl);
    }
    curl_close($curl);
    $this->general->insert('t_mm_log_webhook', [
      'request' => json_encode($result),
      'response' => json_encode($message)
    ]);
    
    return ['result' => $result, 'message' => $message];
  }
}

