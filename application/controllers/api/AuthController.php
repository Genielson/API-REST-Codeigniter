<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller
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
        try {
            if ($this->input->method() === 'post') {
                $validationResult = $this->validateLoginInput();
                if (!$validationResult['status']) {
                    return $this->sendJson(['response' => $validationResult['message']], 400);
                }
                $email = $this->input->post('username');
                $password = $this->input->post('password');
                if ($this->attemptLogin($email, $password)) {
                    $this->handleSuccessfulLogin($email);
                } else {
                    return $this->sendJson(['response' => 'Login ou senha incorretos.'], 404);
                }
            } else {
                return $this->sendJson(['response' => 'Método inválido. Use POST.'], 405);
            }
        }catch(Exception $e){
            return $this->sendJson(['response' =>
                'Ocorreu um erro ao realizar login'], 500);
        }
    }

    public function register()
    {
        try {
            $inputData = $this->input->post();
            if (!$this->validateRegistrationInput($inputData)) {
                return $this->sendJson(['response' => validation_errors()], 400);
            }
            $userId = $this->createUserAndReturnId($inputData);
            if ($userId) {
                $tokenData = $this->generateUserToken($userId, $inputData['username']);
                $response['access_token'] = $tokenData;
                $response['status'] = true;
                $response['uid'] = $userId;
                $response['message'] = 'Obrigado por registrar sua nova conta!';
                return $this->sendJson(['response' => $response], 200);
            } else {
                return $this->sendJson(['response' => 'Houve um erro ao criar a conta. Por favor, tente novamente'], 500);
            }
        }catch (Exception $e){
            return $this->sendJson(['response' =>
                'Ocorreu um erro ao registrar um usuario.'], 500);
        }
    }

    public function logout(){
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                foreach ($_SESSION as $key => $value) {
                    unset($_SESSION[$key]);
                }
                return $this->sendJson(['response' => 'Logout com sucesso!'], 200);
            } else {
               return  $this->sendJson(['response' => 'Houve um problema. Por favor, tente novamente'], 500);
            }
    }

    private function sendJson(array $data)
    {
      return  $this->output->set_header('Content-Type: application/json; charset=utf-8')->set_output(json_encode($data));
    }


    private function validateLoginInput()
    {
        $this->form_validation->set_rules('password', 'Senha', 'required');
        $this->form_validation->set_rules('username', 'Usuário', 'required');

        if ($this->form_validation->run() == false) {
            return ['status' => false, 'message' => 'Por favor, envie todos os parâmetros necessários'];
        }

        return ['status' => true];
    }

    private function attemptLogin(string $email, string $password)
    {
        return $this->UserModel->resolveUserLogin($email, $password);
    }

    private function handleSuccessfulLogin(string $email)
    {
        $userId = $this->UserModel->getUserIdFromEmail($email);
        $user = $this->UserModel->getUser($userId);
        $this->setUserSession($user);
        $tokenData['uid'] = $userId;
        $tokenData['username'] = $user->username;
        $tokenData = $this->authorization_token->generateToken($tokenData);
        $response['access_token'] = $tokenData;
        $response['status'] = true;
        $response['message'] = 'Login realizado com sucesso!';
        $response['note'] = 'Você está logado';
        return $this->sendJson(['response' => $response], 200);
    }

    private function setUserSession($user)
    {
        $_SESSION['user_id'] = (int) $user->id;
        $_SESSION['username'] = (string) $user->username;
        $_SESSION['logged_in'] = (bool) true;
        $_SESSION['is_confirmed'] = (bool) $user->is_confirmed;
    }


    private function validateRegistrationInput(array $inputData)
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required', ['required' => 'O campo {field} é obrigatório.']);
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', [
            'required' => 'O campo {field} é obrigatório.',
            'valid_email' => 'Por favor, forneça um endereço de e-mail válido.'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]', [
            'required' => 'O campo {field} é obrigatório.',
            'min_length' => 'A senha deve ter pelo menos {param} caracteres.'
        ]);
        return $this->form_validation->run();
    }

    private function createUserAndReturnId(array $inputData)
    {
        $username = $inputData['username'];
        $email = $inputData['email'];
        $password = $inputData['password'];
        return $this->UserModel->createUser($username, $email, $password);
    }

    private function generateUserToken(int $userId, string $username)
    {
        $tokenData['uid'] = $userId;
        $tokenData['username'] = $username;
        return $this->authorization_token->generateToken($tokenData);
    }
}