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
                    ->update($tablename, ['flag_active' => 0]);
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

        public function getKategoriMenuByIdMerchant($id_m_merchant){
            return $this->db->select('a.*, b.nama_jenis_menu')
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
            return $this->db->select('a.*, b.nama_merchant')
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

	}
?>