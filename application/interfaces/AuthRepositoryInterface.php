<?php

namespace application\interfaces;

interface AuthRepositoryInterface
{

    public function resolveAuthLogin(string $email, string $password);
    public function getAuthenticationIdFromEmail(string $email);


}