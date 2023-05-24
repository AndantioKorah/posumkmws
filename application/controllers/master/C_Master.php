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

    public function jenisPesan(){
        render('master/V_MasterJenispesan', '', '', null);
    }

    public function createMasterJenisPesan(){
        $data = $this->input->post();
        $data['created_by'] = $this->general_library->getId();
        $this->master->insert('m_jenis_pesan', $data);
    }

    public function loadJenisPesan(){
        $data['list_jenis_pesan'] = $this->general->getAllWithOrder('m_jenis_pesan');
        $this->load->view('master/V_MasterJenisPesanItem', $data);
    }

    public function deleteJenisPesan($id){
        $this->general->delete('id', $id, 'm_jenis_pesan');
    }
}
