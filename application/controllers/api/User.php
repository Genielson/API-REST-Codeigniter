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

	public function register()  {
        $headers = $this->input->request_headers();
        if (isset($headers['Authorization'])) {
            $this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_numeric|min_length[4]|is_unique[users.username]', array('is_unique' => 'Esse nome já existe, por favor, escolha outro! '));
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');

            if ($this->form_validation->run() === false) {
                $this->response(['Regras de validação violadas'], REST_Controller::HTTP_OK);
            } else {

                $username = $this->input->post('username');
                $email    = $this->input->post('email');
                $password = $this->input->post('password');



            }
        }else{
            return $this->sendJson(array("status" => true, "response" => "Token é necessário "));
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
