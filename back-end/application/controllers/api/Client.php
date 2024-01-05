<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {

    public $data;

    public function __construct($config="rest") {
        header("Access-Control-Allow-Origin: null");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding,Authorization");
        parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->model('ClientModel');
        $this->load->model('AddressModel');
    }

    public function getClient()
    {

        try {
            $data = json_decode($this->input->raw_input_stream, true);
            $_GET = $data;
            if ($this->input->method() === 'get') {

                $headers = $this->input->request_headers();
                if (!$this->isValidAuthorization($headers)) {
                    return $this->sendJson(['response' => 'Token é necessário.'], 404);
                }

                $id = $this->input->get('id');
                $this->data = $this->getClientData($id);
                return $this->sendJson(['response' => $this->data], 200);
            }else{
                return $this->sendJson(['response' => 'Utilize o metodo HTTP correto.'], 500);
            }
        }catch (Exception $e){
            return $this->sendJson(['response' =>
                'Ocorreu um erro ao recuperar o cliente.'], 500);
        }
    }


     public function createClient()
    {
        try {
            $data = json_decode($this->input->raw_input_stream, true);
            $_POST = $data;

            if ($this->input->method() === 'post') {
                $headers = $this->input->request_headers();
                if (!$this->isValidAuthorization($headers)) {
                    return $this->sendJson(['response' => 'Token é necessário.'], 404);
                }
                if (!$this->isValidFormData()) {
                    return $this->sendJson(['response' => validation_errors()], 400);
                }
                $input = $this->input->post();
                $dataClient['name'] = $this->input->post('name') ?? null;
                $dataClient['email'] = $this->input->post('email') ?? null;
                $dataClient['phone'] = $this->input->post('phone') ?? null;
                $clientID = $this->ClientModel->insert($dataClient);
                if ($clientID) {
                    $this->createAddress($input, $clientID);
                    return $this->sendJson(['response' => 'Cliente registrado com sucesso.'], 200);
                }
                return $this->sendJson(['response' => 'Falha ao registrar o cliente.'], 500);
            }else{
                return $this->sendJson(['response' => 'Utilize o metodo HTTP correto.'], 500);
            }
        }catch (Exception $e){
            return $this->sendJson(['response' =>
                'Ocorreu um erro ao criar o cliente.'], 500);
        }
    }

    public function updateClient()
    {
        try {
            $data = json_decode($this->input->raw_input_stream, true);
            $_POST = $data;
            if ($this->input->method() === 'put') {
                $headers = $this->input->request_headers();
                $id = $this->input->post('id');
                if (!$this->isValidAuthorization($headers)) {
                    return $this->sendJson(['response' => 'Token é necessário.'], 404);
                }
                $existingClient = $this->getClientById($id);
                if (!$existingClient) {
                    return $this->sendJson(['response' => 'Cliente não encontrado.'], 404);
                }
                $input = $this->input->post();
                $this->updateClientAndAddress($id, $input);
                return $this->sendJson(['response' => 'Cliente atualizado com sucesso.'], 200);
            }else{
                return $this->sendJson(['response' => 'Utilize o metodo HTTP correto.'], 500);
            }
        }catch(Exception $e){
            return $this->sendJson(['response' =>
                'Ocorreu um erro ao atualizar o cliente.'], 500);
        }
    }

    public function deleteClient()
    {
        try {
            $data = json_decode($this->input->raw_input_stream, true);
            $_POST = $data;
            if ($this->input->method() === 'delete') {
                $headers = $this->input->request_headers();
                $id = $this->input->post('id');
                if (!$this->isValidAuthorization($headers)) {
                    return $this->sendJson(['response' => 'Token é necessário.'], 404);
                }
                if (!$this->isValidClientId($id)) {
                    return $this->sendJson(['response' => validation_errors()], 400);
                }
                $existingClient = $this->getClientById($id);
                if (!$existingClient) {
                    return $this->sendJson(['response' => 'Cliente não encontrado.'], 404);
                }
                $this->deleteClientAndAddress($id);
                return $this->sendJson(['response' => 'Cliente excluído com sucesso.'], 200);
            }else{
                return $this->sendJson(['response' => 'Utilize o metodo HTTP correto.'], 500);
            }
        }catch (Exception $e){
            return $this->sendJson(['response' =>
                'Ocorreu um erro ao excluir o cliente.'], 500);
        }
    }

    private function sendJson(array $data)
    {
        return $this->output->set_header('Content-Type: application/json; charset=utf-8')->set_output(json_encode($data));
    }

    private function isValidFormData()
    {
        $rules = [
            ['field' => 'name', 'label' => 'Nome', 'rules' => 'required', 'errors' => ['required' => 'O campo {field} é obrigatório.']],
            ['field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email', 'errors' => [
                'required' => 'O campo {field} é obrigatório.',
                'valid_email' => 'Por favor, forneça um endereço de e-mail válido.'
            ]],
            ['field' => 'phone', 'label' => 'Telefone', 'rules' => 'required', 'errors' => ['required' => 'O campo {field} é obrigatório.']],
        ];
        $this->form_validation->set_rules($rules);
        return $this->form_validation->run();
    }

    private function createAddress(array $input, int $idClient)
    {
        $addressData = [
            'cep' => $input['cep'] ?? null,
            'street' => $input['street'] ?? null,
            'complement' => $input['complement'] ?? null,
            'neighborhood' => $input['neighborhood'] ?? null,
            'city' => $input['city'] ?? null,
            'state' => $input['state'] ?? null,
            'id_client' => $idClient,
        ];
        $idAddress = $this->AddressModel->insert($addressData);
        $this->ClientModel->update($idClient,['id_address' => $idAddress]);
    }

    private function isValidClientId()
    {
        $this->form_validation->set_rules('id', 'Id', 'required');
        $this->form_validation->set_message('required', 'O campo {field} é obrigatório.');
        return $this->form_validation->run();
    }

    private function getClientData(int $id = null)
    {
        return (!empty($id)) ? $this->ClientModel->show($id) : $this->ClientModel->show();
    }

    private function isValidAuthorization(array $headers)
    {
        return isset($headers['Authorization']) && $this->authorization_token->validateToken($headers['Authorization'])['status'];
    }

    private function getClientById(int $id)
    {
        return $this->ClientModel->getClientById($id);
    }

    private function updateClientAndAddress(int $id, array $input)
    {
        $clientData = [
            'name' => $input['name'] ?? null,
            'email' => $input['email'] ?? null,
            'phone' => $input['phone'] ?? null,
        ];
        $this->ClientModel->update($id, $clientData);
        $addressData = [
            'cep' => $input['cep'] ?? null,
            'street' => $input['street'] ?? null,
            'complement' => $input['complement'] ?? null,
            'neighborhood' => $input['neighborhood'] ?? null,
            'city' => $input['city'] ?? null,
            'state' => $input['state'] ?? null,
        ];
        $this->AddressModel->update($id, $addressData);
    }

    private function deleteClientAndAddress(int $id)
    {
        $this->ClientModel->delete($id);
        $this->AddressModel->delete($id);
    }


}