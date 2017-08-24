<?php
/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 28/07/2017
 * Time: 09.14
 */

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('HomeModel');
    }

    public function index()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->AuthModel->check_auth_client();
            if($check_auth_client == true){
                $response = $this->AuthModel->auth();
                if($response['status'] == 200){
                    $resp = $this->HomeModel->get_data();
                    json_output($response['status'],$resp);
                }
            }

        }
    }
}