<?php
/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 20/08/2017
 * Time: 22.03
 */

class MasterItem extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('MasterItemModel');
    }

    public function search()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'POST'){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->AuthModel->check_auth_client();
            if($check_auth_client == true){
                $response = $this->AuthModel->auth();
                if($response['status'] == 200){

                    $json = file_get_contents('php://input');
                    $params = json_decode($json, TRUE);

                    $data = new stdClass();

                    $data->id_item = $params['id_attrdetail'];

                    $resp = $this->MasterItemModel->get_all_item($data);
                    json_output($response['status'],$resp);
                }
            }
        }
    }

    public function warna()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->AuthModel->check_auth_client();
            if($check_auth_client == true){
                $response = $this->AuthModel->auth();
                if($response['status'] == 200){

                    $json = file_get_contents('php://input');
                    $params = json_decode($json, TRUE);

                    $resp = $this->MasterItemModel->get_All_warna();
                    json_output($response['status'],$resp);
                }
            }
        }
    }

    public function penitip()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'POST'){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->AuthModel->check_auth_client();
            if($check_auth_client == true){
                $response = $this->AuthModel->auth();
                if($response['status'] == 200){

                    $json = file_get_contents('php://input');
                    $params = json_decode($json, TRUE);

                    $data = new stdClass();
                    $data->term = $params['nama_penitip'];

                    $resp = $this->MasterItemModel->get_all_nama($data);
                    json_output($response['status'],$resp);
                }
            }
        }
    }

}