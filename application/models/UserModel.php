<?php
namespace application\models;

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

	/**
	 * hashPassword function.
	 * 
	 * @access private
	 * @param  string $password
	 * @return string
	 */
	private function hashPassword(string $password): string {
		return password_hash($password, PASSWORD_BCRYPT);
	}

	/**
	 * createUser function.
	 * 
	 * @access public
	 * @param  string $username
	 * @param  string $email
	 * @param  string $password
	 * @return int
	 */
	public function createUser(string $username, string $email, string $password): int {
		$data = array(
			'username'   => $username,
			'email'      => $email,
			'password'   => $this->hashPassword($password),
			'created_at' => date('Y-m-j H:i:s'),
		);
		$this->db->insert('users', $data);
		return $this->db->insert_id(); 
	}

	/**
	 * resolveUserLogin function.
	 * 
	 * @access public
	 * @param  string $username
	 * @param  string $password
	 * @return bool
	 */
	public function resolveUserLogin(string $email, string $password): bool {
		$this->db->select('password');
		$this->db->from('users');
		$this->db->where('email', $email);
		$hash = $this->db->get()->row('password');
		return $this->verifyPasswordHash($password, $hash);
	}

	/**
	 * getUserIdFromUserName function.
	 * 
	 * @access public
	 * @param  string $username
	 * @return int|null
	 */
	public function getUserIdFromEmail(string $email): ?int {
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('email', $email);
		return $this->db->get()->row('id');	
	}

	/**
	 * getUser function.
	 * 
	 * @access public
	 * @param  string $userId
	 * @return object|null
	 */
	public function getUser(string $userId): ?object {
		$this->db->from('users');
		$this->db->where('id', $userId);
		return $this->db->get()->row();
	}

	/**
	 * verifyPasswordHash function.
	 * 
	 * @access private
	 * @param  string $password
	 * @param  string $hash
	 * @return bool
	 */
	private function verifyPasswordHash(string $password, string $hash): bool {
		return password_verify($password, $hash);
	}
	
}