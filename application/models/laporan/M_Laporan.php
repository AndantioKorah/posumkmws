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

        public function searchLaporanPenjualan($data){
            $tanggal = explodeRangeDate($data['range_tanggal']);

            $rs['total_penjualan'] = 0;
            $rs['total_penjualan_lunas'] = 0;
            $rs['total_penjualan_belum_lunas'] = 0;
            $rs['total_transaksi'] = 0;
            $rs['total_transaksi_lunas'] = 0;
            $rs['total_transaksi_belum_lunas'] = 0;
            $rs['total_item'] = 0;
            $rs['total_item_lunas'] = 0;
            $rs['total_item_belum_lunas'] = 0;
            $list_item = null;
            $rs['list_item'] = null;
            $rs['data_transaksi'] = null;
            $rs['kategori_menu'] = null;
            $rs['jenis_menu'] = null;

            $list_id_transaksi = [];

            $rs['menu'] = $this->db->select('*')
                            ->from('m_menu_merchant')
                            ->where('flag_active', 1)
                            ->where('id_m_merchant', $this->general_library->getIdMerchant())
                            ->order_by('nama_menu_merchant')
                            ->get()->result_array();

            $kategori_menu = $this->db->select('*')
                                            ->from('m_kategori_menu')
                                            ->where('flag_active', 1)
                                            ->where('id_m_merchant', $this->general_library->getIdMerchant())
                                            ->get()->result_array();

            if($kategori_menu){
                foreach($kategori_menu as $km){
                    $rs['kategori_menu'][$km['id']] = $km;
                    $rs['kategori_menu'][$km['id']]['total'] = 0;
                    $rs['kategori_menu'][$km['id']]['nama'] = $km['nama_kategori_menu'];
                }
            }

            $jenis_menu = $this->db->select('*')
                                            ->from('m_jenis_menu')
                                            ->where('flag_active', 1)
                                            ->where('id_m_merchant', $this->general_library->getIdMerchant())
                                            ->get()->result_array();
            if($jenis_menu){
                foreach($jenis_menu as $jm){
                    $rs['jenis_menu'][$jm['id']] = $jm;
                    $rs['jenis_menu'][$jm['id']]['total'] = 0;
                    $rs['jenis_menu'][$jm['id']]['nama'] = $jm['nama_jenis_menu'];
                }
            }

            $data_transaksi = $this->db->select('a.*, b.*, a.id as id_transaksi, b.id as id_transaksi_detail, b.total_harga as harga_detail, 
                                    b.id_m_menu_merchant, c.id_m_kategori_menu, d.id_m_jenis_menu')
                                    ->from('t_transaksi a')
                                    ->join('t_transaksi_detail b',' a.id = b.id_t_transaksi')
                                    ->join('m_menu_merchant c', 'b.id_m_menu_merchant = c.id')
                                    ->join('m_kategori_menu d', 'c.id_m_kategori_menu = d.id')
                                    ->join('m_jenis_menu e', 'd.id_m_jenis_menu = e.id')
                                    ->where('a.tanggal_transaksi >=', $tanggal[0].' 00:00:00')
                                    ->where('a.tanggal_transaksi <=', $tanggal[1].' 23:59:59')
                                    ->where('a.id_m_merchant', $this->general_library->getIdMerchant())
                                    ->where('a.flag_active', 1)
                                    ->where('b.flag_active', 1)
                                    ->order_by('a.tanggal_transaksi', 'desc')
                                    ->get()->result_array();

            $rs['data_transaksi'] = $this->db->select('*')
                                    ->from('t_transaksi a')
                                    ->where('a.tanggal_transaksi >=', $tanggal[0].' 00:00:00')
                                    ->where('a.tanggal_transaksi <=', $tanggal[1].' 23:59:59')
                                    ->where('a.id_m_merchant', $this->general_library->getIdMerchant())
                                    ->where('a.flag_active', 1)
                                    ->order_by('a.status_transaksi', 'asc')
                                    ->order_by('a.tanggal_transaksi', 'desc')
                                    ->get()->result_array();

            if($data_transaksi){
                foreach($data_transaksi as $dt){
                    if(isset($rs['kategori_menu'][$dt['id_m_kategori_menu']])){
                        $rs['kategori_menu'][$dt['id_m_kategori_menu']]['total'] += $dt['qty'];
                    }
                    if(isset($rs['jenis_menu'][$dt['id_m_jenis_menu']])){
                        $rs['jenis_menu'][$dt['id_m_jenis_menu']]['total'] += $dt['qty'];
                    }

                    $flag_lunas = $dt['status_transaksi'] == 'Belum Lunas' ? 0 : 1;
                    $rs['total_penjualan'] += $dt['harga_detail'];
                    $rs['total_item'] += $dt['qty'];
                    if($flag_lunas == 1){
                        $rs['total_penjualan_lunas'] += $dt['harga_detail'];
                        $rs['total_item_lunas'] += $dt['qty'];
                    } else {
                        $rs['total_penjualan_belum_lunas'] += $dt['harga_detail'];
                        $rs['total_item_belum_lunas'] += $dt['qty'];
                    }
                    if(!in_array($dt['id_transaksi'], $list_id_transaksi)){
                        if($flag_lunas == 1){
                            $rs['total_transaksi_lunas']++;
                        } else {
                            $rs['total_transaksi_belum_lunas']++;
                        }
                        array_push($list_id_transaksi, $dt['id_transaksi']); 
                    }

                    if(isset($list_item[$dt['id_m_menu_merchant']])){
                        $list_item[$dt['id_m_menu_merchant']]['qty'] += $dt['qty'];
                    } else {
                        $list_item[$dt['id_m_menu_merchant']]['qty'] = $dt['qty'];
                    }
                }
                $rs['total_transaksi'] = count($list_id_transaksi);
            }

            if($list_item && $rs['menu']){
                foreach($rs['menu'] as $m){
                    $rs['list_item'][$m['id']] = $m;
                    $rs['list_item'][$m['id']]['penjualan']['qty'] = 0;
                    if(isset($list_item[$m['id']])){
                        $rs['list_item'][$m['id']]['penjualan'] = $list_item[$m['id']];
                    }
                }

                usort($rs['list_item'], function($a, $b) {
                    if ($a['penjualan']['qty'] < $b['penjualan']['qty']) {
                        return 1;
                    } elseif ($a['penjualan']['qty'] > $b['penjualan']['qty']) {
                        return -1;
                    }
                    return 0;
                });
            }

            return [$rs, $tanggal];
        }

	}
?>