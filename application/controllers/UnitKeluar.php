<?php

/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 25/07/2017
 * Time: 16.14
 */
class UnitKeluar extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UnitListModel');
    }

    public function index()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $response = $this->AuthModel->auth();
            if($response['status'] == 200){
                $resp = $this->UnitListModel->get_unitkeluar_list();
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
                $resp = $this->UnitListModel->get_unitkeluar_search($id);
                json_output($response['status'],$resp);
            }
        }
    }
}