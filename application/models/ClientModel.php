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
        $result = NULL;
        if(!empty($id)){
            $result = $this->db->get_where("clients", ['id' => $id])->row_array();
        }else{
            $result = $this->db->get("clients")->result();
        }
        return $result;
    }

     public function update($data, $id)
    {
        $data = $this->db->update('clients', $data, array('id'=>$id));
        return $this->db->affected_rows();
    }

    
}
