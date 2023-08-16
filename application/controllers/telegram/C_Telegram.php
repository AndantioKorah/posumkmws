<?php

class C_Telegram extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('general/M_General', 'general');
        $this->load->model('user/M_User', 'user');
        $this->load->model('telegram/M_Telegram', 'tele');
        // if(!$this->general_library->isNotMenu()){
        //     redirect('logout');
        // };
    }

    public function webhook(){
        $input = $this->telegramlib->webhookCapture();
        $data = ($input);
        $chat_id = $data->message->chat->id;
        $text = $data->message->text;
        $respMessage = "";
        $this->tele->saveLog($data);

        if($text == '/start'){
            $respMessage = $this->registerUser($data);
        } else if(str_starts_with($text, '/startmm')){
            $user = $this->tele->checkIfUserExists($data);
            $code = 0;
            if(!$user){
                $respMessage = 'User belum terdaftar. Ketik "/start" untuk mendaftar.';
            } else {
                if($text == '/startmm'){
                    $bulan = date('m');
                    $tahun = date('Y');    
                    $respMessage = "Sesi ".$bulan.' '.$tahun;
                    $code = 0;
                } else {
                    $explode = explode("_", $text);
                    if(count($explode) == 3){
                        $respMessage = "Perintah tidak dikenal. Pastikan Bulan dan Tahun menggunakan format yang tepat. ".$explode[1].' '.$explode[2];
                        $code = 1;
                        // if(is_int(floatval($explode[1])) && is_int(floatval($explode[2]))){
                            if(strlen($explode[1]) == 2 && strlen($explode[2]) == 4){
                                $bulan = $explode[1];
                                $tahun = $explode[2];
                                $respMessage = "Sesi ".$bulan.' '.$tahun;
                                $code = 0;
                            }
                        // }
                    }
                }
                if($code == 0){
                    $this->tele->setSession($data, $bulan, $tahun);
                }
            }
        } else if($text == "/mode"){
            $session = $this->tele->getCurrentSession($data);
            if($session){
                $respMessage = 'Mode: <b>'.strtoupper($session['mode']).'</b>';
            } else {
                $respMessage = 'Belum ada sesi. Ketik "/startmm" untuk memulai sesi.';
            }
        } else if($text == "/min"){
            $session = $this->tele->getCurrentSession($data);
            if($session){
                $this->tele->changeModeSession($data, "min");
                $respMessage = 'Mode Min';
            } else {
                $respMessage = 'Belum ada sesi. Ketik "/startmm" untuk memulai sesi.';
            }
        } else if($text == "/plus"){
            $session = $this->tele->getCurrentSession($data);
            if($session){
                $this->tele->changeModeSession($data, "plus");
                $respMessage = 'Mode Plus';
            } else {
                $respMessage = 'Belum ada sesi. Ketik "/startmm" untuk memulai sesi.';
            }
        } else if($text == "/saldo") {
            $session = $this->tele->getCurrentSession($data);
            if($session){
                $rekap = $this->tele->countTransaksi($data, $session);
                $respMessage = 'Saldo saat ini <b>'.formatCurrency($rekap['total']).'</b>';
            } else {
                $respMessage = 'Belum ada sesi. Ketik "/startmm" untuk memulai sesi.';
            }
        } else if($text == "/rekap"){
            $session = $this->tele->getCurrentSession($data);
            if($session){
                $rekap = $this->tele->countTransaksi($data, $session);
                $respMessage = "Rekap Transaksi ".getNamaBulan($session['bulan'])." ".$session['tahun']."\n";
                if($rekap['list_transaksi']){
                    $no = 1;
                    foreach($rekap['list_transaksi'] as $l){
                        $jenis_transaksi = $l['jenis_transaksi'] == "plus" ? "+" : "-";
                        $respMessage .= $no++.". (".$jenis_transaksi.") ".formatCurrencyWithoutRp($l['nominal'])." ".$l['nama_transaksi']."\n";
                    }
                    $respMessage = trim($respMessage."\nPengeluaran: ".formatCurrency($rekap['pengeluaran'])."\nPemasukan: ".formatCurrency($rekap['pemasukan'])."\nSaldo Akhir: <b>".formatCurrency($rekap['total'])."</b>");
                } else {
                    $respMessage .= "Belum ada transaksi";
                }
            } else {
                $respMessage = 'Belum ada sesi. Ketik "/startmm" untuk memulai sesi.';
            }
        } else if(str_starts_with($text, "/remove")){
            $session = $this->tele->getCurrentSession($data);
            if($session){
                $explode = explode("_", $text);
                $respMessage = "Harap menggunakan format yang sudah ditentukan";
                if(count($explode) > 1){
                    if(floatval($explode[1]) > 0){
                        $delete = $this->tele->removeTransaksiByOrder($data, $session, $explode[1]);
                        if($delete){
                            $respMessage = "Transaksi <b>".$delete['nama_transaksi']." ".formatCurrencyWithoutRp($delete['nominal'])."</b> berhasil dihapus";
                        }
                    }
                }
            } else {
                $respMessage = 'Belum ada sesi. Ketik "/startmm" untuk memulai sesi.';
            }
        } else if($text == "/clear"){
            $session = $this->tele->getCurrentSession($data);
            if($session){
                $this->tele->clearTransaksi($data, $session);
                $respMessage = "Berhasil Menghapus Semua Transaksi";
            } else {
                $respMessage = 'Belum ada sesi. Ketik "/startmm" untuk memulai sesi.';
            }
        } else {
            $session = $this->tele->getCurrentSession($data);
            if($session){
                $count = explode(';', $text);
                foreach($count as $c){
                    $transaksi = null;
                    $explode = explode(' ', trim($c));
                    $i = 1;
                    $nama_transaksi = '';
                    for($i = 1; $i < count($explode); $i++){
                    $nama_transaksi = $nama_transaksi.' '.$explode[$i]; 
                    }
                    if(count($explode) >= 2){
                        $transaksi['nominal'] = $explode[0]; 
                        $transaksi['nama_transaksi'] = trim($nama_transaksi); 
                        $transaksi['jenis_transaksi'] = $session['mode']; 
                        $transaksi['chat_id'] = $session['chat_id'];
                        $transaksi['bulan'] = $session['bulan'];
                        $transaksi['tahun'] = $session['tahun'];
                    }
                    if($transaksi != null){
                        $this->tele->insert('t_mm_transaksi', $transaksi);
                        $respMessage = 'transaksi berhasil dicatat';
                    }
                }
            } else {
                $respMessage = 'Belum ada sesi. Ketik "/startmm" untuk memulai sesi.';
            }
        }

        if($respMessage != ""){
            $response = $this->telegramlib->send_curl_exec("sendMessage", $chat_id, $respMessage);
        }
    }

    public function registerUser($data){
        return $this->tele->registerUser($data);
    }

}