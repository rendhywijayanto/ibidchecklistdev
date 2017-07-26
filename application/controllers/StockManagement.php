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
        $this->load->model('StockManagementModel');
    }

    public function index()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $response = $this->AuthModel->auth();
            if($response['status'] == 200){
                $resp = $this->StockManagementModel->get_all_item();
                json_output($response['status'],$resp);
            }
        }
    }

    public function search($id)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET' || $this->uri->segment(3) == ''){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $response = $this->AuthModel->auth();
            if($response['status'] == 200){
                $resp = $this->StockManagementModel->get_search_item($id);
                json_output($response['status'],$resp);
            }
        }
    }
}