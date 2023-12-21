<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class UserModel extends CI_Model {

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}

	public function createUser(String $username, String $email, String $password) {
		$data = array(
			'username'   => $username,
			'email'      => $email,
			'password'   => $this->hash_password($password),
			'created_at' => date('Y-m-j H:i:s'),
		);
		$this->db->insert('users', $data);
		return $this->db->insert_id(); 
		
	}

	public function resolveUserLogin(String $username, String $password) {	
		$this->db->select('password');
		$this->db->from('users');
		$this->db->where('username', $username);
		$hash = $this->db->get()->row('password');
		return $this->verify_password_hash($password, $hash);
	}

	public function getUserIdFromUserName(String $username) {
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('username', $username);
		return $this->db->get()->row('id');	
	}

	public function getUser(String $userId) {
		$this->db->from('users');
		$this->db->where('id', $userId);
		return $this->db->get()->row();
	}


	
}