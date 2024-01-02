<?php

namespace application\repositories;
use application\interfaces\AuthRepositoryInterface;
use application\models\UserModel;



class AuthRepository implements AuthRepositoryInterface
{
    public function __construct() {
        $this->load->model('UserModel');
    }

    #[\Override] public function resolveAuthLogin(string $email, string $password): bool
    {
        return $this->UserModel->resolveUserLogin($email, $password);
    }

    #[\Override] public function getAuthenticationIdFromEmail(string $email): array
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
        return $response;
    }

    private function setUserSession(UserModel $user)
    {
        $_SESSION['user_id'] = (int) $user->id;
        $_SESSION['username'] = (string) $user->username;
        $_SESSION['logged_in'] = (bool) true;
        $_SESSION['is_confirmed'] = (bool) $user->is_confirmed;
    }

     public function createUserAndReturnId(array $inputData) : int
     {
            $username = $inputData['username'];
            $email = $inputData['email'];
            $password = $inputData['password'];
            return $this->UserModel->createUser($username, $email, $password);
     }

}