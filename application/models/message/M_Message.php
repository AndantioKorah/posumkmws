<?php
	class M_Message extends CI_Model
	{
		public function __construct()
        {
            parent::__construct();
            $this->db = $this->load->database('main', true);
            $this->load->library('Nexmolib', 'nexmolib');
        }

        public function insert($tablename, $data){
            $this->db->insert($tablename, $data);
        }

        public function sendBulkMessage($data){
            $ret['code'] = 0;
            $ret['message'] = 'OK';
            $ret['last_id'] = null;

            $this->db->trans_begin();

            $jenisPesan = explode(";", $data['id_m_jenis_pesan']);
            $pesan['id_m_jenis_pesan'] = $jenisPesan[0];
            $pesan['jenis_pesan'] = $jenisPesan[1];
            $pesan['flag_bulk_message'] = 1;
            $pesan['flag_all_done'] = 0;
            $pesan['created_by'] = $this->general_library->getId();
            $this->db->insert('t_message', $pesan);
            $last_id = $this->db->insert_id();
            $ret['last_id'] = $last_id;
            // $last_id = 0;

            $numbers = explode("\n",$data['nomor_tujuan']);
            $pesan_detail = null;
            $i = 0;
            foreach($numbers as $n){
                $pesan_detail[$i]['id_t_message'] = $last_id;
                $pesan_detail[$i]['nomor_tujuan'] = $n;
                $pesan_detail[$i]['isi_pesan'] = $data['isi_pesan'];
                $pesan_detail[$i]['created_by'] = $this->general_library->getId();
                $i++;
            }
            $this->db->insert_batch('t_message_detail', $pesan_detail);

            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $rs['code'] = 1;
                $rs['message'] = 'Terjadi Kesalahan';
            } else {
                $this->db->trans_commit();
            }

            return $ret;
        }

        public function progressSendBulkMessage($id){
            $allMessageDetail = $this->db->select('*')
                                ->from('t_message_detail')
                                ->where('id_t_message', $id)
                                ->where('flag_active', 1)
                                ->get()->result_array();

            $messageDetail = $this->db->select('*')
                                    ->from('t_message_detail')
                                    ->where('id_t_message', $id)
                                    ->where('flag_active', 1)
                                    ->where('request', null)
                                    ->limit(1)
                                    ->get()->row_array();
            if($messageDetail){
                $sendMessage = $this->nexmolib->sendMessage('POST', $messageDetail);

                $rsp = json_decode($sendMessage['response'], true);
                $message = $sendMessage['message'];
                $code = $rsp['messages'][0]['status'];
                if($code != '0'){
                    $message = $rsp['messages'][0]['error-text'];
                }

                $messageDetail['request'] = $sendMessage['request'];
                $messageDetail['response'] = $sendMessage['response'];
                $messageDetail['response_message'] = $message;
                $messageDetail['updated_by'] = $this->general_library->getId();

                $this->db->where('id', $messageDetail['id'])
                        ->update('t_message_detail', $messageDetail);
            } else {
                $this->db->where('id', $id)
                        ->update('t_message', ['flag_all_done' => 1]);
            }

            return $allMessageDetail;
        }

	}
?>