<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class User extends REST_Controller {

	
	public function __construct() {
		parent::__construct();
        $this->load->library('Authorization_Token');
		$this->load->model('UserModel');
	}

	
	
}
