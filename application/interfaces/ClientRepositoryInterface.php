<?php

namespace application\interfaces;

interface ClientRepositoryInterface
{

    public function getClientData(int $id = null);
    public function createClient(array $dataClient);
    public function getClientById(int $id);

    public function updateClient(int $idClient, array $dataClient);
    public function deleteClient(int $id);



}