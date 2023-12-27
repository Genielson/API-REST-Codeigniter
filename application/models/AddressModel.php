<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AddressModel extends CI_Model
{

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert(array $data): int {
        $this->db->insert('addresses', $data);
        return $this->db->insert_id();
    }

    public function update(int $id, array $data): int {
        $this->db->update('addresses', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function delete(int $id): int {
        $this->db->delete('addresses', ['id' => $id]);
        return $this->db->affected_rows();
    }



}