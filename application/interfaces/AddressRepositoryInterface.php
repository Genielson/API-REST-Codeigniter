<?php

namespace application\interfaces;

interface AddressRepositoryInterface
{

    public function createAddress(array $input, int $clientID);
    public function updateAddress(int $idAddress, array $dataAddress);
    public function deleteAddress(int $idAddress);


}