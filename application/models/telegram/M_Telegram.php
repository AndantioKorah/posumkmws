<?php
	class M_Telegram extends CI_Model
	{
		public function __construct()
        {
            parent::__construct();
            $this->db = $this->load->database('main', true);
            date_default_timezone_set("Asia/Singapore");
        }

        public function insert($tablename, $data){
            $this->db->insert($tablename, $data);
        }

        public function insertBulk($tablename, $data){
            $this->db->insert_batch($tablename, $data);
        }

        public function saveLog($data){
            $this->db->insert('t_mm_log_chat', [
                'chat_id' => $data->message->chat->id,
                'username' => $data->message->chat->username,
                'nama' => $data->message->chat->first_name." ".$data->message->chat->last_name,
                'text' => $data->message->text
            ]);
        }

        public function checkIfUserExists($data){
            return $this->db->select('*')
                            ->from('m_mm_user')
                            ->where('chat_id', $data->message->chat->id)
                            ->where('flag_active', 1)
                            ->get()->row_array();   
        }

        public function getCurrentSession($data){
            return $this->db->select('*')
                            ->from('t_mm_session')
                            ->where('chat_id', $data->message->chat->id)
                            ->where('flag_active', 1)
                            ->get()->row_array();
        }

        public function setSession($data, $bulan, $tahun){
            $this->db->where('chat_id', $data->message->chat->id)
                    ->where('flag_active', 1)
                    ->update('t_mm_session', ['flag_active' => 0]);

            $this->db->insert('t_mm_session', [
                'chat_id' => $data->message->chat->id,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'mode' => 'min'
            ]);
        }

        public function changeModeSession($data, $mode){
            $this->db->where('chat_id', $data->message->chat->id)
                    ->where('flag_active', 1)
                    ->update('t_mm_session', ['mode' => $mode]);
        }

        public function registerUser($data){
            $exists = $this->db->select('*')
                            ->from('m_mm_user')
                            ->where('chat_id', $data->message->chat->id)
                            ->where('flag_active', 1)
                            ->get()->row_array();
            if($exists){
                return "User sudah terdaftar sebelumnya";
            } else {
                $this->tele->insert('m_mm_user', [
                    'chat_id' => $data->message->chat->id,
                    'username' => $data->message->chat->username,
                    'nama' => $data->message->chat->first_name." ".$data->message->chat->last_name
                ]);
                return "User berhasil didaftarkan";
            }
        }

        public function countTransaksi($data, $session){
            $result['total'] = 0;
            $result['list_transaksi'] = null;
            $result['pemasukan'] = 0;
            $result['pengeluaran'] = 0;
            $all = $this->db->select('*')
                            ->from('t_mm_transaksi')
                            ->where('flag_active', 1)
                            ->where('bulan', $session['bulan'])
                            ->where('tahun', $session['tahun'])
                            ->where('chat_id', $data->message->chat->id)
                            ->order_by('created_date')
                            ->get()->result_array();
            if(count($all) > 0){
                foreach($all as $a){
                    $result['list_transaksi'][] = $a;
                    if($a['jenis_transaksi'] == 'plus'){
                        $result['total'] = floatval($result['total']) + floatval($a['nominal']);
                        $result['pemasukan'] += floatval($a['nominal']);
                    } else if($a['jenis_transaksi'] == 'min'){
                        $result['total'] = floatval($result['total']) - floatval($a['nominal']);
                        $result['pengeluaran'] += floatval($a['nominal']);
                    }
                }
            }
            return $result;
        }

        public function clearTransaksi($data, $session){
            $this->db->where('bulan', $session['bulan'])
                    ->where('tahun', $session['tahun'])
                    ->where('flag_active', 1)
                    ->where('chat_id', $data->message->chat->id)
                    ->update('t_mm_transaksi', ['flag_active' => 0]);
        }

        public function removeTransaksiByOrder($data, $session, $num){
            $match = null;
            $all = $this->db->select('*')
                            ->from('t_mm_transaksi')
                            ->where('flag_active', 1)
                            ->where('bulan', $session['bulan'])
                            ->where('tahun', $session['tahun'])
                            ->where('chat_id', $data->message->chat->id)
                            ->order_by('created_date')
                            ->get()->result_array();
            if(count($all) > 0){
                $i = 1;
                foreach($all as $a){
                    if($i == $num){
                        $match = $a;
                        $this->db->where('id', $a['id'])
                                ->update('t_mm_transaksi', ['flag_active' => 0]);
                        break;
                    }
                    $i++;
                }
            }
            return $match;
        }
	}
?>