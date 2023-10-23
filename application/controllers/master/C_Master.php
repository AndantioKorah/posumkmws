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

    public function masterKategoriMenu(){
        $data['list_merchant'] = $this->general->getAllWithOrder('m_merchant');
        $data['list_jenis'] = $this->master->getAllJenisMenuByIdMerchant($this->general_library->getIdMerchant());
        render('master/V_MasterKategoriMenu', '', '', $data);
    }

    public function loadJenisMenuByForMasterKategori($id_merchant){
        echo json_encode($this->master->getAllJenisMenuByIdMerchant($id_merchant));
    }

    // public function loadKategoriMenuMerchant($id_m_jenis_menu){
    //     $data['result'] = $this->master->getKategoriMenuByIdJenis($id_m_jenis_menu);
    //     $this->load->view('master/V_MasterKategoriMenuItem', $data);
    // }

    public function loadKategoriMenuMerchant($id_m_merchant = 0){
        $data['result'] = $this->master->getKategoriMenuMerchant($id_m_merchant);
        $this->load->view('master/V_MasterKategoriMenuItem', $data);
    }

    public function createMasterKategoriMenu(){
        $data = $this->input->post();
        $data['created_by'] = $this->general_library->getId();
        $this->master->insert('m_kategori_menu', $data);       
    }

    public function deleteKategoriMenu($id){
        $this->master->deleteItem('m_kategori_menu', $id);
    }

    public function masterJenisMenu(){
        $data['list_merchant'] = $this->general->getAllWithOrder('m_merchant');
        render('master/V_MasterJenisMenu', '', '', $data);
    }

    public function loadJenisMenuByMerchant($id_merchant){
        $data['result'] = $this->master->getAllJenisMenuByIdMerchant($id_merchant);
        $this->load->view('master/V_MasterJenisMenuItem', $data);
    }

    public function deleteJenisMenu($id){
        $this->master->deleteItem('m_jenis_menu', $id);
    }

    public function createMasterJenisMenu(){
        $data = $this->input->post();
        $data['created_by'] = $this->general_library->getId();
        $this->master->insert('m_jenis_menu', $data);       
    }

    public function masterMerchant(){
        render('master/V_MasterMerchant', '', '', null);
    }

    public function createMasterMerchant(){
        $photo = $_FILES['logo_merchant']['name'];
        $upload = $this->general_library->uploadLogoMerchant('logo_merchant','logo_merchant');
        if($photo && $upload['code'] != 0){
            $this->session->set_flashdata('message', $upload['message']);
        } else {
            if(($photo && $upload['code'] == 0) || (!$photo && $upload['code'] != 0)){
                $this->session->set_flashdata('message', null);
                $data = $this->input->post();
                $data['logo'] =  $photo ? $upload['data']['file_name'] : null;
                $data['created_by'] = $this->general_library->getId();
                $data['api_key'] = generateRandomString(API_KEY_MERCHANT_LENGTH);
                $this->master->insert('m_merchant', $data);
            }
        }
        redirect('master/merchant');
    }

    public function editMasterMerchant($id){
        $this->master->editMasterMerchant($id);
    }

    public function deleteLogo($id){
        $this->master->deleteLogo($id);
    }

    public function openModalEditMerchant($id){
        $data['result'] = $this->general->get('m_merchant', 'id', $id)[0];
        $this->load->view('master/V_MasterEditMerchant', $data);
    }

    public function loadAllMerchant(){
        $data['list_jenis_pesan'] = $this->general->getAllWithOrder('m_merchant');
        $this->load->view('master/V_MasterMerchantItem', $data);
    }

    public function deleteMerchant($id){
        $this->general->delete('id', $id, 'm_merchant');
    }

    public function menuMerchant(){
        $data['list_merchant'] = $this->master->getAllMerchant();
        render('master/V_MasterMenuMerchant', '', '', $data);
    }

    public function getJenisMenuByMerchant($id_merchant){
        echo json_encode($this->master->getAllJenisMenuByIdMerchant($id_merchant));
    }

    public function getKategoriMenuByJenisMenu($id_m_jenis_menu){
        echo json_encode($this->master->getKategoriMenuByIdJenis($id_m_jenis_menu));
    }

    public function createMasterMenuMerchant(){
        $data = $this->input->post();
        $data['created_by'] = $this->general_library->getId();
        $this->master->insert('m_menu_merchant', $data);
    }

    public function loadAllMenuMerchant($id_merchant){
        $data['result'] = $this->master->getAllMenuMerchant($id_merchant);
        $this->load->view('master/V_MasterMenuMerchantItem', $data);
    }

    public function deleteMenuMerchant($id){
        $this->general->delete('id', $id, 'm_menu_merchant');
    }

    public function openModalEditMenuMerchant($id){
        $data['id_menu'] = $id;
        $data['result'] = $this->general->getOne('m_menu_merchant', 'id', $id);
        $this->load->view('master/V_MasterMenuMerchantModal', $data);
    }

    public function openStockMenuMerchant($id){
        $data['id_menu'] = $id;
        $data['result'] = $this->master->openStockMenuMerchant($id);
        $this->load->view('master/V_MasterMenuMerchantStock', $data);
    }

    public function loadStockMenuMerchant($id){
        $data['id_menu'] = $id;
        $data['result'] = $this->master->loadStockMenuMerchant($id);
        $this->load->view('master/V_MasterMenuMerchantStockItem', $data);
    }

    public function inputStockMenuMerchant($id){
        echo json_encode($this->master->inputStockMenuMerchant($id));
    }

    public function deleteStockMenuMerchant($id){
        $this->master->deleteStockMenuMerchant($id);
    }

    public function editHargaMenuMerchant($id){
        $data = $this->input->post();
        $data['updated_by'] = $this->general_library->getId();
        $data['harga'] = clearString($data['harga']);
        $this->general->update('id', $id, 'm_menu_merchant', $data);
    }
}
