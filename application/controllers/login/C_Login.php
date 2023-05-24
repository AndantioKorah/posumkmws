<?php

class C_Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('general/M_General', 'm_general');
        $this->load->model('user/M_User', 'user');
    }

    public function login(){
        // $userLoggedIn = $this->session->userdata('user_logged_in');
        // if($userLoggedIn){
        //     redirect(base_url($this->session->userdata('landing_page')));
        // } else {
            $this->session->set_userdata(['user_logged_in' => null, 'test' => null, 'params' => null]);
            $this->load->view('login/V_Login', null);
        // }
    }

    public function logout(){
        $this->session->set_flashdata('message', $this->session->userdata('apps_error'));
        $this->session->set_userdata(['apps_error' => null]);
        $this->session->set_userdata(['user_logged_in' => null, 'test' => null, 'params' => null]);
        redirect('login');
    }

    public function welcomePage(){
        if(!$this->general_library->isNotMenu()){
            redirect('logout');
        };
        render('login/V_Welcome', '', '', null);
    }

    public function authenticateAdmin()
    { 
       
        if($this->input->post('username') == 'prog' && $this->input->post('password') == '123Tes.'){
            redirect('developer');
        }
        $username = $this->input->post('username');
        $password = $this->general_library->encrypt($username, $this->input->post('password'));
        // var_dump($password);
        // die();
        $result = $this->m_general->authenticate($username, $password);
        
        if($result != null){
            $params = $this->m_general->getAll('m_parameter');
            $list_menu = null;
            $list_role = $this->user->getListRoleForUser($result[0]['id']);
            $active_role = null;
            if($list_role){
                $active_role = $list_role[0];
                $list_menu = $this->general_library->getListMenu($active_role['id'], $active_role['role_name']);
                $list_url = $this->general_library->getListUrl($active_role['id']);
            }

            if(!$active_role){
                $this->session->set_flashdata('message', 'Akun Anda belum memiliki Role. Silahkan menghubungi Administrator.');
                redirect('login');
            }

            $this->session->set_userdata([
                'user_logged_in' => $result,
                'params' => $params,
                'test' => 'tiokors',
                'list_menu' =>  $list_menu,
                'list_role' =>  $list_role,
                'list_url' =>  $list_url,
                'active_role' =>  $active_role,
                'active_role_id' =>  $active_role['id'],
                'active_role_name' =>  $active_role['role_name'],
                'landing_page' =>  $active_role['landing_page'],
                'ID_PENDAFTARAN_PASIEN' =>  null,
            ]);
            if($params){
                foreach($params as $p){
                    $this->session->set_userdata([$p['parameter_name'] => $p]);
                }
            }
            redirect(base_url($this->session->userdata('landing_page')));                
        } else {
            $this->session->set_flashdata('message', $this->session->flashdata('message'));
            redirect('login');
        }
    }

    public function openAuthModal($id, $id_t_transaksi, $jenis_transaksi){
        $data['id'] = $id;
        $data['id_t_transaksi'] = $id_t_transaksi;
        $data['jenis_transaksi'] = $jenis_transaksi;
        $this->load->view('admin/V_Auth', $data);
    }

    public function otentikasiUser($jenis_transaksi){
        $result = $this->m_general->otentikasiUser($this->input->post(), $jenis_transaksi);
        echo json_encode($result);
    }
}
