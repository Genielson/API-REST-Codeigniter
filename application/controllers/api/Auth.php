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
            $this->form_validation->set_rules('password', 'Senha', 'required');
            $this->form_validation->set_rules('username', 'Usuario', 'required');
            if ($this->form_validation->run() == false) {
                return $this->sendJson(array("message" => " Por favor, envie todos os parâmetros necessários"));
            } else {
                $username    = $this->input->post('username');
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
                    $this->sendJson(['response' => $final], 200);
                } else {
                    $this->sendJson(['message' => 'Login ou senha incorretos. '], 404);
                }
            }
        } else {
            return $this->sendJson(array("message" => "POST Method", "status" => false));
        }
    }

    public function register(){
            $this->form_validation->set_rules('username', 'Username', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            if ($this->form_validation->run() === false) {
                $this->sendJson(['Regras de validação violadas'], 500);
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
                    return $this->sendJson(array("response" => $final), 200);
                } else {
                    return $this->sendJson(array("response" => "Houve um erro ao criar a conta. Por favor, tente novamente"), 500);
                }

            }
    }

    public function logout(){
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            foreach ($_SESSION as $key => $value) {
                unset($_SESSION[$key]);
            }
            $this->sendJson(['response' => 'Logout com sucesso!'], 200);
        } else {
            $this->sendJson(['response' => 'Houve um problema. Por favor, tente novamente'], 500);
        }
    }

    private function sendJson($data)
    {
        $this->output->set_header('Content-Type: application/json; charset=utf-8')->set_output(json_encode($data));
    }
}