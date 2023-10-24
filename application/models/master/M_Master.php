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

        public function deleteItem($tablename, $id){
            $this->db->where('id', $id)
                    ->update($tablename, [
                        'flag_active' => 0,
                        'updated_by' => $this->general_library->getId()
                    ]);
        }

        public function editMasterMerchant($id){
            $merchant = $this->db->select('*')
                                ->from('m_merchant')
                                ->where('id', $id)
                                ->where('flag_active', 1)
                                ->get()->row_array();

            $data = $this->input->post();
            $photo = $_FILES['logo_merchant']['name'] ? $_FILES['logo_merchant']['name'] : null;
            $upload = null;
            if($photo){
                $upload = $this->general_library->uploadLogoMerchant('logo_merchant','logo_merchant');
            }
            if($photo && $upload['code'] != 0){
                $this->session->set_flashdata('message', $upload['message']);
            } else {
                if($photo && $upload['code'] == 0){
                    $data['logo'] =  $photo ? $upload['data']['file_name'] : null;
                    if($data['logo'] && $merchant['logo']){
                        $file = 'assets/logo_merchant/'.$merchant['logo'];
                        unlink($file);
                    }
                }
                $data['updated_by'] = $this->general_library->getId();
                // $file = 'assets/logo_merchant/'.$merchant['logo'];
                // unlink($file);
                // dd(unlink($file));
                $this->db->where('id', $id)
                        ->update('m_merchant', $data);
            }
        }

        public function deleteLogo($id){
            $merchant = $this->db->select('*')
                                ->from('m_merchant')
                                ->where('id', $id)
                                ->where('flag_active', 1)
                                ->get()->row_array();

            $file = 'assets/logo_merchant/'.$merchant['logo'];
            unlink($file);
            $this->db->where('id', $id)
                    ->update('m_merchant', [
                        'logo' => null,
                        'updated_by' => $this->general_library->getId()
                    ]);
        }

        public function getKategoriMenuByIdJenis($id_m_jenis_menu){
            return $this->db->select('a.*, b.nama_jenis_menu')
                            ->from('m_kategori_menu a')
                            ->join('m_jenis_menu b', 'a.id_m_jenis_menu = b.id')
                            ->where('a.id_m_jenis_menu', $id_m_jenis_menu)
                            ->where('a.flag_active', 1)
                            ->order_by('a.nama_kategori_menu', 'asc')
                            ->get()->result_array();
        }

        public function getKategoriMenuMerchant($id_m_merchant){
            if($id_m_merchant == 0){
                $id_m_merchant = $this->general_library->getIdMerchant();
            }
            return $this->db->select('a.*, b.nama_jenis_menu')
                            ->from('m_kategori_menu a')
                            ->join('m_jenis_menu b', 'a.id_m_jenis_menu = b.id')
                            ->where('a.flag_active', 1)
                            ->where('a.id_m_merchant', $id_m_merchant)
                            ->order_by('a.nama_kategori_menu', 'asc')
                            ->get()->result_array();
        }

        public function getKategoriMenuByIdMerchant($id_m_merchant){
            return $this->db->select('a.*, b.nama_jenis_menu,
            (   SELECT count(c.id)
                FROM m_menu_merchant c 
                WHERE c.id_m_kategori_menu = a.id
                AND c.flag_active = 1) as jumlah_menu
            ')
                            ->from('m_kategori_menu a')
                            ->join('m_jenis_menu b', 'a.id_m_jenis_menu = b.id', 'left')
                            ->where('a.id_m_merchant', $id_m_merchant)
                            ->where('a.flag_active', 1)
                            ->order_by('a.nama_kategori_menu', 'asc')
                            ->get()->result_array();
        }

        public function getAllMenuMerchantByIdMerchant($id_m_merchant){
            return $this->db->select('a.*, b.nama_jenis_menu, c.nama_kategori_menu')
                            ->from('m_menu_merchant a')
                            ->join('m_jenis_menu b', 'a.id_m_jenis_menu = b.id', 'left')
                            ->join('m_kategori_menu c', 'a.id_m_kategori_menu = c.id', 'left')
                            ->where('a.id_m_merchant', $id_m_merchant)
                            ->where('a.flag_active', 1)
                            ->order_by('a.nama_menu_merchant', 'asc')
                            ->group_by('a.id')
                            ->get()->result_array();
        }

        public function getAllMerchant(){
            return $this->db->select('id, nama_merchant')
                            ->from('m_merchant')
                            ->where('flag_active', 1)
                            ->order_by('nama_merchant', 'asc')
                            ->get()->result_array();
        }

        public function getAllJenisMenuByIdMerchant($id){
            return $this->db->select('a.*, b.nama_merchant,
            (SELECT count(c.id)
                FROM m_kategori_menu c 
                WHERE c.id_m_jenis_menu = a.id
                AND c.flag_active = 1) as jumlah_kategori,
            (SELECT count(d.id)
                FROM m_menu_merchant d 
                WHERE d.id_m_jenis_menu = a.id
                AND d.flag_active = 1) as jumlah_menu,')
                            ->from('m_jenis_menu a')
                            ->join('m_merchant b', 'a.id_m_merchant = b.id')
                            ->where('a.id_m_merchant', $id)
                            ->where('a.flag_active', 1)
                            ->order_by('a.nama_jenis_menu', 'asc')
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
            return $this->db->select('a.*, b.nama_jenis_menu, c.nama_kategori_menu')
                            ->from('m_menu_merchant a')
                            ->join('m_jenis_menu b', 'a.id_m_jenis_menu = b.id', 'left')
                            ->join('m_kategori_menu c', 'a.id_m_kategori_menu = c.id', 'left')
                            ->where('a.id_m_merchant', $id_m_merchant)
                            ->where('a.flag_active', 1)
                            ->order_by('a.nama_menu_merchant')
                            ->group_by('a.id')
                            ->get()->result_array();
        }

        public function editJenisMenu($data, $id_m_user){
            $this->db->trans_begin();

            $this->db->where('id', $data['id'])
                    ->where('id_m_merchant', $data['id_m_merchant'])
                    ->update('m_jenis_menu', [
                        'nama_jenis_menu' => $data['nama_jenis_menu'],
                        'updated_by' => $id_m_user
                    ]);
            
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return 0;
            } else {
                $this->db->trans_commit();
                return 1;
            }
        }

        public function deleteJenisMenu($data, $id_m_user){
            $this->db->trans_begin();

            $this->db->where('id', $data['id'])
                    ->where('id_m_merchant', $data['id_m_merchant'])
                    ->update('m_jenis_menu', [
                        'flag_active' => 0,
                        'updated_by' => $id_m_user
                    ]);
            
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return 0;
            } else {
                $this->db->trans_commit();
                return 1;
            }
        }

        public function tambahJenisMenu($data, $id_m_user){
            $this->db->trans_begin();

            $exist = $this->db->select('*')
                            ->from('m_jenis_menu')
                            ->where('id_m_merchant', $data['id_m_merchant'])
                            ->where('nama_jenis_menu', $data['nama_jenis_menu'])
                            ->where('flag_active', 1)
                            ->get()->row_array();
            if($exist){
                $this->db->trans_rollback();
                return 2;
            }

            $this->db->insert('m_jenis_menu', [
                'nama_jenis_menu' => $data['nama_jenis_menu'],
                'id_m_merchant' => $data['id_m_merchant'],
                'created_by' => $id_m_user
            ]);

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return 0;
            } else {
                $this->db->trans_commit();
                return 1;
            }
        }

        public function editKategoriMenu($data, $id_m_user){
            $this->db->trans_begin();

            $this->db->where('id', $data['id'])
                    ->where('id_m_merchant', $data['id_m_merchant'])
                    ->update('m_kategori_menu', [
                        'nama_kategori_menu' => $data['nama_kategori_menu'],
                        'id_m_jenis_menu' => $data['id_m_jenis_menu'],
                        'updated_by' => $id_m_user
                    ]);
            
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return 0;
            } else {
                $this->db->trans_commit();
                return 1;
            }
        }

        public function deleteKategoriMenu($data, $id_m_user){
            $this->db->trans_begin();

            $this->db->where('id', $data['id'])
                    ->where('id_m_merchant', $data['id_m_merchant'])
                    ->update('m_kategori_menu', [
                        'flag_active' => 0,
                        'updated_by' => $id_m_user
                    ]);
            
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return 0;
            } else {
                $this->db->trans_commit();
                return 1;
            }
        }

        public function tambahKategoriMenu($data, $id_m_user){
            $this->db->trans_begin();

            $exist = $this->db->select('*')
                            ->from('m_kategori_menu')
                            ->where('id_m_merchant', $data['id_m_merchant'])
                            ->where('id_m_jenis_menu', $data['id_m_jenis_menu'])
                            ->where('nama_kategori_menu', $data['nama_kategori_menu'])
                            ->where('flag_active', 1)
                            ->get()->row_array();
            if($exist){
                $this->db->trans_rollback();
                return 2;
            }

            $this->db->insert('m_kategori_menu', [
                'nama_kategori_menu' => $data['nama_kategori_menu'],
                'id_m_jenis_menu' => $data['id_m_jenis_menu'],
                'id_m_merchant' => $data['id_m_merchant'],
                'created_by' => $id_m_user
            ]);

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return 0;
            } else {
                $this->db->trans_commit();
                return 1;
            }
        }

        public function editMenuMerchant($data, $id_m_user){
            $this->db->trans_begin();

            $kategori = $this->db->select('a.*')
                            ->from('m_kategori_menu a')
                            ->join('m_jenis_menu b', 'a.id_m_jenis_menu = b.id')
                            ->where('a.id', $data['id_m_kategori_menu'])
                            ->get()->row_array();

            $this->db->where('id', $data['id'])
                    ->where('id_m_merchant', $data['id_m_merchant'])
                    ->update('m_menu_merchant', [
                        'nama_menu_merchant' => $data['nama_menu_merchant'],
                        'id_m_kategori_menu' => $data['id_m_kategori_menu'],
                        'harga' => $data['harga'],
                        'id_m_jenis_menu' => $kategori ? $kategori['id_m_jenis_menu'] : null,
                        'updated_by' => $id_m_user
                    ]);
            
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return 0;
            } else {
                $this->db->trans_commit();
                return 1;
            }
        }

        public function deleteMenuMerchant($data, $id_m_user){
            $this->db->trans_begin();

            $this->db->where('id', $data['id'])
                    ->where('id_m_merchant', $data['id_m_merchant'])
                    ->update('m_menu_merchant', [
                        'flag_active' => 0,
                        'updated_by' => $id_m_user
                    ]);
            
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return 0;
            } else {
                $this->db->trans_commit();
                return 1;
            }
        }

        public function tambahMenuMerchant($data, $id_m_user){
            $this->db->trans_begin();

            $exist = $this->db->select('*')
                            ->from('m_menu_merchant')
                            ->where('id_m_merchant', $data['id_m_merchant'])
                            ->where('id_m_kategori_menu', $data['id_m_kategori_menu'])
                            ->where('nama_menu_merchant', $data['nama_menu_merchant'])
                            ->where('harga', $data['harga'])
                            ->where('flag_active', 1)
                            ->get()->row_array();
            if($exist){
                $this->db->trans_rollback();
                return 2;
            }

            $kategori = $this->db->select('a.*')
                            ->from('m_kategori_menu a')
                            ->join('m_jenis_menu b', 'a.id_m_jenis_menu = b.id')
                            ->where('a.id', $data['id_m_kategori_menu'])
                            ->get()->row_array();

            $this->db->insert('m_menu_merchant', [
                'nama_menu_merchant' => $data['nama_menu_merchant'],
                'id_m_jenis_menu' => $kategori ? $kategori['id_m_jenis_menu'] : null,
                'id_m_merchant' => $data['id_m_merchant'],
                'id_m_kategori_menu' => $data['id_m_kategori_menu'],
                'harga' => $data['harga'],
                'created_by' => $id_m_user
            ]);

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return 0;
            } else {
                $this->db->trans_commit();
                return 1;
            }
        }

        public function openStockMenuMerchant($id){
            $rs['item'] = $this->db->select('*')
                            ->from('m_menu_merchant')
                            ->where('id', $id)
                            ->get()->row_array();

            // $rs['stock'] = $this->db->select('*')
            //                         ->from('t_stock_menu_merchant')
            //                         ->where('id_m_menu_merchant', $id)
            //                         ->where('flag_active', 1)
            //                         ->order_by('tanggal', 'desc')
            //                         ->get()->result_array();
            return $rs;
        }

        public function loadStockMenuMerchant($id){
            return $this->db->select('*')
                        ->from('t_stock_menu_merchant')
                        ->where('id_m_menu_merchant', $id)
                        ->where('flag_active', 1)
                        ->order_by('tanggal', 'desc')
                        ->get()->result_array();
        }

        public function getAllBahanBakumerchant(){
            return $this->db->select('*')
                            ->from('m_bahan_baku')
                            ->where('id_m_merchant', $this->general_library->getIdMerchant())
                            ->where('flag_active', 1)
                            ->order_by('nama_bahan_baku', 'asc')
                            ->get()->result_array();
        }

        public function loadStockBahanBaku($id){
            return $this->db->select('a.*, b.satuan')
                        ->from('t_stock_bahan_baku a')
                        ->join('m_bahan_baku b', 'a.id_m_bahan_baku = b.id')
                        ->where('a.id_m_bahan_baku', $id)
                        ->where('a.flag_active', 1)
                        ->order_by('a.tanggal', 'desc')
                        ->get()->result_array();
        }

        public function inputStockMenuMerchant($id){
            $data = $this->input->post();
            $data['id_m_menu_merchant'] = $id;
            $data['created_by'] = $this->general_library->getId();
            $this->insert('t_stock_menu_merchant', $data);
        }

        public function inputStockBahanBaku($id){
            $data = $this->input->post();
            $data['id_m_bahan_baku'] = $id;
            $data['created_by'] = $this->general_library->getId();
            $this->insert('t_stock_bahan_baku', $data);
        }

        public function deleteStockMenuMerchant($id){
            $this->db->where('id', $id)
                    ->update('t_stock_menu_merchant', [
                        'flag_active' => 0,
                        'updated_by' => $this->general_library->getId()
                    ]);
        }

        public function deleteStockBahanBaku($id){
            $this->db->where('id', $id)
                    ->update('t_stock_bahan_baku', [
                        'flag_active' => 0,
                        'updated_by' => $this->general_library->getId()
                    ]);
        }

        public function loadBahanBakuMenuMerchant($id){
            return $this->db->select('a.*, b.nama_bahan_baku, b.satuan')
                        ->from('t_bahan_baku_menu_merchant a')
                        ->join('m_bahan_baku b', 'a.id_m_bahan_baku = b.id')
                        ->where('a.id_m_menu_merchant', $id)
                        ->where('a.flag_active', 1)
                        ->order_by('a.takaran', 'asc')
                        ->get()->result_array();
        }

        public function changeStatusStockMenuMerchant($id, $val){
            $this->db->where('id', $id)
                    ->update('m_menu_merchant', [
                        'updated_by' => $this->general_library->getId(),
                        'stock' => $val
                    ]);
        }

	}
?>