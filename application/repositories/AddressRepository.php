<?php

namespace application\repositories;

use application\interfaces\AddressRepositoryInterface;

class AddressRepository implements AddressRepositoryInterface
{


    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->load->model('AddressModel');
    }

    #[\Override] public function createAddress(array $input, int $clientID)
    {
        $addressData = [
            'cep' => $input['cep'] ?? null,
            'street' => $input['street'] ?? null,
            'complement' => $input['complement'] ?? null,
            'neighborhood' => $input['neighborhood'] ?? null,
            'city' => $input['city'] ?? null,
            'state' => $input['state'] ?? null,
            'id_client' => $clientID,
        ];
        $this->AddressModel->insert($addressData);
    }


    #[\Override] public function updateAddress(int $idAddress, array $dataAddress)
    {
        $this->AddressModel->update($idAddress, $dataAddress);
    }

    #[\Override] public function deleteAddress(int $idAddress)
    {
        $this->AddressModel->delete($idAddress);
    }
}