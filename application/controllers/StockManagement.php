<?php

/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 26/07/2017
 * Time: 10.54
 */
class StockManagement extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('StockModel');
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
                    $resp = $this->StockModel->get_stock_list();
                    json_output($response['status'],$resp);
                }
            }
        }
    }

    public function search($id)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET' || $this->uri->segment(3) == ''){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->AuthModel->check_auth_client();
            if($check_auth_client == true){
                $response = $this->AuthModel->auth();
                if($response['status'] == 200){
                    $resp = $this->StockModel->get_stock_search($id);
                    json_output($response['status'],$resp);
                }
            }
        }
    }
}