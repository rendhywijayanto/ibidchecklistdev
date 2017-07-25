<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
    }

    public function index()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
            $response = $this->MyModel->auth();
            if($response['status'] == 200){
                $resp = $this->CompanyModel->company_all_data();
                json_output($response['status'],$resp);
            }
		}
	}
    
}