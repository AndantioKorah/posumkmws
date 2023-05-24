<?php
	class M_Laporan extends CI_Model
	{
		public function __construct()
        {
            parent::__construct();
            $this->db = $this->load->database('main', true);
        }

        public function insert($tablename, $data){
            $this->db->insert($tablename, $data);
        }

        public function searchLaporanPendaftaranPerPasien(){
            $data = $this->input->post();
            list($tanggal_awal, $tanggal_akhir) = explodeRangeDate($data['range_tanggal']);

            $result = $this->db->select('a.*, b.nama_pasien, c.total_tagihan, c.status_tagihan')
                            ->from('t_pendaftaran a')
                            ->join('m_pasien b', 'a.norm = b.norm')
                            ->join('t_tagihan c', 'a.id = c.id_t_pendaftaran')
                            ->where('a.tanggal_pendaftaran >=', $tanggal_awal.' 00:00:00')
                            ->where('a.tanggal_pendaftaran <=', $tanggal_akhir.' 23:59:59')
                            ->order_by('a.tanggal_pendaftaran', 'desc')
                            ->get()->result_array();
            return $result;
        }

        public function searchLaporanRekapHarian(){
            $data = $this->input->post();
            list($tanggal_awal, $tanggal_akhir) = explodeRangeDate($data['range_tanggal']);

            $result = $this->db->select('a.*, b.nama_pasien, c.total_tagihan, c.status_tagihan, d.jumlah_pembayaran as jumlah_bayar, e.jumlah_pembayaran as uang_muka')
                            ->from('t_pendaftaran a')
                            ->join('m_pasien b', 'a.norm = b.norm')
                            ->join('t_tagihan c', 'a.id = c.id_t_pendaftaran')
                            ->join('t_pembayaran d', 'd.id_t_pendaftaran = a.id AND d.flag_active = 1', 'left')
                            ->join('t_uang_muka e', 'e.id_t_pendaftaran = a.id AND e.flag_active = 1', 'left')
                            ->where('a.tanggal_pendaftaran >=', $tanggal_awal.' 00:00:00')
                            ->where('a.tanggal_pendaftaran <=', $tanggal_akhir.' 23:59:59')
                            ->order_by('a.tanggal_pendaftaran', 'desc')
                            ->get()->result_array();

            $total_pelunasan = 0;
            $total_uang_muka = 0;
            $total_belum_bayar = 0;
            if($result){
                foreach($result as $rs){
                    $belum_bayar = $rs['total_tagihan'] - ($rs['uang_muka'] + $rs['jumlah_bayar']);
                    $total_pelunasan += $rs['jumlah_bayar'];
                    $total_uang_muka += $rs['uang_muka'];
                    $total_belum_bayar += $belum_bayar;
                }
            }
            $total_penerimaan = $total_uang_muka + $total_pelunasan;
            return [$result, count($result), $total_uang_muka, $total_pelunasan, $total_belum_bayar, $total_penerimaan];
        }


        public function searchLaporanFeeDokter(){
            $data = $this->input->post();
            list($tanggal_awal, $tanggal_akhir) = explodeRangeDate($data['range_tanggal']);
            // dd($data['dokter']);

            if($data['dokter'] != "0"){
                $result = $this->db->select('`a`.*, `b`.`nama_dokter`, `c`.`total_tagihan`,  sum(c.total_tagihan)as total, `c`.`status_tagihan`, b.fee')
                                ->from('t_pendaftaran a')
                                ->join('m_dokter b', 'a.id_m_dokter_pengirim = b.id')
                                ->join('t_tagihan c', 'a.id = c.id_t_pendaftaran')
                                ->where('a.tanggal_pendaftaran >=', $tanggal_awal.' 00:00:00')
                                ->where('a.tanggal_pendaftaran <=', $tanggal_akhir.' 23:59:59')
                                ->where('a.id_m_dokter_pengirim', $data['dokter'])
                                ->where('c.id_m_status_tagihan', 2)
                                ->where('a.flag_active', 1)
                                ->group_by('a.id_m_dokter_pengirim')
                                ->order_by('b.nama_dokter', 'asc')
                                ->get()->result_array();
            } else {
                $result = $this->db->select('`a`.*, `b`.`nama_dokter`, `c`.`total_tagihan`, sum(c.total_tagihan)as total, `c`.`status_tagihan`, b.fee')
                                ->from('t_pendaftaran a')
                                ->join('m_dokter b', 'a.id_m_dokter_pengirim = b.id')
                                ->join('t_tagihan c', 'a.id = c.id_t_pendaftaran')
                                ->where('a.tanggal_pendaftaran >=', $tanggal_awal.' 00:00:00')
                                ->where('a.tanggal_pendaftaran <=', $tanggal_akhir.' 23:59:59')                               
                                ->where('c.id_m_status_tagihan', 2)
                                ->where('a.flag_active', 1)
                                ->group_by('a.id_m_dokter_pengirim')
                                ->order_by('b.nama_dokter', 'asc')
                                ->get()->result_array();
            } 
            return $result;
        }

        
	}
?>