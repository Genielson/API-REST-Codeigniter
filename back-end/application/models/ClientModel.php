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

    public function getClientById(int $id) {
        $query = $this->db->get_where('clients', array('id' => $id));

        return $query->row_array();
    }


    public function show(int $id = 0) {
        if (!empty($id)) {
            $query = $this->db->query("SELECT * FROM clients c left join addresses a on c.id = a.id_client 
         WHERE c.id = ?", array($id));
            $result = $query->row_array();
        } else {
            $result = $this->db->query("SELECT * FROM clients c left join addresses a on c.id = a.id_client 
        ")->result();
        }

        return $result;
    }


    public function update(int $id, array $data): int {
        $this->db->update('clients', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function delete(int $id): int {
        $this->db->delete('clients', ['id' => $id]);
        return $this->db->affected_rows();
    }
}