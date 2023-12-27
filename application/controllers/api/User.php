<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct($config = "rest") {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET,
         POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, 
        Content-Length, Accept-Encoding,Authorization");
		parent::__construct();
		$this->load->model('UserModel');
	}

	public function login_post() : void {
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

                $tokenData['uid'] = $userId;
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


	public function logout_post() : void {
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
