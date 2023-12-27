<?php

namespace Restserver\controllers\api;


class Auth extends CI_Controller
{

    public function __construct($config = "rest")
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding,Authorization");
        parent::__construct();
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
                $email = $this->input->post('email');
                $password = $this->input->post('password');

                if ($email == "test@mail.com" and $password == "test") {
                    $token_data['userEmail'] = $email;
                    $token_data['userRole'] = "Admin";
                    $tokenData = $this->authorization_token->generateToken($token_data);
                    return $this->sendJson(array("token" => $tokenData, "status" => true, "response" => "Login Success!"));
                } else {
                    return $this->sendJson(array("token" => null, "status" => false, "response" => "Login Failed!"));
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