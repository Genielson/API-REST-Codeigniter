<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClientModel extends CI_Model {
  
    public function __construct() {
       parent::__construct();
       $this->load->database();
    }

    public function insert(array $data): int {
        $this->db->insert('clients', $data);
        return $this->db->insert_id(); 
    }

    public function getClientById($id) {
        $query = $this->db->get_where('clients', array('id' => $id));
        return $query->row_array();
    }


    public function show(int $id = 0) {
        if (!empty($id)) {
            $result = $this->db->get_where("clients", ['id' => $id])->row_array();
        } else {
            $result = $this->db->get("clients")->result();
        }
        return $result;
    }


    public function update(array $data, int $id): int {
        $this->db->update('clients', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }
    
    public function delete(int $id): int {
        $this->db->delete('clients', ['id' => $id]);
        return $this->db->affected_rows();
    }
}