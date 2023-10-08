<?php

class C_Kasir extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('general/M_General', 'general');
        $this->load->model('user/M_User', 'user');
        $this->load->model('kasir/M_Kasir', 'kasir');
        if(!$this->general_library->isNotMenu()){
            redirect('logout');
        };
    }

    public function kasir(){
        render('kasir/V_Kasir', '', '', null);
    }

    public function loadListTransaksi($tanggal = null){
        if($tanggal == null){
            $tanggal = date('Y-m-d');
        }
        $data['result'] = $this->kasir->loadListTransaksi($tanggal);
        $this->load->view('kasir/V_ListTransaksi', $data);
    }

    public function searchTransaksi($tanggal){
        $data['result'] = $this->kasir->searchTransaksi($tanggal, $this->input->post());
        $this->load->view('kasir/V_ListTransaksi', $data);
    }

    public function getListSelectedMenu($id){
        $data['result'] = $this->kasir->getListSelectedMenu($id);
        $this->load->view('kasir/V_SelectedMenu', $data);
    }

    public function detailTransaksi($id){
        $data['transaksi'] = $this->kasir->getDataTransaksi($id);
        $this->load->view('kasir/V_DetailTransaksi', $data);
    }

    public function loadListMenu($id){
        $data['id_t_transaksi'] = $id;
        list($data['list_menu'], $data['detail']) = $this->kasir->getMenuMerchantByIdMerchant(0, $id);
        $this->session->set_userdata('list_detail', $data['detail']);
        $this->load->view('kasir/V_ListMenu', $data);
    }

    public function searchMenuMerchant(){
        $data['list_menu'] = $this->kasir->searchMenuMerchant($this->input->post('search'));
        $data['detail'] = $this->session->userdata('list_detail');
        $this->load->view('kasir/V_ListMenu', $data);
    }

    public function changeSelectedMenu(){
        echo json_encode($this->kasir->changeSelectedMenu($this->input->post()));
    }

    public function deleteSelectedMenu(){
        $this->kasir->deleteSelectedMenu($this->input->post());
    }

    public function getPembayaranTransaksi($id){
        $data['result'] = $this->kasir->getPembayaranTransaksi($id);
        $data['transaksi'] = $this->kasir->getDataTransaksi($id);
        $data['jenis_pembayaran'] = $this->general->getAllWithOrder('m_jenis_pembayaran', 'nama_jenis_pembayaran', 'asc');
        $this->load->view('kasir/V_Pembayaran', $data);
    }
}
