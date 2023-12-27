<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct($config = "rest") {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET,
         POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, 
        Content-Length, Accept-Encoding,Authorization");
		parent::__construct();
		$this->load->model('UserModel');
	}


}
