<?php

class C_Message extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('general/M_General', 'general');
        $this->load->model('message/M_Message', 'message');
        $this->load->library('Nexmolib', 'nexmolib');
        if(!$this->general_library->isNotMenu()){
            redirect('logout');
        };
    }

    public function individuMessage(){
        $data['list_jenis_pesan'] = $this->general->getAllWithOrder('m_jenis_pesan', 'jenis_pesan', 'asc');
        render('message/V_SendIndividuMessage', '', '', $data);
    }

    public function sendIndividuMessage(){
        $data = $this->input->post();
        // dd($data);
        $sendMessage = $this->nexmolib->sendMessage('POST', $data);

        $jenisPesan = explode(';', $data['id_m_jenis_pesan']);

        $rsp = json_decode($sendMessage['response'], true);
        $message = $sendMessage['message'];
        $code = $rsp['messages'][0]['status'];
        if($code != '0'){
            $message = $rsp['messages'][0]['error-text'];
        }

        $insert_data = null;
        $insert_data['id_m_jenis_pesan'] = $jenisPesan[0];
        $insert_data['jenis_pesan'] = $jenisPesan[1];
        $insert_data['nomor_tujuan'] = $data['nomor_tujuan'];
        $insert_data['isi_pesan'] = $data['isi_pesan'];
        $insert_data['created_by'] = $this->general_library->getId();
        $insert_data['request'] = $sendMessage['request'];
        $insert_data['response'] = $sendMessage['response'];
        $insert_data['response_message'] = $message;

        $this->message->insert('t_message', $insert_data);

        echo json_encode(['code' => $code, 'message' => $message]);
    }

    public function bulkMessage(){
        $data['list_jenis_pesan'] = $this->general->getAllWithOrder('m_jenis_pesan', 'jenis_pesan', 'asc');
        render('message/V_SendBulkMessage', '', '', $data);
    }

    public function sendBulkMessage(){
        $data = $this->input->post();
        echo json_encode($this->message->sendBulkMessage($data));
    }

    public function checkIfAllDoneBulkMessage($id){
        $message = $this->general->getOne('t_message', 'id', $id);
        echo json_encode($message);
    }

    public function progressSendBulkMessage($id){
        $data['message_detail'] = $this->general->get('t_message_detail', 'id_t_message', $id);
        $this->message->progressSendBulkMessage($id);
        $this->load->view('message/V_ProgressSendBulkMessage', $data);
    }
}
