<?php
	class M_Kasir extends CI_Model
	{
		public function __construct()
        {
            parent::__construct();
            $this->db = $this->load->database('main', true);
        }

        public function insert($tablename, $data){
            $this->db->insert($tablename, $data);
        }

        public function loadListTransaksi($tanggal){
            return $this->db->select('*')
                            ->from('t_transaksi a')
                            ->where('a.flag_active', 1)
                            ->where('a.tanggal_transaksi >=', $tanggal.' 00:00:00')
                            ->where('a.tanggal_transaksi <=', $tanggal.' 23:59:59')
                            ->order_by('a.status_transaksi', 'asc')
                            ->order_by('a.tanggal_transaksi', 'desc')
                            ->where('a.id_m_merchant', $this->general_library->getIdMerchant())
                            ->get()->result_array();
        }

        public function searchTransaksi($tanggal, $search){
            $result = null;
            if($search['search_field'] != ''){
                return $this->db->select('*')
                            ->from('t_transaksi a')
                            ->where('a.flag_active', 1)
                            ->where('a.tanggal_transaksi >=', $tanggal.' 00:00:00')
                            ->where('a.tanggal_transaksi <=', $tanggal.' 23:59:59')
                            ->where('a.nama LIKE "%'.$search['search_field'].'%" OR a.total_harga LIKE "%'.$search['search_field'].'%"')
                            ->order_by('a.status_transaksi', 'asc')
                            ->order_by('a.tanggal_transaksi', 'desc')
                            ->where('a.id_m_merchant', $this->general_library->getIdMerchant())
                            ->get()->result_array();
            } else {
                return $this->loadListTransaksi($tanggal);
            }
        }

        public function getDataTransaksi($id){
            $transaksi = $this->db->select('*')
                                ->from('t_transaksi a')
                                ->where('a.id', $id)
                                ->where('a.id_m_merchant', $this->general_library->getIdMerchant())
                                ->where('a.flag_active', 1)
                                ->get()->row_array();

            return $transaksi;
        }

        public function getMenuMerchantByIdMerchant($flag_only_list = 0, $id = 0){
            $list_item = $this->db->select('a.*, b.nama_kategori_menu, c.nama_jenis_menu')
                            ->from('m_menu_merchant a')
                            ->join('m_kategori_menu b', 'a.id_m_kategori_menu = b.id')
                            ->join('m_jenis_menu c', 'a.id_m_jenis_menu = c.id')
                            ->where('a.id_m_merchant', $this->general_library->getIdMerchant())
                            ->where('a.flag_active', 1)
                            ->order_by('a.nama_menu_merchant', 'asc')
                            ->get()->result_array();

            $final_detail = $this->getListSelectedMenu($id);

            return $flag_only_list == 1 ? $list_item : [$list_item, $final_detail];
        }

        public function getListSelectedMenu($id){
            $detail = $this->db->select('*')
                    ->from('t_transaksi_detail a')
                    ->where('a.id_t_transaksi', $id)
                    ->where('a.flag_active', 1)
                    ->order_by('a.created_date', 'desc')
                    ->get()->result_array();

            $final_detail = null;
            if($detail){
                foreach($detail as $d){
                    $final_detail[$d['id_m_menu_merchant']] = $d;
                }
            }

            return $final_detail;
        }

        public function searchMenuMerchant($search){
            if($search != "" && $search != null){
                return $this->db->select('a.*, b.nama_kategori_menu, c.nama_jenis_menu')
                                ->from('m_menu_merchant a')
                                ->join('m_kategori_menu b', 'a.id_m_kategori_menu = b.id')
                                ->join('m_jenis_menu c', 'a.id_m_jenis_menu = c.id')
                                ->where('a.id_m_merchant', $this->general_library->getIdMerchant())
                                ->where('a.flag_active', 1)
                                ->where('(a.nama_menu_merchant LIKE "%'.$search.'%" OR b.nama_kategori_menu LIKE "%'.$search.'%" OR c.nama_jenis_menu LIKE "%'.$search.'%")')
                                ->order_by('a.nama_menu_merchant', 'asc')
                                ->get()->result_array();
            } else {
                return $this->getMenuMerchantByIdMerchant(1);
            }
        }

	}
?>