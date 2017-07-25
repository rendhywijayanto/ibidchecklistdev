<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UnitLelang extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('UnitLelangModel');
    }

    public function index()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
            $response = $this->MyModel->auth();
            if($response['status'] == 200){
                $resp = $this->UnitLelangModel->auctions_all_data();
                json_output($response['status'],$resp);
            }
		}
	}
    
}