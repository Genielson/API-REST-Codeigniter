<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {


    public function __construct() {
       parent::__construct();
       $this->load->library('Authorization_Token');	
       $this->load->model('ClientModel');
    }



    public function indexClient(int $id = 0)
    {
        $headers = $this->input->request_headers(); 
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status'])
            {
                if(!empty($id)){
                    $data = $this->ClientModel->show($id);
                } else {
                    $data = $this->ClientModel->show();
                }
                $this->response($data, REST_Controller::HTTP_OK);
            } 
            else {
                $this->response($decodedToken);
            }
        } else {
            $this->response(['Falha de autenticação.'], REST_Controller::HTTP_OK);
        }
    }


     public function storeClient()
    {
        $headers = $this->input->request_headers(); 
        if (isset($headers['Authorization'])) {
            $clientToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($clientToken['status'])
            {  
                $input = $this->input->post();
                $data = $this->ClientModel->insert($input);
                $this->response(['Cliente registrado com sucesso.'], REST_Controller::HTTP_OK);
            }
            else {
                $this->response($clientToken);
            }
        }
        else {
            $this->response(['Falha de autenticação.'], REST_Controller::HTTP_OK);
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

                $response>0? $this->response(['Cliente atualizado com sucesso.'],
                    REST_Controller::HTTP_OK):$this->response(['Não atualizado'],
                    REST_Controller::HTTP_OK);
            }
            else {
                $this->response($clientToken);
            }
        }
        else {
            $this->response(['Falha de autenticação.'], REST_Controller::HTTP_OK);
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
                $response>0?$this->response(['Cliente deletado com sucesso!'],
                    REST_Controller::HTTP_OK):$this->response(['Not deleted'],
                    REST_Controller::HTTP_OK);
            }
            else {
                $this->response($clientToken);
            }
        }
        else {
            $this->response(['Falha de autenticação.'], REST_Controller::HTTP_OK);
        }
    }
       
   	
}