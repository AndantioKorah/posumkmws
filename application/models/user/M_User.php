<?php
	class M_User extends CI_Model
	{
		public function __construct()
        {
            parent::__construct();
            $this->db = $this->load->database('main', true);
        }

        public function insert($tablename, $data){
            $this->db->insert($tablename, $data);
        }

        public function userChangePassword($data){
            $rs['code'] = 0;
            $rs['message'] = '';
            $user = $this->db->select('*')
                            ->from('m_user')
                            ->where('id', $data['id_m_user'])
                            ->get()->row_array();
            if($user){
                if($data['new_password'] != $data['confirm_new_password']){
                    $rs['code'] = 2;
                    $rs['message'] = 'Password Baru dan Konfirmasi Password Baru tidak sama !';    
                } else {
                    $new_password = $this->general_library->encrypt($user['username'], $data['new_password']);
                    $update['password'] = $new_password;
                    $update['updated_by'] = $this->general_library->getId();
                    $this->db->where('id', $data['id_m_user'])
                            ->update('m_user', $update);
                }
            } else {
                $rs['code'] = 1;
                $rs['message'] = 'Terjadi Kesalahan ; Error Code : 1';
            }

            return $rs;
        }

        public function getAllUsers(){
            return $this->db->select('a.*, a.nama as nama_user, b.nama_merchant')
                            ->from('m_user a')
                            ->join('m_merchant b', 'a.id_m_merchant = b.id', 'left')
                            ->where('a.flag_active', 1)
                            ->order_by('a.nama')
                            ->get()->result_array();
        }

        public function createUser($data){
            if($data['password'] != $data['konfirmasi_password']){
                return ['message' => 'Password dan Konfirmasi Password harus sama'];
            }
            if(strlen($data['password']) < 6){
                return ['message' => 'Panjang Password harus lebih dari 6 karakter'];
            }
            $exist = $this->db->select('username')
                                ->from('m_user')
                                ->where('username', $data['username'])
                                ->where('flag_active', 1)
                                ->get()->row_array();
            if($exist){
                return ['message' => 'Username sudah digunakan'];
            }
            unset($data['konfirmasi_password']);
            $data['password'] = $this->general_library->encrypt($data['username'], $data['password']);
            $this->db->insert('m_user', $data);
            return ['message' => '0'];
        }

        public function deleteUser($id_m_user){
            $this->db->where('id', $id_m_user)
                ->update('m_user', ['flag_active' => 0, 'updated_by' => $this->general_library->getId()]);
        }

        public function getUserRole($id_m_user){
            return $this->db->select('a.*, b.nama as nama_role, b.keterangan, b.role_name as role')
                            ->from('m_user_role a')
                            ->join('m_role b', 'a.id_m_role = b.id')
                            ->where('a.id_m_user', $id_m_user)
                            ->where('a.flag_active', 1)
                            ->order_by('a.is_default', 'desc')
                            ->order_by('b.nama', 'asc')
                            ->get()->result_array();
        }

        public function addRoleForUser($data){
            $rs['code'] = 0;
            $rs['message'] = 'OK';

            $this->db->trans_begin();

            $exist = $this->db->select('*')
                            ->from('m_user_role')
                            ->where('id_m_user', $data['id_m_user'])
                            ->where('id_m_role', $data['id_m_role'])
                            ->where('flag_active', 1)
                            ->get()->row_array();
            if(!$exist){
                $data['created_by'] = $this->general_library->getId();
                $this->db->insert('m_user_role', $data);
            } else {
                $rs['code'] = 1;
                $rs['message'] = 'User sudah memiliki Role tersebut';
            }

            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $rs['code'] = 1;
                $rs['message'] = 'Terjadi Kesalahan';
            } else {
                $this->db->trans_commit();
            }

            return $rs;
        }

        public function changePassword($data){
            if($data['password_baru'] != $data['konfirmasi_password']){
                return ['message' => 'Password Baru dan Konfirmasi Password Baru tidak sama'];
            }
            $password_lama = $this->general_library->encrypt($this->general_library->getUserName(), $data['password_lama']);
            $user = $this->db->select('*, a.nama as nama_user')
                                ->from('m_user a')
                                ->where('a.username', $this->general_library->getUserName())
                                ->where('a.password', $password_lama)
                                ->get()->result_array();
            if(!$user){
                return ['message' => 'Password Lama salah'];                
            } else {
                if(strlen($data['password_baru']) < 6){
                    return ['message' => 'Panjang Password harus lebih dari 6 karakter'];
                }
                $password_baru = $this->general_library->encrypt($this->general_library->getUserName(), $data['password_baru']);
                $this->db->where('id', $this->general_library->getId())
                        ->update('m_user', ['password' => $password_baru]);
                if($this->db->affected_rows() > 0){
                    $this->session->set_userdata(['user_logged_in' => null]);
                    $user[0]['password'] = $password_baru;
                    $this->session->set_userdata([
                        'user_logged_in' => $user,
                        'test' => 'tiokors'
                    ]);
                    $this->general_library->refreshUserLoggedInData();
                    return ['message' => '0'];
                }
            }
            return ['message' => 'Terjadi Kesalahan'];
        }

        public function updateProfile($data){
            $this->db->where('id', $this->general_library->getId())
                        ->update('m_user', $data);

            if($this->db->affected_rows() > 0){
                $this->session->set_userdata(['user_logged_in' => null]);

                $user = $this->db->select('*, a.nama as nama_user, b.nama as nama_role')
                            ->from('m_user a')
                            ->join('m_role b', 'a.id_m_role = b.id')
                            ->where('a.id', $this->general_library->getId())
                            ->get()->result_array();

                $this->session->set_userdata([
                    'user_logged_in' => $user,
                    'test' => 'tiokors'
                ]);
                $this->general_library->refreshUserLoggedInData();
                return ['message' => '0'];
            }

            return ['message' => 'Terjadi Kesalahan'];
        }

        public function deleteProfilePict(){
            $this->db->where('id', $this->general_library->getId())
                        ->update('m_user', ['profile_picture' => null]);

            if($this->db->affected_rows() > 0){
                $this->session->set_userdata(['user_logged_in' => null]);

                $user = $this->db->select('*, a.nama as nama_user, b.nama as nama_role')
                            ->from('m_user a')
                            ->join('m_role b', 'a.id_m_role = b.id')
                            ->where('a.id', $this->general_library->getId())
                            ->get()->result_array();

                $this->session->set_userdata([
                    'user_logged_in' => $user,
                    'test' => 'tiokors'
                ]);
                $this->general_library->refreshUserLoggedInData();
                return ['message' => '0'];
            }

            return ['message' => 'Terjadi Kesalahan'];
        }

        public function updateProfilePicture($data){
            $this->db->where('id', $this->general_library->getId())
                        ->update('m_user', ['profile_picture' => $data['data']['file_name']]);

            if($this->db->affected_rows() > 0){
                $this->session->set_userdata(['user_logged_in' => null]);
                
                $user = $this->db->select('*, a.nama as nama_user, b.nama as nama_role')
                            ->from('m_user a')
                            ->join('m_role b', 'a.id_m_role = b.id')
                            ->where('a.id', $this->general_library->getId())
                            ->get()->result_array();

                $this->session->set_userdata([
                    'user_logged_in' => $user,
                    'test' => 'tiokors'
                ]);
                $this->general_library->refreshUserLoggedInData();
                return ['message' => '0'];
            }

            return ['message' => 'Terjadi Kesalahan'];
        }

        public function updateExpDateApp($data){
            $user = $this->db->select('*, a.nama as nama_user, b.nama as nama_role')
                            ->from('m_user a')
                            ->join('m_role b', 'a.id_m_role = b.id')
                            ->where('a.username', 'prog')
                            ->where('a.flag_active', 1)
                            ->get()->row_array();
            if($user){
                if($data['username'] != $user['username']){
                    return ['message' => 'Bukan User untuk Programmer'];
                }
                $password = $this->general_library->encrypt($data['username'], $data['password']);
                if($user['password'] != $password){
                    return ['message' => 'Password yang dimasukkan salahsssss'];
                }
                $second_password = $this->general_library->encrypt($data['username'], $data['second_password']);
                if($second_password != SECOND_PASSWORD){
                    return ['message' => 'Password yang dimasukkan salah'];
                }
                $this->db->where('parameter_name', $data['param_name'])
                            ->update('m_parameter', ['parameter_value' => $data['parameter_value_new'].' 23:59:59', 'updated_by' => $this->general_library->getId()]);
                if($this->db->affected_rows() > 0){
                    $this->session->set_userdata(['params' => null]);
                    
                    $params = $this->db->select('*')
                                ->from('m_parameter')
                                ->where('flag_active', 1)
                                ->get()->result_array();
                    // dd($params);
                    $this->session->set_userdata([
                        'params' => $params
                    ]);
                    // dd($this->session);
                    // $this->general_library->refreshParams();
                    return ['message' => 0];
                } else {
                    return ['message' => 'Terjadi Kesalahan'];
                }
            }
            return ['message' => 'Terjadi Kesalahan'];
        }

        public function createMenu($data){
            $rs['code'] = 0;
            $rs['message'] = 'OK';

            $this->db->trans_begin();

            $exist = null;
            if($data['url'] != '#' && $data['url'] != ''){
                $exist = $this->db->select('*')
                            ->from('m_menu')
                            ->where('url', $data['url'])
                            ->where('flag_active', 1)
                            ->get()->row_array();
            }

            if(!$exist){
                $data['created_by'] = $this->general_library->getId();
                $this->db->insert('m_menu', $data);
            } else {
                $rs['code'] = 1;
                $rs['message'] = 'URL sudah terpakai untuk Menu lain';
            }

            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $rs['code'] = 1;
                $rs['message'] = 'Terjadi Kesalahan';
            } else {
                $this->db->trans_commit();
            }

            return $rs;
        }

        public function loadAllMenu(){
            return $this->db->select('a.*, b.nama_menu as nama_menu_parent')
                            ->from('m_menu a')
                            ->join('m_menu b', 'a.id_m_menu_parent = b.id', 'left')
                            ->where('a.flag_active', 1)
                            ->order_by('a.nama_menu')
                            ->group_by('a.id')
                            ->get()->result_array();
        }

        public function getListMenu($id_role, $role_name){
            $this->db->select('a.*')
                    ->from('m_menu a')
                    ->where('a.id_m_menu_parent', 0)
                    ->where('a.flag_active', 1)
                    ->order_by('a.nama_menu', 'asc')
                    ->group_by('a.id');
            if($role_name != 'programmer'){
                $this->db->join('m_menu_role b', 'b.id_m_menu = a.id')
                        ->where('b.id_m_role', $id_role)    
                        ->where('b.flag_active', 1);    
            }
            $list_menu = $this->db->get()->result_array();
            if($list_menu){
                $i = 0;
                foreach($list_menu as $l){
                    $list_menu[$i]['child'] = null;
                    $this->db->select('*')
                            ->from('m_menu a')
                            ->where('a.id_m_menu_parent', $l['id'])
                            ->where('a.flag_active', 1)
                            ->order_by('a.nama_menu', 'asc');
                    if($role_name != 'programmer'){
                        $this->db->join('m_menu_role b', 'b.id_m_menu = a.id')
                                ->where('b.id_m_role', $id_role)    
                                ->where('b.flag_active', 1);    
                    }
                    $list_menu[$i]['child'] = $this->db->get()->result_array();
                    $i++;
                }
            }
            return $list_menu;
        }

        public function getMenuRole($id){
            return $this->db->select('a.*, b.nama as nama_role, b.keterangan, b.role_name as role')
                            ->from('m_menu_role a')
                            ->join('m_role b', 'a.id_m_role = b.id')
                            ->where('a.id_m_menu', $id)
                            ->where('a.flag_active', 1)
                            ->order_by('b.nama')
                            ->group_by('b.id')
                            ->get()->result_array();
        }

        public function insertRoleForMenu($data){
            $rs['code'] = 0;
            $rs['message'] = 'OK';

            $this->db->trans_begin();

            $exist = $this->db->select('*')
                            ->from('m_menu_role')
                            ->where('id_m_menu', $data['id_m_menu'])
                            ->where('id_m_role', $data['id_m_role'])
                            ->where('flag_active', 1)
                            ->get()->row_array();
            if(!$exist){
                $data['created_by'] = $this->general_library->getId();
                $this->db->insert('m_menu_role', $data);
            } else {
                $rs['code'] = 1;
                $rs['message'] = 'Menu sudah memiliki Role tersebut';
            }

            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $rs['code'] = 1;
                $rs['message'] = 'Terjadi Kesalahan';
            } else {
                $this->db->trans_commit();
            }

            return $rs;
        }

        public function getListRoleForUser($id){
            return $this->db->select('a.is_default, b.*')
                            ->from('m_user_role a')
                            ->join('m_role b', 'a.id_m_role = b.id')
                            ->where('a.id_m_user', $id)
                            ->where('a.flag_active', 1)
                            ->order_by('a.is_default', 'desc')
                            ->order_by('b.nama', 'asc')
                            ->get()->result_array();
        }

        public function setDefaultRoleForUser($id_user_role, $id_user){
            $rs['code'] = 0;
            $rs['message'] = 'OK';

            $this->db->trans_begin();

            $this->db->where('id_m_user', $id_user)
                    ->update('m_user_role',
                    [
                        'is_default' => 0,
                        'updated_by' => $this->general_library->getId()
                    ]);
            
            $this->db->where('id', $id_user_role)
                    ->update('m_user_role',
                    [
                        'is_default' => 1,
                        'updated_by' => $this->general_library->getId()
                    ]);

            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $rs['code'] = 1;
                $rs['message'] = 'Terjadi Kesalahan';
            } else {
                $this->db->trans_commit();
            }

            return $rs;
        }

        public function deleteRole($id){
            $rs['code'] = 0;
            $rs['message'] = 'OK';

            $this->db->trans_begin();

            if($id == 5 || $id == $this->session->userdata('active_role_id')){
                $rs['code'] = 1;
                $rs['message'] = 'Untuk sementara, Role ini tidak dapat dihapus';
            } else {
                $this->db->where('id', $id)
                        ->update('m_role',
                        [
                            'flag_active' => 0,
                            'updated_by' => $this->general_library->getId()
                        ]); 
            }

            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $rs['code'] = 1;
                $rs['message'] = 'Terjadi Kesalahan';
            } else {
                $this->db->trans_commit();
            }

            return $rs;
        }

        public function getListUrl($id_role){
            $this->db->select('a.url')
                    ->from('m_menu a')
                    ->join('m_menu_role b', 'b.id_m_menu = a.id')
                    ->where('b.id_m_role', $id_role)    
                    ->where('a.flag_active', 1)
                    ->where('b.flag_active', 1)    
                    ->order_by('a.nama_menu', 'asc')
                    ->group_by('a.id');
                    
            return $this->db->get()->result_array();
        }

        public function logoutWs($req){
            $resp = ['code' => 200, 'message' => '', 'data' => null];

            $this->db->trans_begin();

            $user = $this->db->select('*')
                            ->from('m_user')
                            ->where('username', $req['username'])
                            ->where('password', $req['password'])
                            ->get()->row_array();

            if($user){
                $this->db->where('id', $user['id'])
                    ->update('m_user', [
                        'device_id' => null,
                        'updated_by' => $user['id']
                    ]);

            } else {
                $this->db->trans_rollback();
                $resp['code'] = 500;
                $resp['message'] = 'Terjadi Kesalahan';
                return $resp;
            }
            
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $resp['code'] = 500;
                $resp['message'] = 'Terjadi Kesalahan';
            } else {
                $this->db->trans_commit();
            }

            return $resp;
        }

        public function loginWs($data){
            $resp = ['code' => 200, 'message' => '', 'data' => null];

            $user = $this->db->select('a.username, a.password, a.nama as nama_user, a.id as id_m_user, b.id as id_m_role, c.id as id_m_merchant,
            d.nama as nama_role, d.role_name as kode_nama_role, c.id as id_m_merchant, c.nama_merchant, c.alamat, c.logo, a.device_id, c.expire_date')
            ->from('m_user a')
            ->join('m_user_role b', 'a.id = b.id_m_user')
            ->join('m_merchant c', 'a.id_m_merchant = c.id', 'left')
            ->join('m_role d', 'b.id_m_role = d.id')
            ->where('a.username', $data['username'])
            // ->where('a.password', $password)
            ->where('a.flag_active', 1)
            ->where('b.flag_active', 1)
            ->group_by('a.id')
            // ->where('c.flag_active', 1)
            ->get()->row_array();

            if($user){
                $password = $this->general_library->encrypt($data['username'], $data['password']);
                if($user['password'] == $password){
                    $validate = $this->validateMerchant($user, $data['device_id'], 1);
                    if($validate['code'] == 0){
                        $resp = ['code' => 200, 'message' => '', 'data' => $user];
                        $this->db->where('id', $user['id_m_user'])
                                ->update('m_user', [
                                    'device_id' => $data['device_id'],
                                    'updated_by' => $user['id_m_user']
                                ]);
                    } else {
                        $resp = ['code' => 302, 'message' => $validate['message'], 'data' => null, 'status' => false];
                    }
                } else {
                    $resp = ['code' => 404, 'message' => 'Password salah'];
                }
            } else {
                $resp = ['code' => 404, 'message' => 'Username tidak ditemukan'];
            }

            return $resp;
            
        }

        public function checkUserCredentials($data){
            return $this->db->select('a.username, a.password, a.nama as nama_user, a.id as id_m_user, b.id as id_m_role, c.id as id_m_merchant,
                            d.nama as nama_role, d.role_name as kode_nama_role, c.id as id_m_merchant, c.nama_merchant, c.alamat, c.logo')
                            ->from('m_user a')
                            ->join('m_user_role b', 'a.id = b.id_m_user')
                            ->join('m_merchant c', 'a.id_m_merchant = c.id', 'left')
                            ->join('m_role d', 'b.id_m_role = d.id')
                            ->where('a.username', $data['username'])
                            ->where('a.password', $data['password'])
                            ->where('a.flag_active', 1)
                            ->where('b.flag_active', 1)
                            ->group_by('a.id')
                            // ->where('c.flag_active', 1)
                            ->get()->row_array();
        }

        public function checkUserCredentialsLibrary($data){
            // $data['password'] = $this->general_library->encrypt($data['username'], $data['password']);
            return $this->db->select('a.username, a.password, a.nama as nama_user, a.id as id_m_user, b.id as id_m_role, c.id as id_m_merchant, c.expire_date,
                            d.nama as nama_role, d.role_name as kode_nama_role, c.id as id_m_merchant, c.nama_merchant, c.alamat, c.logo, a.device_id')
                            ->from('m_user a')
                            ->join('m_user_role b', 'a.id = b.id_m_user')
                            ->join('m_merchant c', 'a.id_m_merchant = c.id', 'left')
                            ->join('m_role d', 'b.id_m_role = d.id')
                            ->where('a.username', $data['username'])
                            ->where('a.password', $data['password'])
                            ->where('a.flag_active', 1)
                            ->where('b.flag_active', 1)
                            ->group_by('a.id')
                            // ->where('c.flag_active', 1)
                            ->get()->row_array();
        }

        public function validateMerchant($user, $device_id, $flag_login = 0){
            $res = ['code' => 0, 'message' => ''];

            if($user['device_id'] && $user['device_id'] != $device_id){
                $res = ['code' => 1, 'message' => 'User ini telah login di device lain.'];
            } else if (date('Y-m-d') > $user['expire_date']){
                $res = ['code' => 1, 'message' => 'Akun Merchant sudah expire. Hubungi Admin untuk extend'];
            }

            if(!$user['device_id'] && $flag_login == 0){ //jika device id null dan bukan login, redirect logout
                $res = ['code' => 1, 'message' => 'Silahkan melakukan login kembali'];
            }

            return $res;
        }

        public function changePasswordWs($data){
            $res = ['code' => 0, 'message' => ''];
            $new_password = $this->general_library->encrypt($data['username'], $data['new_password']);
            $this->db->where('username', $data['username'])
                    ->update('m_user', ['password' => $new_password]);
            if($this->db->affected_rows() > 0){
                $res = ['code' => 0, 'message' => 'Password Berhasil diubah', 'data' => $new_password];
            } else {
                $res = ['code' => 1, 'message' => 'Terjadi Keslahan. '.json_encode($this->db->error())];
            }
            return $res;
        }

        public function getUserMerchant($req){
            $list = $this->db->select('*')
                            ->from('m_user a')
                            ->where('id_m_merchant', $req['id_m_merchant'])
                            ->order_by('nama', 'asc')
                            ->get()->result_array();
            $res = ['code' => 200, 'message' => '', 'data' => $list];
            return $res;
        }

        public function getUserInfo($id){
            return $this->db->select('*, a.id as id_m_user')
                            ->from('m_user a')
                            ->join('m_merchant b', 'a.id_m_merchant = b.id')
                            ->where('a.id', $id)
                            ->get()->row_array();
        }

        public function changeMerchantUser($id_m_user, $data){
            $this->db->where('id', $id_m_user)
                    ->update('m_user', [
                        'id_m_merchant' => $data['id_m_merchant']
                    ]);

            return $this->db->select('*')
                        ->from('m_merchant')
                        ->where('id', $data['id_m_merchant'])
                        ->where('flag_active', 1)
                        ->get()->row_array();
        }
	}
?>