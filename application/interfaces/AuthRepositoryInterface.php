<?php

namespace Restserver\interfaces;

interface AuthRepositoryInterface
{

    public function resolveAuthLogin(string $email, string $password);
    public function getAuthenticationIdFromEmail(string $email);
    public function getAuth(int $userId);

}