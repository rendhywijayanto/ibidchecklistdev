<?php

/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 25/07/2017
 * Time: 10.01
 */
class Persiapan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PersiapanModel');
    }

    public function index()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $response = $this->AuthModel->auth();
            if($response['status'] == 200){
                $resp = $this->PersiapanModel->get_item_list();
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
                $resp = $this->PersiapanModel->get_list_search($id);
                json_output($response['status'],$resp);
            }
        }
    }

}