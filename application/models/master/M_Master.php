<?php
	class M_Master extends CI_Model
	{
		public function __construct()
        {
            parent::__construct();
            $this->db = $this->load->database('main', true);
        }

        public function insert($tablename, $data){
            $this->db->insert($tablename, $data);
        }

        public function getAllMerchant(){
            return $this->db->select('id, nama_merchant')
                            ->from('m_merchant')
                            ->where('flag_active', 1)
                            ->order_by('nama_merchant', 'asc')
                            ->get()->result_array();
        }

        public function getAllJenisMenu(){
            return $this->db->select('*')
                            ->from('m_jenis_menu')
                            ->where('flag_active', 1)
                            ->order_by('nama_jenis_menu', 'asc')
                            ->get()->result_array();
        }

        public function getAllKategoriMenu(){
            return $this->db->select('*')
                            ->from('m_kategori_menu')
                            ->where('flag_active', 1)
                            ->order_by('nama_kategori_menu', 'asc')
                            ->get()->result_array();
        }

        public function getAllMenuMerchant($id_m_merchant){
            
        }

	}
?>