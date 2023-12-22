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
       
   	
}