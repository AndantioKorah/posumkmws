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

        public function searchLaporanStockOpnameBahanBaku($data){
            $rs = null;
            $tanggal = explodeRangeDate($data['range_tanggal']);

            $bahan_baku = $this->db->select('*')
                            ->from('m_bahan_baku')
                            ->where('flag_active', 1)
                            ->where('id_m_merchant', $this->general_library->getIdMerchant())
                            ->order_by('nama_bahan_baku')
                            ->get()->result_array();

            $rs['bahan_baku'] = null;
            if($bahan_baku){
                foreach($bahan_baku as $bb){
                    $rs['bahan_baku'][$bb['id']] = $bb;
                    $rs['bahan_baku'][$bb['id']]['stock_awal'] = 0;
                    $rs['bahan_baku'][$bb['id']]['stock_akhir'] = 0;
                    $rs['bahan_baku'][$bb['id']]['transaksi'] = [];
                }
            }

            $stock_bahan_baku = $this->db->select('a.*')
                                        ->from('t_stock_bahan_baku a')
                                        ->join('m_bahan_baku b', 'a.id_m_bahan_baku = b.id')
                                        ->where('b.id_m_merchant', $this->general_library->getIdMerchant())
                                        ->where('a.tanggal >=', $tanggal[0].' 00:00:00')
                                        ->where('a.tanggal <=', $tanggal[1].' 23:59:59')
                                        ->where('a.flag_active', 1)
                                        ->order_by('a.tanggal', 'asc')
                                        ->get()->result_array();

            if($stock_bahan_baku){
                foreach($stock_bahan_baku as $sb){
                    $rs['bahan_baku'][$bb['id']]['stock_akhir'] += $sb['jumlah_barang'];
                    if($rs['bahan_baku'][$sb['id_m_bahan_baku']]['stock_awal'] == 0){
                        $rs['bahan_baku'][$sb['id_m_bahan_baku']]['stock_awal'] = $sb['jumlah_barang'];
                    }
                    $rs['bahan_baku'][$sb['id_m_bahan_baku']]['transaksi'][formatDateOnly($sb['tanggal'])]['tanggal'] = formatDateOnly($sb['tanggal']);

                    if(isset($rs['bahan_baku'][$sb['id_m_bahan_baku']]['transaksi'][formatDateOnly($sb['tanggal'])]['masuk'])){
                        $rs['bahan_baku'][$sb['id_m_bahan_baku']]['transaksi'][formatDateOnly($sb['tanggal'])]['masuk'] += $sb['jumlah_barang'];
                    } else {
                        $rs['bahan_baku'][$sb['id_m_bahan_baku']]['transaksi'][formatDateOnly($sb['tanggal'])]['masuk'] = $sb['jumlah_barang'];
                    }
                    $rs['bahan_baku'][$sb['id_m_bahan_baku']]['transaksi'][formatDateOnly($sb['tanggal'])]['keluar'] = 0;
                }
            }

            $transaksi_detail = $this->db->select('a.qty, c.nama_menu_merchant, b.tanggal_transaksi, d.id_m_bahan_baku, d.takaran')
                                        ->from('t_transaksi_detail a')
                                        ->join('t_transaksi b', 'a.id_t_transaksi = b.id')
                                        ->join('m_menu_merchant c', 'a.id_m_menu_merchant = c.id')
                                        ->join('t_bahan_baku_menu_merchant d', 'a.id_m_menu_merchant = d.id_m_menu_merchant', 'left')
                                        ->where('b.id_m_merchant', $this->general_library->getIdMerchant())
                                        ->where('a.flag_active', 1)
                                        ->where('b.flag_active', 1)
                                        ->where('c.stock', 0)
                                        ->where('b.tanggal_transaksi >=', $tanggal[0].' 00:00:00')
                                        ->where('b.tanggal_transaksi <=', $tanggal[1].' 23:59:59')
                                        ->order_by('b.created_date', 'desc')
                                        // ->group_by('d.id')
                                        ->get()->result_array();
            
            if($transaksi_detail){
                foreach($transaksi_detail as $t){
                    if($t['id_m_bahan_baku']){
                        $rs['bahan_baku'][$t['id_m_bahan_baku']]['stock_akhir'] -= ($t['qty'] * $t['takaran']);
                        $rs['bahan_baku'][$t['id_m_bahan_baku']]['transaksi'][formatDateOnly($t['tanggal_transaksi'])]['tanggal'] = formatDateOnly($t['tanggal_transaksi']);
                        if(isset($rs['bahan_baku'][$t['id_m_bahan_baku']]['transaksi'][formatDateOnly($t['tanggal_transaksi'])]['keluar'])){
                            $rs['bahan_baku'][$t['id_m_bahan_baku']]['transaksi'][formatDateOnly($t['tanggal_transaksi'])]['keluar'] += ($t['qty'] * $t['takaran']);
                        } else {
                            $rs['bahan_baku'][$t['id_m_bahan_baku']]['transaksi'][formatDateOnly($t['tanggal_transaksi'])]['keluar'] = ($t['qty'] * $t['takaran']);
                        }
                    }
                }
            }

            return $rs;
        }

        public function searchLaporanStockOpnameMenu($data){
            $tanggal = explodeRangeDate($data['range_tanggal']);
            $rs = null;
            $menu = $this->db->select('*')
                            ->from('m_menu_merchant')
                            ->where('flag_active', 1)
                            ->where('stock', 1)
                            ->where('id_m_merchant', $this->general_library->getIdMerchant())
                            ->order_by('nama_menu_merchant')
                            ->get()->result_array();
            
            foreach($menu as $m){
                $rs['menu'][$m['id']] = $m;
                $rs['menu'][$m['id']]['transaksi']['total'] = 0;
                $rs['menu'][$m['id']]['transaksi']['stock_awal'] = 0;
                $rs['menu'][$m['id']]['transaksi']['list'] = null;
            }

            $stock = $this->db->select('a.*')
                                ->from('t_stock_menu_merchant a')
                                ->join('m_menu_merchant b', 'a.id_m_menu_merchant = b.id')
                                ->where('b.id_m_merchant', $this->general_library->getIdMerchant())
                                ->where('a.tanggal >=', $tanggal[0].' 00:00:00')
                                ->where('a.tanggal <=', $tanggal[1].' 23:59:59')
                                ->where('a.flag_active', 1)
                                ->where('b.stock', 1)
                                ->order_by('a.tanggal', 'asc')
                                ->group_by('a.id')
                                ->get()->result_array();
                                // dd(json_encode($stock));
            if($stock){
                $i = 0;
                foreach($stock as $s){
                    if($rs['menu'][$s['id_m_menu_merchant']]['transaksi']['total'] == 0){
                        $rs['menu'][$s['id_m_menu_merchant']]['transaksi']['stock_awal'] = $s['jumlah_barang'];    
                    }

                    if(isset($rs['menu'][$s['id_m_menu_merchant']]['transaksi']['list'][formatDateOnly($s['tanggal'])])){
                        $rs['menu'][$s['id_m_menu_merchant']]['transaksi']['stock_awal'] += $s['jumlah_barang'];
                    }

                    $rs['menu'][$s['id_m_menu_merchant']]['transaksi']['total'] += $s['jumlah_barang'];
                    $rs['menu'][$s['id_m_menu_merchant']]['transaksi']['list'][formatDateOnly($s['tanggal'])]['tanggal'] = $s['tanggal'];
                    if(isset($rs['menu'][$s['id_m_menu_merchant']]['transaksi']['list'][formatDateOnly($s['tanggal'])]['masuk'])){
                        $rs['menu'][$s['id_m_menu_merchant']]['transaksi']['list'][formatDateOnly($s['tanggal'])]['masuk'] += $s['jumlah_barang'];
                    } else {
                        $rs['menu'][$s['id_m_menu_merchant']]['transaksi']['list'][formatDateOnly($s['tanggal'])]['masuk'] = $s['jumlah_barang'];
                    }
                    $i++;
                }
            }

            $transaksi_detail = $this->db->select('a.*')
                                        ->from('t_transaksi_detail a')
                                        ->join('t_transaksi b', 'a.id_t_transaksi = b.id')
                                        ->join('m_menu_merchant c', 'a.id_m_menu_merchant = c.id')
                                        ->where('b.id_m_merchant', $this->general_library->getIdMerchant())
                                        ->where('a.flag_active', 1)
                                        ->where('b.flag_active', 1)
                                        ->where('c.stock', 1)
                                        ->where('b.tanggal_transaksi >=', $tanggal[0].' 00:00:00')
                                        ->where('b.tanggal_transaksi <=', $tanggal[1].' 23:59:59')
                                        ->order_by('b.created_date', 'desc')
                                        ->group_by('a.id')
                                        ->get()->result_array();

            if($transaksi_detail){
                foreach($transaksi_detail as $td){
                    if(isset($rs['menu'][$td['id_m_menu_merchant']])){
                        $rs['menu'][$td['id_m_menu_merchant']]['transaksi']['total'] -= $td['qty'];
                        $rs['menu'][$td['id_m_menu_merchant']]['transaksi']['list'][formatDateOnly($td['created_date'])]['tanggal'] = $td['created_date'];
                        if(isset($rs['menu'][$td['id_m_menu_merchant']]['transaksi']['list'][formatDateOnly($td['created_date'])]['keluar'])){
                            $rs['menu'][$td['id_m_menu_merchant']]['transaksi']['list'][formatDateOnly($td['created_date'])]['keluar'] += $td['qty'];
                        } else {
                            $rs['menu'][$td['id_m_menu_merchant']]['transaksi']['list'][formatDateOnly($td['created_date'])]['keluar'] = $td['qty'];
                        }
                    }
                }
            }

            return $rs;
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

        public function searchLaporanPendapatan($data){
            $tanggal = explodeRangeDate($data['range_tanggal']);
            $rs['pendapatan_bersih'] = 0;
            $rs['total_penjualan'] = 0;
            $rs['total_pengeluaran'] = 0;
            $rs['total_penjualan_lunas'] = 0;
            $rs['total_penjualan_belum_lunas'] = 0;

            $rs['data_transaksi'] = $this->db->select('*, a.total_harga as nominal')
                                    ->from('t_transaksi a')
                                    ->where('a.tanggal_transaksi >=', $tanggal[0].' 00:00:00')
                                    ->where('a.tanggal_transaksi <=', $tanggal[1].' 23:59:59')
                                    ->where('a.id_m_merchant', $this->general_library->getIdMerchant())
                                    ->where('a.flag_active', 1)
                                    // ->order_by('a.status_transaksi', 'asc')
                                    ->order_by('a.tanggal_transaksi', 'desc')
                                    ->get()->result_array();
            $result = $rs['data_transaksi'];

            $rs['data_pengeluaran'] = $this->db->select('*')
                                    ->from('t_pengeluaran a')
                                    ->where('a.tanggal_transaksi >=', $tanggal[0].' 00:00:00')
                                    ->where('a.tanggal_transaksi <=', $tanggal[1].' 23:59:59')
                                    ->where('a.id_m_merchant', $this->general_library->getIdMerchant())
                                    ->order_by('a.tanggal_transaksi', 'desc')
                                    ->where('a.flag_active', 1)
                                    ->get()->result_array();
            if($rs['data_pengeluaran']){
                foreach($rs['data_pengeluaran'] as $rdp){
                    $result[] = $rdp;
                }
            }

            if($result){
                function comparator($object1, $object2) { 
                    return $object1['tanggal_transaksi'] > $object2['tanggal_transaksi']; 
                } 
                usort($result, 'comparator'); 

                $i = 0;
                foreach($result as $res){
                    if(isset($res['status_transaksi'])){
                        $rs['total_penjualan'] += $res['nominal'];
                        if($res['status_transaksi'] == 'Lunas'){
                            $rs['pendapatan_bersih'] += $res['nominal'];
                            $rs['total_penjualan_lunas'] += $res['nominal'];
                        } else if($res['status_transaksi'] == 'Belum Lunas'){
                            $rs['total_penjualan_belum_lunas'] += $res['nominal'];
                        }
                    }
                    if(isset($res['nomor_transaksi'])){
                        $rs['result'][$i]['nama_transaksi'] = $res['nama'] == "" || $res['nama'] == null ? "-" : $res['nama'];
                        $rs['result'][$i]['nama_transaksi'] = $res['nomor_transaksi'].' / '.$rs['result'][$i]['nama_transaksi'];
                        $rs['result'][$i]['jenis_transaksi'] = 'pendapatan';
                        $rs['result'][$i]['status_transaksi'] = $res['status_transaksi'];
                    } else {
                        $rs['pendapatan_bersih'] -= $res['nominal'];
                        $rs['total_pengeluaran'] += $res['nominal'];
                        $rs['result'][$i]['nama_transaksi'] = $res['nama_transaksi'];
                        $rs['result'][$i]['jenis_transaksi'] = 'pengeluaran';
                    }
                    $rs['result'][$i]['nominal'] = $res['nominal'];
                    $rs['result'][$i]['tanggal_transaksi'] = $res['tanggal_transaksi'];
                    $i++;
                }
            }

            return [$rs, $tanggal];
        }

	}
?>