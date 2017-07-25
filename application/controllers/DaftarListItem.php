<?php

/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 25/07/2017
 * Time: 10.01
 */
class DaftarListItem extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DaftarListItemModel');
    }

    public function index()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $response = $this->AuthModel->auth();
            if($response['status'] == 200){
                $resp = $this->DaftarListItemModel->get_all_item();
                json_output($response['status'],$resp);
            }
        }
    }

}