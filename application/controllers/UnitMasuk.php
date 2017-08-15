<?php

/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 25/07/2017
 * Time: 15.44
 */
class UnitMasuk extends CI_Controller
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
                $resp = $this->UnitListModel->get_unitmasuk_list();
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
                $resp = $this->UnitListModel->get_unitmasuk_search($id);
                json_output($response['status'],$resp);
            }
        }
    }

    public function page()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $response = $this->AuthModel->auth();
            if($response['status'] == 200){

                $data = new stdClass();

                $data->start = $_GET['start'];
                $data->limit = $_GET['limit'];

                $resp = $this->UnitListModel->get_pagemasuk_list($data);
                json_output($response['status'],$resp);
            }
        }
    }

    public function create()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'POST'){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $response = $this->MyModel->auth();
            $respStatus = $response['status'];
            if($response['status'] == 200){
                $params = json_decode(file_get_contents('php://input'), TRUE);
                if ($params['title'] == "" || $params['author'] == "") {
                    $respStatus = 400;
                    $resp = array('status' => 400,'message' =>  'Title & Author can\'t empty');
                } else {
                    $resp = $this->MyModel->book_create_data($params);
                }
                json_output($respStatus,$resp);
            }
        }
    }

    public function insert()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'POST'){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $response = $this->AuthModel->auth();
            if($response['status'] == 200){

                $json = file_get_contents('php://input');
                $params = json_decode($json, TRUE);

                $data = new stdClass();

                $data->idpemeriksaanitem= $params['idpemeriksaanitem'];
                $data->idauctionitem= $params['idauctionitem'];
                $data->bataskomponen= $params['bataskomponen'];

                $data->fuel= $params['fuel'];
                $data->catbody= $params['catbody'];
                $data->catatan= $params['catatan'];
                $data->cases = $params['cases'];
                $data->poolkota = $params['poolkota'];
                $data->WEBID_LOGGED_IN = $params['WEBID_LOGGED_IN'];

                $data->tglpemeriksaan= $params['tglpemeriksaan'];
                $data->jampemeriksaan = $params['jampemeriksaan'];
                $data->menitpemeriksaan = $params['menitpemeriksaan'];

                $data->NO_POLISI = $params['nopolisi'];
                $data->namapengemudi= $params['namapengemudi'];
                $data->alamatpengemudi= $params['alamatpengemudi'];
                $data->kotapengemudi= $params['kotapengemudi'];
                $data->teleponpengemudi= $params['teleponpengemudi'];

                $data->iduser = $params['iduser'];

                $data->cektampilkanbaik = $params['cektampilkanbaik'];
                $data->cektampilkanrusak  = $params['cektampilkanrusak'];
                $data->cektampilkantidakada = $params['cektampilkantidakada'];
                $data->idkomponenpemeriksaan = $params['idkomponenpemeriksaan'];

//                print_r($data);
                $resp = $this->UnitListModel->post_unit($data);
                json_output($response['status'],$resp);
            }
        }
    }

}