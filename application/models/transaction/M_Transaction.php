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

            if($data['id'] != "0"){
                $trx_detail = json_decode($data['data'], true);
                if(!$trx_detail){ //cek jika data yang dikirim kosong
                    $this->db->trans_rollback();
                    $rs['code'] = 400;
                    $rs['message'] = 'Belum ada item yang dipilih';
                    return $rs;
                } else { // jika ada
                    $this->db->where('id', $data['id'])
                            ->update('t_transaksi',
                            [
                                'tanggal_transaksi' => $date[2].'-'.$date[1].'-'.$date[0].' '.$tanggal[1],
                                'nama' => $data['nama']
                            ]);
                    $detail = $this->db->select('*')
                                ->from('t_transaksi_detail')
                                ->where('flag_active', 1)
                                ->where('id_t_transaksi', $data['id'])
                                ->get()->result_array();
                    if(!$detail){ // cek jika detail di database kosong
                        $detail = [];
                        $i = 0;
                        $total_harga = 0;
                        foreach($trx_detail as $td){
                            $detail[$i]['id_t_transaksi'] = $data['id'];
                            $detail[$i]['id_m_menu_merchant'] = $td['id'];
                            $detail[$i]['harga'] = $td['harga'];
                            $detail[$i]['nama_menu_merchant'] = $td['nama_menu_merchant'];
                            $detail[$i]['qty'] = $td['selectedCount'];
                            $detail[$i]['created_by'] = $user['id_m_user'];

                            $total_harga_item = floatval($td['selectedCount']) * floatval($td['harga']);
                            $total_harga += $total_harga_item;
                            
                            $detail[$i]['total_harga'] = $total_harga_item;
                            $i++;
                        }

                        $this->db->insert_batch('t_transaksi_detail', $detail);

                        $this->db->where('id', $data['id'])
                                ->update('t_transaksi', ['total_harga' => $total_harga]);

                        $rs['data'] = $data['id'];
                    } else { //jika tidak kosong
                        $tempAdd = null;
                        $tempDel = null;
                        foreach($trx_detail as $td){
                            $flag_tambah = 1;
                            foreach($detail as $d){
                                if($td['id'] == $d['id_m_menu_merchant']){ //cek jika ada data yang dikirim dan belum ada di database
                                    if($td['selectedCount'] != $d['qty']){
                                        $this->db->where('id', $d['id'])
                                                ->update('t_transaksi_detail',[
                                                    'qty' => $td['selectedCount'],
                                                    'total_harga' => floatval($td['selectedCount']) * floatval($td['harga']),
                                                    'updated_by' => $user['id_m_user']
                                                ]);
                                    }
                                    $flag_tambah = 0;
                                }
                            }
                            if($flag_tambah == 1){ //jika tidak ada di database, tambah di list untuk di insert
                                $tempAdd[] = $td;
                            }
                        }

                        foreach($detail as $d){
                            $flag_hapus = 1;
                            foreach($trx_detail as $td){
                                if($d['id_m_menu_merchant'] == $td['id']){
                                    $flag_hapus = 0;
                                }
                            }
                            if($flag_hapus == 1){ //jika ada di database dan tidak ada di data yang dikirim, maka dihapus
                                $tempDel[] = $d['id'];
                            }
                        }

                        if($tempDel){
                            $this->db->where_in('id', $tempDel)
                                    ->update('t_transaksi_detail', [
                                        'flag_active' => 0,
                                        'updated_by' => $user['id_m_user']
                                    ]);
                        }

                        if($tempAdd){
                            $new_detail = [];
                            $i = 0;
                            foreach($tempAdd as $ta){
                                $new_detail[$i]['id_t_transaksi'] = $data['id'];
                                $new_detail[$i]['id_m_menu_merchant'] = $ta['id'];
                                $new_detail[$i]['harga'] = $ta['harga'];
                                $new_detail[$i]['nama_menu_merchant'] = $ta['nama_menu_merchant'];
                                $new_detail[$i]['qty'] = $ta['selectedCount'];
                                $new_detail[$i]['created_by'] = $user['id_m_user'];
                                $total_harga_item = floatval($td['selectedCount']) * floatval($td['harga']);
                                $total_harga += $total_harga_item;
                                
                                $new_detail[$i]['total_harga'] = $total_harga_item;
                                $i++;
                            }

                            $this->db->insert_batch('t_transaksi_detail', $new_detail);
                        }
                    }
                }
                $rs['data'] = $data['id'];        
            } else {
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

                    $total_harga_item = floatval($td['selectedCount']) * floatval($td['harga']);
                    $total_harga += $total_harga_item;
                    
                    $detail[$i]['total_harga'] = $total_harga_item;
                    $i++;
                }

                $this->db->insert_batch('t_transaksi_detail', $detail);

                $this->db->where('id', $lastId)
                        ->update('t_transaksi', ['total_harga' => $total_harga]);
                
                $rs['data'] = $lastId;
            }

            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $rs['code'] = 500;
                $rs['message'] = 'Terjadi Kesalahan';
            } else {
                $this->updateTotalHargaTransaksi($rs['data']);
                $this->db->trans_commit();
            }
            return $rs;
        }

        public function updateTotalHargaTransaksi($id){
            $detail = $this->db->select('*')
                                ->from('t_transaksi_detail')
                                ->where('flag_active', 1)
                                ->where('id_t_transaksi', $id)
                                ->get()->result_array();
            if($detail){
                $total_harga = 0;
                foreach($detail as $d){
                    $total_harga += (floatval($d['qty']) * floatval($d['harga']));
                }
                $this->db->where('id', $id)
                        ->update('t_transaksi', [
                            'total_harga' => $total_harga
                        ]);
            }
        }

        public function getPembayaranDetail($data, $user){
            $rs['code'] = 200;
            $rs['message'] = "";
            $rs['status'] = true;
            $rs['data'] = null;

            $pembayaran = $this->db->select('a.*, b.nama_jenis_pembayaran')
                                    ->from('t_pembayaran a')
                                    ->join('m_jenis_pembayaran b', 'a.id_m_jenis_pembayaran = b.id')
                                    ->where('a.id_t_transaksi', $data['id'])
                                    ->where('a.flag_active', 1)
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

        public function getTransactionDetail($data, $user){
            $rs['code'] = 200;
            $rs['message'] = "";
            $rs['status'] = true;
            $rs['data'] = null;
            
            $transaksi = $this->db->select('*')
                                    ->from('t_transaksi')
                                    ->where('id', $data['id'])
                                    ->where('flag_active', 1)
                                    ->get()->row_array();

            $transaksi_detail = $this->db->select('a.*, b.id_m_merchant, d.id as id_m_kategori_menu,
            d.id_m_jenis_menu, c.deskripsi, e.nama_jenis_menu, d.nama_kategori_menu, d.deskripsi')
                                    ->from('t_transaksi_detail a')
                                    ->join('t_transaksi b', 'a.id_t_transaksi = b.id')
                                    ->join('m_menu_merchant c', 'a.id_m_menu_merchant = c.id')
                                    ->join('m_kategori_menu d', 'c.id_m_kategori_menu = d.id')
                                    ->join('m_jenis_menu e', 'd.id_m_jenis_menu = e.id')
                                    ->where('a.id_t_transaksi', $data['id'])
                                    ->where('a.flag_active', 1)
                                    ->order_by('a.nama_menu_merchant')
                                    ->get()->result_array();

            $list_menu = $this->db->select('a.*, b.nama_jenis_menu, c.nama_kategori_menu')
                    ->from('m_menu_merchant a')
                    ->join('m_jenis_menu b', 'a.id_m_jenis_menu = b.id', 'left')
                    ->join('m_kategori_menu c', 'a.id_m_kategori_menu = c.id', 'left')
                    ->where('a.id_m_merchant', $user['id_m_merchant'])
                    ->where('a.flag_active', 1)
                    ->order_by('a.nama_menu_merchant', 'asc')
                    ->group_by('a.id')
                    ->get()->result_array();

            $result['transaksi'] = $transaksi;
            $result['list_menu'] = $list_menu;
            $result['transaksi']['detail'] = $transaksi_detail;
            $rs['data'] = $result;

            return $rs;
        }

        public function createPembayaran($data, $user){
            $data['id'] = $data['id_t_transaksi'];
            $rs['code'] = 200;
            $rs['message'] = "";
            $rs['status'] = true;
            $rs['data'] = null;

            $this->db->trans_begin();

            $exists = $this->db->select('*')
                            ->from('t_pembayaran')
                            ->where('id_t_transaksi', $data['id_t_transaksi'])
                            ->where('flag_active', 1)
                            ->get()->row_array();
            if($exists){
                $this->db->where('id', $data['id_t_transaksi'])
                        ->update('t_transaksi', [
                            'status_transaksi' => 'Lunas',
                            'updated_by' => $user['id_m_user']
                        ]);

                $rs['code'] = 409;
                $rs['message'] = "Sudah ada data pembayaran";
                $rs['status'] = false; 
                $rs['data'] = $this->getPembayaranDetail($data, $user)['data'];
                $this->db->trans_commit();
                return $rs;
            } else {
                $tanggal_pembayaran = extractTanggalWs($data['tanggal_pembayaran']);

                $this->db->insert('t_pembayaran', [
                    'id_t_transaksi' => $data['id_t_transaksi'],
                    'id_m_merchant' => $data['id_m_merchant'],
                    'tanggal_pembayaran' => $tanggal_pembayaran['formatted'],
                    'nama_pembayar' => $data['nama_pembayar'],
                    'id_m_jenis_pembayaran' => $data['id_m_jenis_pembayaran'],
                    'total_pembayaran' => $data['total_pembayaran'],
                    'kembalian' => clearString($data['kembalian']),
                    'nomor_pembayaran' => str_pad($data['id_m_merchant'], 4, '0', STR_PAD_LEFT).date('ymdhis'),
                    'created_by' => $user['id_m_user']
                ]);

                $this->db->where('id', $data['id_t_transaksi'])
                        ->update('t_transaksi', [
                            'status_transaksi' => 'Lunas',
                            'updated_by' => $user['id_m_user']
                        ]);
            }

            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $rs['code'] = 500;
                $rs['message'] = 'Terjadi Kesalahan';
            } else {
                $this->db->trans_commit();
                $rs['code'] = 201;
                $rs['message'] = "Pembayaran berhasil dilakukan";
                $rs['status'] = true;
                $rs['data'] = $this->getPembayaranDetail($data, $user)['data'];
            }

            return $rs;
        }

        public function deletePembayaran($data, $user){
            $rs['code'] = 200;
            $rs['message'] = "";
            $rs['status'] = true;
            $rs['data'] = null;

            $this->db->trans_begin();

            $pembayaran = $this->db->select('*')
                                ->from('t_pembayaran')
                                ->where('id', $data['id'])
                                ->where('flag_active', 1)
                                ->get()->row_array();
            if($pembayaran){
                $this->db->where('id', $data['id'])
                ->update('t_pembayaran', [
                    'flag_active' => 0,
                    'updated_by' => $user['id_m_user']
                ]);

                $this->db->where('id', $pembayaran['id_t_transaksi'])
                ->update('t_transaksi', [
                    'status_transaksi' => "Belum Lunas",
                    'updated_by' => $user['id_m_user']
                ]);

                $data['id'] = $pembayaran['id_t_transaksi'];
            } else {
                return null;
            }

            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $rs['code'] = 500;
                $rs['message'] = 'Terjadi Kesalahan';
            } else {
                $this->db->trans_commit();
                $rs['code'] = 200;
                $rs['message'] = "Pembayaran berhasil dihapus";
                $rs['status'] = true;
                $rs['data'] = $this->getPembayaranDetail($data, $user)['data'];
            }

            return $rs;
        }

        public function getDataDashboard($data, $user){
            $rs['code'] = 200;
            $rs['message'] = "Refresh Data Berhasil";
            $rs['status'] = true;
            $rs['data'] = null;

            $date = date('Y-m-d');

            if(isset($data['tanggal'])){
                $date = $data['tanggal'];
            }

            $rs['data']['total_penjualan'] = 0;
            $rs['data']['total_penjualan_lunas'] = 0;
            $rs['data']['total_penjualan_belum_lunas'] = 0;
            $rs['data']['total_transaksi'] = 0;
            $rs['data']['total_transaksi_lunas'] = 0;
            $rs['data']['total_transaksi_belum_lunas'] = 0;
            $rs['data']['total_item'] = 0;
            $rs['data']['total_item_lunas'] = 0;
            $rs['data']['total_item_belum_lunas'] = 0;

            $list_id_transaksi = [];

            $data_transaksi = $this->db->select('*, a.id as id_transaksi, b.id as id_transaksi_detail, b.total_harga as harga_detail')
                                    ->from('t_transaksi a')
                                    ->join('t_transaksi_detail b',' a.id = b.id_t_transaksi')
                                    ->where('a.tanggal_transaksi >=', $date.' 00:00:00')
                                    ->where('a.tanggal_transaksi <=', $date.' 23:59:59')
                                    ->where('a.id_m_merchant', $user['id_m_merchant'])
                                    ->where('a.flag_active', 1)
                                    ->where('b.flag_active', 1)
                                    ->order_by('a.id')
                                    ->get()->result_array();

            if($data_transaksi){
                foreach($data_transaksi as $dt){
                    $flag_lunas = $dt['status_transaksi'] == 'Belum Lunas' ? 0 : 1;
                    $rs['data']['total_penjualan'] += $dt['harga_detail'];
                    $rs['data']['total_item'] += $dt['qty'];
                    if($flag_lunas == 1){
                        $rs['data']['total_penjualan_lunas'] += $dt['harga_detail'];
                        $rs['data']['total_item_lunas'] += $dt['qty'];
                    } else {
                        $rs['data']['total_penjualan_belum_lunas'] += $dt['harga_detail'];
                        $rs['data']['total_item_belum_lunas'] += $dt['qty'];
                    }
                    if(!in_array($dt['id_transaksi'], $list_id_transaksi)){
                        if($flag_lunas == 1){
                            $rs['data']['total_transaksi_lunas']++;
                        } else {
                            $rs['data']['total_transaksi_belum_lunas']++;
                        }
                        array_push($list_id_transaksi, $dt['id_transaksi']); 
                    }
                }
                $rs['data']['total_transaksi'] = count($list_id_transaksi);
            }

            return $rs;
        }

        public function getAllTransaction($data, $user){
            $rs['code'] = 200;
            $rs['message'] = "Refresh Data Berhasil";
            $rs['status'] = true;
            $rs['data'] = null;

            $tanggal = explode("/", $data['tanggal']);

            $temp = $this->db->select('a.*, b.qty, b.harga, b.total_harga, b.nama_menu_merchant, a.total_harga as total_harga_transaksi')
                                ->from('t_transaksi a')
                                ->join('t_transaksi_detail b', 'a.id = b.id_t_transaksi')
                                ->where('YEAR(a.tanggal_transaksi)', $tanggal[2])
                                ->where('MONTH(a.tanggal_transaksi)', $tanggal[1])
                                ->where('DAY(a.tanggal_transaksi)', $tanggal[0])
                                ->where('a.flag_active', 1)
                                ->where('b.flag_active', 1)
                                ->where('a.id_m_merchant', $user['id_m_merchant'])
                                ->order_by('a.status_transaksi', 'asc')
                                ->order_by('a.tanggal_transaksi', 'desc')
                                ->get()->result_array();
            $result = array();
            $finalresult = array();
            if($temp){
                foreach($temp as $t){
                    if(isset($result[$t['id']])){
                        $result[$t['id']]['total_item'] += $t['qty'];
                        $result[$t['id']]['list_nama_item'] = $result[$t['id']]['list_nama_item'].', '.$t['qty'].' '.$t['nama_menu_merchant'];
                    } else {
                        $result[$t['id']]['id'] = $t['id'];
                        $result[$t['id']]['id_m_merchant'] = $t['id_m_merchant'];
                        $result[$t['id']]['tanggal_transaksi'] = $t['tanggal_transaksi'];
                        $result[$t['id']]['nomor_transaksi'] = $t['nomor_transaksi'];
                        $result[$t['id']]['nama'] = $t['nama'];
                        $result[$t['id']]['total_harga'] = $t['total_harga_transaksi'];
                        $result[$t['id']]['status_transaksi'] = $t['status_transaksi'];
                        $result[$t['id']]['total_item'] = $t['qty'];
                        $result[$t['id']]['list_nama_item'] = $t['qty'].' '.$t['nama_menu_merchant'];
                    }
                }
                if($result){
                    foreach($result as $res){
                        $finalresult[] = $res;
                    }
                }
            }

            $rs['data'] = $finalresult;
            return $rs;
        }
	}
?>