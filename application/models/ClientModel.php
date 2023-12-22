<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClientModel extends CI_Model {
  
    public function __construct() {
       parent::__construct();
       $this->load->database();
    }

    /**
     * INSERT method.
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data): int {
        $this->db->insert('clients', $data);
        return $this->db->insert_id(); 
    } 

    /**
     * SHOW method.
     *
     * @param int $id
     * @return array|object|null
     */
    public function show(int $id = 0) {
        if (!empty($id)) {
            $result = $this->db->get_where("clients", ['id' => $id])->row_array();
        } else {
            $result = $this->db->get("clients")->result();
        }
        return $result;
    }

    /**
     * UPDATE method.
     *
     * @param array $data
     * @param int $id
     * @return int
     */
    public function update(array $data, int $id): int {
        $this->db->update('clients', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

    /**
     * DELETE method.
     *
     * @param int $id
     * @return int
     */
    public function delete(int $id): int {
        $this->db->delete('clients', ['id' => $id]);
        return $this->db->affected_rows();
    }
}