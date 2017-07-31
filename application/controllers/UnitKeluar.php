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

    public function insert()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'POST'){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $response = $this->AuthModel->auth();
            if($response['status'] == 200){
                $params = json_decode(file_get_contents('php://input'), TRUE);
                $data = new stdClass();

                $data->idpemeriksaanitem= $params['idpemeriksaanitem'];
                $data->idauctionitem= $params['idauctionitem'];
                $data->bataskomponen= $params['bataskomponen'];

                $data->fuel= $params['fuel'];
                $data->catbody= $params['catbody'];
                $data->catatan= $params['catatan'];

                $data->tglpemeriksaan= $params['tglpemeriksaan'];
                $data->jampemeriksaan = $params['jampemeriksaan'];
                $data->menitpemeriksaan = $params['menitpemeriksaan'];

                $data->nopolisi = $params['nopolisi'];
                $data->namapengemudi= $params['namapengemudi'];
                $data->alamatpengemudi= $params['alamatpengemudi'];
                $data->kotapengemudi= $params['kotapengemudi'];
                $data->teleponpengemudi= $params['teleponpengemudi'];

                $data->abcup = $params['abcup'];
                $data->iduser = $params['iduser'];

                $data->cektampilkanbaik = $params['cektampilkanbaik'];
                $data->cektampilkanrusak  = $params['cektampilkanrusak'];
                $data->cektampilkantidakada = $params['cektampilkantidakada'];
                $data->idkomponenpemeriksaan = $params['idkomponenpemeriksaan'];

                $resp = $this->UnitListModel->post_unit($data);
                json_output($response['status'],$resp);
            }
        }
    }
}