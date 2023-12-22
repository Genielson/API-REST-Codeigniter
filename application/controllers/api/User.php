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

	public function registerUser() : void {
		$this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_numeric|min_length[4]|is_unique[users.username]', array('is_unique' => 'Esse nome já existe, por favor, escolha outro! '));
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
		
		if ($this->form_validation->run() === false) {
            $this->response(['Regras de validação violadas'], REST_Controller::HTTP_OK);
		} else {
			
			$username = $this->input->post('username');
			$email    = $this->input->post('email');
			$password = $this->input->post('password');
			
			if ($res = $this->UserModel->createUser($username, $email, $password)) {
                $tokenData['uid'] = $res; 
                $tokenData['username'] = $username;
                $tokenData = $this->authorization_token->generateToken($tokenData);
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


	public function loginUser() : void {
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == false) {
            $this->response(['Regras de validação violadas'], REST_Controller::HTTP_OK);
		} else {
			
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			if ($this->UserModel->resolveUserLogin($username, $password)) {
				
				$userId = $this->UserModel->getUserIdFromUsername($username);
				$user    = $this->UserModel->getUser($userId);
				
				$_SESSION['user_id']      = (int)$user->id;
				$_SESSION['username']     = (string)$user->username;
				$_SESSION['logged_in']    = (bool)true;
				$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
				$_SESSION['is_admin']     = (bool)$user->is_admin;

                $tokenData['uid'] = $user_id;
                $tokenData['username'] = $user->username; 
                $tokenData = $this->authorization_token->generateToken($tokenData);
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


	public function logoutUser() : void {
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {

			foreach ($_SESSION as $key => $value) {
				unset($_SESSION[$key]);
			}
            $this->response(['Logout com sucesso!'], REST_Controller::HTTP_OK);
			
		} else {
            $this->response(['Houve um problema. Por favor, tente novamente'], REST_Controller::HTTP_OK);
		}
		
	}


}
