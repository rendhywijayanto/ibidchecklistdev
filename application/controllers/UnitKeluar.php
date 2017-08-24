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

                $resp = $this->UnitListModel->get_pagekeluar_list($data);
                json_output($response['status'],$resp);
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
                $data->WEBID_LOGGED_IN = $params['WEBID_LOGGED_IN'];

                $data->tglpemeriksaan= $params['tglpemeriksaan'];
                $data->jampemeriksaan = $params['jampemeriksaan'];
                $data->menitpemeriksaan = $params['menitpemeriksaan'];

                $data->NO_POLISI = $params['nopolisi'];
                $data->MERK = $params['MERK'];
                $data->SERI = $params['SERI'];
                $data->SILINDER = $params['SILINDER'];
                $data->GRADE = $params['GRADE'];
                $data->SUB_GRADE = $params['SUB_GRADE'];
                $data->TRANSMISI = $params['TRANSMISI'];
                $data->KM = $params['KM'];
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
                $resp = $this->UnitListModel->post_unit_keluar($data);
                json_output($response['status'],$resp);
            }
        }
    }
}