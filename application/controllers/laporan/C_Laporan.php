<?php

class C_Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('general/M_General', 'general');
        $this->load->model('user/M_User', 'user');
        $this->load->model('laporan/M_Laporan', 'laporan');
        if(!$this->general_library->isNotMenu()){
            redirect('logout');
        };
    }

    public function laporanPenjualan(){
        render('laporan/V_LaporanPenjualan', '', '', null);
    }

    public function searchLaporanPenjualan($flag_welcome = 0){
        $data['flag_welcome'] = $flag_welcome;
        list($data['result'], $data['tanggal']) = $this->laporan->searchLaporanPenjualan($this->input->post());
        $this->load->view('laporan/V_LaporanPenjualanResult', $data);
    }

    public function stockOpname(){
        render('laporan/V_LaporanStockOpname', '', '', null);
    }

    public function searchLaporanStockOpname(){
        $data['result'] = $this->laporan->searchLaporanStockOpname($this->input->post());
        $this->session->set_userdata('stock_opname', $data['result']);
        $this->load->view('laporan/V_LaporanStockOpnameResult', $data);
    }

    public function openDetailStockOpname($id){
        $stockOpname = $this->session->userdata('stock_opname');
        $data['result'] = null;
        if(isset($stockOpname['menu'][$id])){
            $data['result'] = $stockOpname['menu'][$id];
        }
        $this->load->view('laporan/V_LaporanStockOpnameDetail', $data);
    }
}
