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

        public function updateTotalHargaTransaksi($id){
            $total_harga = 0;
            $detail = $this->db->select('*')
                                ->from('t_transaksi_detail')
                                ->where('flag_active', 1)
                                ->where('id_t_transaksi', $id)
                                ->get()->result_array();
            if($detail){
                foreach($detail as $d){
                    $total_harga += (floatval($d['qty']) * floatval($d['harga']));
                }
            }
            
            $this->db->where('id', $id)
                        ->update('t_transaksi', [
                            'total_harga' => $total_harga
                        ]);
            return $total_harga;
        }

        public function changeSelectedMenu($data){
            $result = ['code' => 0, 'message' => 'Berhasil', 'total_harga' => 0];
            $exist = $this->db->select('*')
                            ->from('t_transaksi_detail a')
                            ->where('a.id_t_transaksi', $data['id_t_transaksi'])
                            ->where('a.id_m_menu_merchant', $data['id_m_menu_merchant'])
                            ->where('a.flag_active', 1)
                            ->get()->row_array();

            if($exist){
                if($data['type'] == 'minus'){
                    $new_qty = floatval($exist['qty']) - 1;
                    $new_total_harga = $new_qty * $exist['harga'];
                    
                } else if($data['type'] == 'plus') {
                    $new_qty = floatval($exist['qty']) + 1;
                    $new_total_harga = $new_qty * $exist['harga'];
                }
                
                if($new_qty == 0){
                    $this->db->where('id_m_menu_merchant', $data['id_m_menu_merchant'])
                            ->where('id_t_transaksi', $data['id_t_transaksi'])
                            ->update('t_transaksi_detail', [
                                'flag_active' => 0,
                                'updated_by' => $this->general_library->getId()
                            ]);
                } else {
                    $this->db->where('id_m_menu_merchant', $data['id_m_menu_merchant'])
                            ->where('id_t_transaksi', $data['id_t_transaksi'])
                            ->update('t_transaksi_detail', [
                                'qty' => $new_qty,
                                'total_harga' => $new_total_harga,
                                'updated_by' => $this->general_library->getId()
                            ]);
                }
            } else {
                if($data['type'] == 'minus'){
                    // $result = ['code' => 1, 'message' => 'Terjadi Kesalahan'];
                } else if($data['type'] == 'plus') {
                    $menu = $this->db->select('*')
                                ->from('m_menu_merchant a')
                                ->where('a.id', $data['id_m_menu_merchant'])
                                ->where('a.flag_active', 1)
                                ->get()->row_array();
                    if($menu){
                        $this->db->insert('t_transaksi_detail', [
                            'id_t_transaksi' => $data['id_t_transaksi'],
                            'id_m_menu_merchant' => $menu['id'],
                            'harga' => $menu['harga'],
                            'qty' => 1,
                            'total_harga' => $menu['harga'],
                            'nama_menu_merchant' => $menu['nama_menu_merchant'],
                            'created_by' => $this->general_library->getId()                            
                        ]);
                    }
                }
            }

            $result['total_harga'] = $this->updateTotalHargaTransaksi($data['id_t_transaksi']);
            return $result;
        }

        public function deleteSelectedMenu($data){
            $exist = $this->db->select('*')
                            ->from('t_transaksi_detail')
                            ->where('id', $data['id'])
                            ->get()->row_array();

            $this->db->where('id', $data['id'])
                    ->update('t_transaksi_detail', [
                        'flag_active' => 0,
                        'updated_by' => $this->general_library->getId()
                    ]);

            return $this->updateTotalHargaTransaksi($exist['id_t_transaksi']);
        }

        public function getPembayaranTransaksi($id){
            return $this->db->select('*')
                            ->from('t_pembayaran a')
                            ->join('m_jenis_pembayaran b', 'a.id_m_jenis_pembayaran = b.id')
                            ->where('a.id_t_transaksi', $id)
                            ->where('a.flag_active', 1)
                            ->get()->row_array();
        }

        public function saveTransaksi($id){
            $data['id'] = $id;
            if($id != 0){
                $data = $this->input->post();
                $this->db->where('id', $id)
                        ->update('t_transaksi', [
                            'nama' => $data['nama'],
                            'tanggal_transaksi' => $data['tanggal_transaksi']
                        ]);
            } else {
                $last_trx = $this->db->select('*')
                                ->from('t_transaksi')
                                ->where('id_m_merchant', $this->general_library->getIdMerchant())
                                ->where('DATE(tanggal_transaksi)', date('Y-m-d'))
                                ->order_by('created_date', 'desc')
                                ->limit(1)
                                ->get()->row_array();
                $nomor_transaksi = generateNomorTransaksi($this->general_library->getIdMerchant(), $last_trx);
                $this->db->insert('t_transaksi',[
                    'nomor_transaksi' => $nomor_transaksi,
                    'tanggal_transaksi' => date('Y-m-d H:i:s'),
                    'nama' => "",
                    'total_harga' => 0,
                    'id_m_merchant' => $this->general_library->getIdMerchant(),
                    'created_by' => $this->general_library->getId(),
                ]);
                $data['id'] = $this->db->insert_id();
            }
            
            return $data;
        }

        public function createPembayaran($id){
            $transaksi = $this->db->select('*')
                                ->from('t_transaksi')
                                ->where('id', $id)
                                ->get()->row_array();

            $data = $this->input->post();
            $kembalian = floatval(clearString($transaksi['total_pembayaran'])) - floatval($data['total_harga']);

            $this->db->insert('t_pembayaran', [
                'id_t_transaksi' => $id,
                'id_m_merchant' => $this->general_library->getIdMerchant(),
                'tanggal_pembayaran' => date('Y-m-d H:i:s'),
                'nama_pembayar' => $data['nama_pembayar'],
                'id_m_jenis_pembayaran' => $data['id_m_jenis_pembayaran'],
                'total_pembayaran' => clearString($data['total_pembayaran']),
                'diskon_nominal' => clearString($data['diskon_nominal']),
                'kembalian' => $kembalian,
                'nomor_pembayaran' => str_pad($this->general_library->getIdMerchant(), 4, '0', STR_PAD_LEFT).date('ymdhis'),
                'created_by' => $this->general_library->getId()
            ]);

            $this->db->where('id', $id)
                    ->update('t_transaksi', [
                        'status_transaksi' => 'Lunas',
                        'updated_by' => $this->general_library->getId()
                    ]);
        }

        public function deletePembayaran($id){
            $this->db->where('id_t_transaksi', $id)
                    ->update('t_pembayaran', ['flag_active' => 0, 'updated_by' => $this->general_library->getId()]);

            $this->db->where('id', $id)
                    ->update('t_transaksi', ['status_transaksi' => 'Belum Lunas', 'updated_by' => $this->general_library->getId()]);
        }

        public function deleteTransaksi($id){
            $rs['code'] = 0;
            $rs['message'] = '';

            $detail = $this->db->select('*')
                            ->from('t_transaksi_detail')
                            ->where('id_t_transaksi', $id)
                            ->where('flag_active', 1)
                            ->get()->result_array();

            if($detail){
                $rs['code'] = 1;
                $rs['message'] = 'Data tidak dapat dihapus karena masih ada item yang tersimpan.';
            }

            $pembayaran = $this->db->select('*')
                            ->from('t_pembayaran')
                            ->where('id_t_transaksi', $id)
                            ->where('flag_active', 1)
                            ->get()->result_array();

            if($pembayaran){
                $rs['code'] = 1;
                $rs['message'] = 'Data tidak dapat dihapus karena sudah ada pembayaran.';
            }

            if($rs['code'] == 0){
                $this->db->where('id', $id)
                        ->update('t_transaksi', ['flag_active' => 0]);
            }

            return $rs;

        }

	}
?>