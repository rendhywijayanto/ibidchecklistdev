<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

	public function login()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			$check_auth_client = $this->AuthModel->check_auth_client();
			if($check_auth_client == true){
				$params = json_decode(file_get_contents('php://input'), TRUE);
		        $email = $params['email'];
		        $password = $params['password'];

		        $response = $this->AuthModel->login($email,$password);
				echo json_encode($response);
			}
		}
	}

//	public function logout()
//	{
//		$method = $_SERVER['REQUEST_METHOD'];
//		if($method != 'POST'){
//			json_output(400,array('status' => 400,'message' => 'Bad request.'));
//		} else {
//			$check_auth_client = $this->AuthModel->check_auth_client();
//			if($check_auth_client == true){
//		        $response = $this->AuthModel->logout();
//				json_output($response['status'],$response);
//			}
//		}
//	}
	
}
