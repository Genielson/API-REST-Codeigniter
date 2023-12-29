<?php

namespace application\repositories;
use application\interfaces\ClientRepositoryInterface;
use application\models\ClientModel;
class ClientRepository implements ClientRepositoryInterface
{

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->load->model('ClientModel');
    }

    #[\Override] public function getClientData(int $id = null)
    {
        return (!empty($id)) ? $this->ClientModel->show($id) : $this->ClientModel->show();
    }

    #[\Override] public function createClient(array $dataClient)
    {
       return $this->ClientModel->insert($dataClient);
    }

    #[\Override] public function getClientById(int $id)
    {
        return $this->ClientModel->getClientById($id);
    }


    #[\Override] public function updateClient(int $idClient, array $dataClient)
    {
        $clientData = [
            'name' => $dataClient['name'] ?? null,
            'email' => $dataClient['email'] ?? null,
            'phone' => $dataClient['phone'] ?? null,
        ];
        $this->ClientModel->update($idClient, $clientData);
    }

    #[\Override] public function deleteClient(int $id)
    {
        $this->ClientModel->delete($id);
    }
}