<?php
	class M_Transaction extends CI_Model
	{
		public function __construct()
        {
            parent::__construct();
            $this->db = $this->load->database('main', true);
        }

        public function insert($tablename, $data){
            $this->db->insert($tablename, $data);
        }

        public function createTransaction($data, $user){
            $rs['code'] = 201;
            $rs['message'] = "Transaksi Berhasil Dibuat";
            $rs['status'] = true;
            $rs['data'] = null;

            $this->db->trans_begin();

            $tanggal = explode(" ", $data['tanggal_transaksi']);
            $date = explode("/", $tanggal[0]);

            $last_trx = $this->db->select('*')
                                ->from('t_transaksi')
                                ->where('id_m_merchant', $user['id_m_merchant'])
                                ->where('YEAR(tanggal_transaksi)', $date[2])
                                ->where('MONTH(tanggal_transaksi)', $date[1])
                                ->where('DAY(tanggal_transaksi)', $date[0])
                                ->order_by('created_date', 'desc')
                                ->limit(1)
                                ->get()->row_array();

            $nomor_transaksi = generateNomorTransaksi($user['id_m_merchant'], $last_trx);

            $trx_detail = json_decode($data['data'], true);
            if(!$trx_detail){
                $this->db->trans_rollback();
                $rs['code'] = 400;
                $rs['message'] = 'Belum ada item yang dipilih';
                return $rs;
            }

            $this->db->insert('t_transaksi',[
                'nomor_transaksi' => $nomor_transaksi,
                'tanggal_transaksi' => $date[2].'-'.$date[1].'-'.$date[0].' '.$tanggal[1],
                'nama' => $data['nama'],
                'id_m_merchant' => $user['id_m_merchant'],
                'created_by' => $user['id_m_user'],
            ]);

            $lastId = $this->db->insert_id();
            
            $detail = [];
            $i = 0;
            $total_harga = 0;
            foreach($trx_detail as $td){
                $detail[$i]['id_t_transaksi'] = $lastId;
                $detail[$i]['id_m_menu_merchant'] = $td['id'];
                $detail[$i]['harga'] = $td['harga'];
                $detail[$i]['nama_menu_merchant'] = $td['nama_menu_merchant'];
                $detail[$i]['qty'] = $td['selectedCount'];

                $total_harga += floatval($td['selectedCount']) * floatval($td['harga']);
                $detail[$i]['total_harga'] = $total_harga;
                $i++;
            }

            $this->db->insert_batch('t_transaksi_detail', $detail);

            $this->db->where('id', $lastId)
                    ->update('t_transaksi', ['total_harga' => $total_harga]);
            
            $rs['data'] = $lastId;

            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $rs['code'] = 500;
                $rs['message'] = 'Terjadi Kesalahan';
            } else {
                $this->db->trans_commit();
            }
            return $rs;
        }

        public function getPembayaranDetail($data, $user){
            $rs['code'] = 200;
            $rs['message'] = "";
            $rs['status'] = true;
            $rs['data'] = null;

            $pembayaran = $this->db->select('*')
                                    ->from('t_pembayaran')
                                    ->where('id_t_transaksi', $data['id'])
                                    ->where('flag_active', 1)
                                    ->get()->row_array();
            
            $transaksi = $this->db->select('*')
                                    ->from('t_transaksi')
                                    ->where('id', $data['id'])
                                    ->where('flag_active', 1)
                                    ->get()->row_array();

            $transaksi_detail = $this->db->select('*')
                                    ->from('t_transaksi_detail')
                                    ->where('id_t_transaksi', $data['id'])
                                    ->where('flag_active', 1)
                                    ->order_by('nama_menu_merchant')
                                    ->get()->result_array();

            $result['pembayaran'] = $pembayaran;
            $result['transaksi'] = $transaksi;
            $result['transaksi']['detail'] = $transaksi_detail;
            $rs['data'] = $result;

            return $rs;
        }
	}
?>