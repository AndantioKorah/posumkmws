<?php

class C_Master extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('general/M_General', 'general');
        $this->load->model('master/M_Master', 'master');
        if(!$this->general_library->isNotMenu()){
            redirect('logout');
        };
    }

    public function masterMerchant(){
        render('master/V_MasterMerchant', '', '', null);
    }

    public function createMasterMerchant(){
        $photo = $_FILES['logo_merchant']['name'];
        $upload = $this->general_library->uploadLogoMerchant('logo_merchant','logo_merchant');
        if($upload['code'] != 0){
            $this->session->set_flashdata('message', $upload['message']);
        } else {
            $data = $this->input->post();
            $data['logo'] =  $data['data']['file_name'];
            $data['created_by'] = $this->general_library->getId();
            $this->master->insert('m_merchant', $data);
        }
        redirect('master/merchant');
    }

    public function loadAllMerchant(){
        $data['list_jenis_pesan'] = $this->general->getAllWithOrder('m_merchant');
        $this->load->view('master/V_MasterMerchantItem', $data);
    }

    public function deleteMerchant($id){
        $this->general->delete('id', $id, 'm_merchant');
    }
}
