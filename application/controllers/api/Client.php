<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {

    public $data;

    public function __construct($config="rest") {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding,Authorization");
        parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->model('ClientModel');
    }

    public function getClient(int $id = 0)
    {
        $headers = $this->input->request_headers();
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);

            if ($decodedToken['status'])
            {

                if(!empty($id)){
                    $this->data = $this->ClientModel->show($id);
                } else {
                    $this->data = $this->ClientModel->show();
                }
                $this->sendJson($this->data, 200);
            }
            else {
                $this->sendJson($decodedToken);
            }
        } else {
            $this->sendJson(['Token é necessário. '], 404);
        }
    }


     public function createClient()
    {

        $headers = $this->input->request_headers();
        if (isset($headers['Authorization'])) {
            $clientToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($clientToken['status'])
            {
                $input = $this->input->post();
                $this->ClientModel->insert($input);
                $this->sendJson(['Cliente registrado com sucesso.'], 200);
            }
            else {
                $this->sendJson($clientToken);
            }
        }
        else {
            $this->sendJson(['Token é necessário. '], 404);
        }
    }



    public function updateClient(int $id)
    {
        $headers = $this->input->request_headers();
        if (isset($headers['Authorization'])) {
            $clientToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($clientToken['status'])
            {
                $headers = $this->input->request_headers();
                $data['name'] = $headers['name'];
                $data['price'] = $headers['price'];
                $response = $this->ClientModel->update($data, $id);


                $response>0? $this->sendJson(['Cliente atualizado com sucesso.'],
                    REST_Controller::HTTP_OK):$this->sendJson(['Não atualizado'],
                    REST_Controller::HTTP_OK);

                $response>0?$this->sendJson(['Cliente atualizado com sucesso.'], REST_Controller::HTTP_OK):$this->response(['Não atualizado'], REST_Controller::HTTP_OK);
            }
            else {
                $this->sendJson($clientToken);
            }
        }
        else {
            $this->sendJson(['Token é necessário. '], 404);
        }
    }


    public function deleteClient($id)
    {
        $headers = $this->input->request_headers();
        if (isset($headers['Authorization'])) {
            $clientToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($clientToken['status'])
            {
                $response = $this->ClientModel->delete($id);
                $response>0?$this->sendJson(['Cliente deletado com sucesso!'],
                    REST_Controller::HTTP_OK):$this->sendJson(['Not deleted'],
                    REST_Controller::HTTP_OK);
            }
            else {
                $this->sendJson($clientToken);
            }
        }
        else {
            $this->sendJson(['Falha de autenticação.'], REST_Controller::HTTP_OK);
        }
    }

    private function sendJson($data)
    {
        $this->output->set_header('Content-Type: application/json; charset=utf-8')->set_output(json_encode($data));
    }


}