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

    public function warna($id)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET' || $this->uri->segment(3) == ''){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->AuthModel->check_auth_client();
            if($check_auth_client == true){
                $response = $this->AuthModel->auth();
                if($response['status'] == 200){

                    $json = file_get_contents('php://input');
                    $params = json_decode($json, TRUE);

                    $resp = $this->MasterItemModel->get_warna_search($id);
                    json_output($response['status'],$resp);
                }
            }
        }
    }

    public function penitip($id)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET' || $this->uri->segment(3) == ''){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->AuthModel->check_auth_client();
            if($check_auth_client == true){
                $response = $this->AuthModel->auth();
                if($response['status'] == 200){

                    $resp = $this->MasterItemModel->get_all_nama($id);
                    json_output($response['status'],$resp);
                }
            }
        }
    }

    public function get_sign_masuk(){
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

                    $resp = $this->MasterItemModel->get_sign_img_msk($params);
                    json_output($response['status'],$resp);
                }
            }
        }
    }

    public function get_sign_keluar(){
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

                    $resp = $this->MasterItemModel->get_sign_img_klr($params);
                    json_output($response['status'],$resp);
                }
            }
        }
    }

    public function post_sign_masuk(){
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

                    $resp = $this->MasterItemModel->post_sign_img_msk($params);
                    json_output($response['status'],$resp);
                }
            }
        }
    }

    public function post_sign_keluar(){
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

                    $resp = $this->MasterItemModel->post_sign_img_klr($params);
                    json_output($response['status'],$resp);
                }
            }
        }
    }

    public function get_lampiran($id_pemeriksaanitem)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET' || $this->uri->segment(3) == ''){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->AuthModel->check_auth_client();
            if($check_auth_client == true){
                $response = $this->AuthModel->auth();
                if($response['status'] == 200){

                    $resp = $this->MasterItemModel->get_lamp_img($id_pemeriksaanitem);
                    json_output($response['status'],$resp);
                }
            }
        }
    }

    public function post_lampiran(){
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

                    $resp = $this->MasterItemModel->post_lamp_img($params);
                    json_output($response['status'],$resp);
                }
            }
        }
    }
}