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
            $date = explode("-", $tanggal[0]);

            $last_trx = $this->db->select('*')
                                ->from('t_transaksi')
                                ->where('id_m_merchant', $user['id_m_merchant'])
                                ->where('YEAR(tanggal_transaksi)', $date[0])
                                ->where('MONTH(tanggal_transaksi)', $date[1])
                                ->where('DAY(tanggal_transaksi)', $date[2])
                                ->order_by('created_date', 'desc')
                                ->limit(1)
                                ->get()->row_array();

            $nomor_transaksi = generateNomorTransaksi($user['id_m_merchant'], $last_trx);
            dd($nomor_transaksi);
            $total_harga = 0;

            $trx_detail = json_decode($data['data'], true);
            if(!$trx_detail){
                $this->db->trans_rollback();
                $rs['code'] = 500;
                $rs['message'] = 'Terjadi Kesalahan';
                return $rs;
            }

            $this->db->insert('t_transaksi',[
                'nomor_transaksi' => $nomor_transaksi,
                'tanggal_transaksi' => $data['tanggal_transaksi'],
                'nama' => $data['nama'],
                'id_m_merchant' => $data['id_m_merchant'],
                'created_by' => $user['id_m_user'],
            ]);

            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $rs['code'] = 500;
                $rs['message'] = 'Terjadi Kesalahan';
            } else {
                $this->db->trans_commit();
            }

            return $rs;
        }
	}
?>