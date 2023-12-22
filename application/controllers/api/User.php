<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class User extends REST_Controller {

	
	public function __construct() {
		parent::__construct();
      $this->load->library('Authorization_Token');
		$this->load->model('UserModel');
	}


	public function registerUser() {
		$this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_numeric|min_length[4]|is_unique[users.username]', array('is_unique' => 'Esse nome já existe, por favor, escolha outro! '));
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
		
		if ($this->form_validation->run() === false) {
            $this->response(['Regras de validação violadas'], REST_Controller::HTTP_OK);
		} else {
			
			$username = $this->input->post('username');
			$email    = $this->input->post('email');
			$password = $this->input->post('password');
			
			if ($res = $this->user_model->create_user($username, $email, $password)) {
                $token_data['uid'] = $res; 
                $token_data['username'] = $username;
                $tokenData = $this->authorization_token->generateToken($token_data);
                $final = array();
                $final['access_token'] = $tokenData;
                $final['status'] = true;
                $final['uid'] = $res;
                $final['message'] = 'Obrigado por registrar sua nova conta!';
                $this->response($final, REST_Controller::HTTP_OK); 

			} else {
                $this->response(['Ocorreu um problema ao criar sua nova conta. Por favor, tente novamente.'], REST_Controller::HTTP_OK);
			}
			
		}
		
	}


	public function loginUser() {
	
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == false) {
			
            $this->response(['Regras de validação violadas'], REST_Controller::HTTP_OK);

		} else {
			
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			if ($this->user_model->resolve_user_login($username, $password)) {
				
				$user_id = $this->user_model->get_user_id_from_username($username);
				$user    = $this->user_model->get_user($user_id);
				
				$_SESSION['user_id']      = (int)$user->id;
				$_SESSION['username']     = (string)$user->username;
				$_SESSION['logged_in']    = (bool)true;
				$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
				$_SESSION['is_admin']     = (bool)$user->is_admin;

                $token_data['uid'] = $user_id;
                $token_data['username'] = $user->username; 
                $tokenData = $this->authorization_token->generateToken($token_data);
                $final = array();
                $final['access_token'] = $tokenData;
                $final['status'] = true;
                $final['message'] = 'Login realizado com sucesso!';
                $final['note'] = 'Você está logado';
                $this->response($final, REST_Controller::HTTP_OK); 	
			} else {
                $this->response(['Senha ou login incorretos. '], REST_Controller::HTTP_OK);			
			}
			
		}
		
	}

	
	
}
