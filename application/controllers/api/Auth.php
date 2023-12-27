<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct($config = "rest")
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding,Authorization");
        parent::__construct();
        $this->load->model('UserModel');
    }

    public function login()
    {
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required');
            if ($this->form_validation->run() == false) {
                return $this->sendJson(array("message" => " Por favor, envie todos os parâmetros necessários"));
            } else {
                $username = $this->input->post('username');
                $email    = $this->input->post('email');
                $password = $this->input->post('password');

                if ($res = $this->UserModel->createUser($username, $email, $password)) {
                    $tokenData['uid'] = $res;
                    $tokenData['username'] = $username;
                    $tokenData = $this->authorization_token->validateToken($headers['Authorization']);                $final = array();
                    $final['access_token'] = $tokenData;
                    $final['status'] = true;
                    $final['uid'] = $res;
                    $final['message'] = 'Obrigado por registrar sua nova conta!';
                    return $this->sendJson(array("response" => $final), 200);
                } else {
                    return $this->sendJson(array("response" => "Houve um erro ao criar a conta. Por favor, tente novamente"), 500);
                }
            }
        } else {
            return $this->sendJson(array("message" => "POST Method", "status" => false));
        }
    }

    private function sendJson($data)
    {
        $this->output->set_header('Content-Type: application/json; charset=utf-8')->set_output(json_encode($data));
    }
}