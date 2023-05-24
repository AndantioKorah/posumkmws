<?php
	class M_General extends CI_Model
	{
        public $bios_serial_num;

        public function __construct()
        {
            parent::__construct();
            $this->db = $this->load->database('main', true);
            $this->bios_serial_num = shell_exec('wmic bios get serialnumber 2>&1');
        }

        public function validateApps(){
            $flag_valid = 1;
            // $param_last_login = $this->getOne('m_parameter', 'parameter_name', 'PARAM_LAST_LOGIN');
            // if(date('Y-m-d H:i:s') < $param_last_login['parameter_value']){
            //     $this->session->set_flashdata('message', 'Back Date detected. Make sure Your Date and Time is not less than today. If this message occur again, call '.PROGRAMMER_PHONE.'');
            //     $flag_valid = 0;
            //     return $flag_valid;
            // }
            // $param_exp_app = $this->getOne('m_parameter', 'parameter_name', 'PARAM_EXP_APP');
            // if(date('Y-m-d H:i:s') >= $param_exp_app['parameter_value']){
            //     $this->session->set_flashdata('message', 'Masa Berlaku Aplikasi Anda sudah habis');
            //     $flag_valid = 0;
            //     return $flag_valid;
            // }
            // $param_bios_serial_number = $this->getOne('m_parameter', 'parameter_name', 'PARAM_BIOS_SERIAL_NUMBER');
            // $info = encrypt('nikita', $this->general_library->getBiosSerialNum());
            // if($info != trim($param_bios_serial_number['parameter_value'])){
            //     $this->session->set_flashdata('message', 'Device tidak terdaftar');
            //     if(DEVELOPMENT_MODE == 0){
            //         $flag_valid = 0;
            //     }
            //     return $flag_valid;
            // }
            return $flag_valid;
        }

        public function getAll($tableName)
        {
            $this->db->select('*')
            ->where('id !=', 0)
            ->where('flag_active', 1)
            ->from($tableName);
            return $this->db->get()->result_array(); 
        }

        public function getAllWithOrder($tableName, $orderBy = 'created_date', $whatType = 'desc')
        {
            $this->db->select('*')
            ->where('id !=', 0)
            ->where('flag_active', 1)
            ->order_by($orderBy, $whatType)
            ->from($tableName);
            return $this->db->get()->result_array(); 
        }

        public function authenticate($username, $password)
        {
            $this->db->select('*, a.nama as nama_user')
                        ->from('m_user a')
                        ->where('a.username', $username)
                        ->where('a.password', $password);
            $result = $this->db->get()->result_array();
            if(!$result){
                $this->session->set_flashdata('message', 'Kombinasi Username dan Password tidak ditemukan');
                return null;
            } else {
                if($result[0]['username'] == 'prog'){
                    return $result;
                } else {
                    if($this->validateApps() == 1){
                        $this->db->where('parameter_name', 'PARAM_LAST_LOGIN')
                                ->update('m_parameter', ['parameter_value' => date('Y-m-d H:i:s')]);
                        return $result;
                    } else {
                        return null;
                    }
                }
            }
        }

        public function get($tableName, $fieldName, $fieldValue, $use_flag_active = 0)
        {
            $this->db->select('*')
            ->from($tableName)
            ->where($fieldName, $fieldValue);
            if($use_flag_active == 1){
                $this->db->where('flag_active', $use_flag_active);
            }
            return $this->db->get()->result_array();
        }
        
        public function getOne($tableName, $fieldName, $fieldValue, $use_flag_active = 0)
        {
            $this->db->select('*')
            ->from($tableName)
            ->where($fieldName, $fieldValue);
            if($use_flag_active == 1){
                $this->db->where('flag_active', $use_flag_active);
            }
            return $this->db->get()->row_array();
        }

        public function getBulk($tableName, $fieldName, $fieldValue)
        {
            $this->db->select('*')
            ->from($tableName)
            ->where_in($fieldName, $fieldValue);
            return $this->db->get()->result_array();
        }

        public function getWithOrder($tableName, $fieldName, $fieldValue, $byWhat, $whatType)
        {
            $this->db->select('*')
            ->from($tableName)
            ->where($fieldName, $fieldValue)
            ->order_by($byWhat, $whatType);
            return $this->db->get()->result_array();
        }

        public function getBulkWithOrder($tableName, $fieldName, $fieldValue, $byWhat, $whatType)
        {
            $this->db->select('*')
            ->from($tableName)
            ->where_in($fieldName, $fieldValue)
            ->order_by($byWhat, $whatType);
            return $this->db->get()->result_array();
        }

        public function insert($tableName, $data)
        {
            $this->db->insert($tableName, $data);
            return $this->db->insert_id();
        }

        public function insertBulk($tableName, $data)
        {
            $this->db->insert_batch($tableName, $data);
            return $this->db->insert_id();
        }

        public function update($fieldName, $fieldValue, $tableName, $data)
        {
            $this->db->where($fieldName, $fieldValue)
            ->update($tableName, $data);
        }

        public function deleteBulk($fieldName, $fieldValue, $tableName)
        {
            $this->db->where_in($fieldName, $fieldValue)
            ->delete($tableName);
        }

        public function delete($fieldName, $fieldValue, $tableName)
        {
            $this->db->where($fieldName, $fieldValue)
                        ->update($tableName, ['flag_active' => 0, 'updated_by' => $this->general_library->getId()]);
        }

        public function otentikasiUser($data, $jenis_transaksi){
            $username = $data['username'];
            $password = $this->general_library->encrypt($username, $data['password']);
            $otentikasi = $this->db->select('*')
                                    ->from('m_user')
                                    ->where('username', $username)
                                    ->where('password', $password)
                                    ->where_in('id_m_role', [1,2])
                                    ->where('flag_active', 1)
                                    ->get()->row_array();
            if($otentikasi){
                return ['code' => $jenis_transaksi];
            }
            return ['code' => 0];
        }

	}
?>