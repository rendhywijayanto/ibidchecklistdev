<?php

/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 25/07/2017
 * Time: 15.44
 */
class UnitMasukList extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UnitMasukListModel');
    }

    public function index()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $response = $this->AuthModel->auth();
            if($response['status'] == 200){
                $resp = $this->UnitMasukListModel->get_all_item();
                json_output($response['status'],$resp);
            }
        }
    }
}