<?php

   require APPPATH . '/libraries/REST_Controller.php';
   use Restserver\Libraries\REST_Controller;
     
class Client extends REST_Controller {
    
	  /**
     * CONSTRUCTOR | LOAD MODEL
     *
     * @return Response
    */
    public function __construct() {
       parent::__construct();
       $this->load->library('Authorization_Token');	
       $this->load->model('ClientModel');
    }


    /**
     * SHOW | GET method.
     *
     * @return Response
    */
    public function indexGet(int $id = 0)
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

    /**
     * INSERT | POST method.
     *
     * @return Response
    */

     public function store()
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


    /**
     * UPDATE | PUT method.
     *
     * @return Response
    */
    public function update(int $id)
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

                $response>0?$this->response(['Cliente atualizado com sucesso.'], REST_Controller::HTTP_OK):$this->response(['Não atualizado'], REST_Controller::HTTP_OK);
                // ------------- End -------------
            }
            else {
                $this->response($clientToken);
            }
        }
        else {
            $this->response(['Falha de autenticação.'], REST_Controller::HTTP_OK);
        }
    }


    /**
     * DELETE method.
     *
     * @return Response
    */
    public function delete($id)
    { 
        $headers = $this->input->request_headers(); 
        if (isset($headers['Authorization'])) {
            $clientToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($clientToken['status'])
            {
                $response = $this->ClientModel->delete($id);
                $response>0?$this->response(['Cliente deletado com sucesso!'], REST_Controller::HTTP_OK):$this->response(['Not deleted'], REST_Controller::HTTP_OK);
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