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

    public function searchLaporanPenjualan(){
        list($data['result'], $data['tanggal']) = $this->laporan->searchLaporanPenjualan($this->input->post());
        $this->load->view('laporan/V_LaporanPenjualanResult', $data);
    }
}
