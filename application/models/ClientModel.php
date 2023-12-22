<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClientModel extends CI_Model {
  
    
    public function __construct() {
       parent::__construct();
       $this->load->database();
    }


    public function insert($data)
    {
        $this->db->insert('clients',$data);
        return $this->db->insert_id(); 
    } 

    public function show($id = 0)
    {
        if(!empty($id)){
            $query = $this->db->get_where("products", ['id' => $id])->row_array();
        }else{
            $query = $this->db->get("products")->result();
        }
        return $query;
    }

    
}
