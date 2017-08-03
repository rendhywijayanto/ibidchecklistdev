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
        $this->load->model('DaftarUnitModel');
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

                $resp = $this->PersiapanModel->get_page_list($data);
                json_output($response['status'],$resp);
            }
        }
    }

    public function add_data()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $response = $this->AuthModel->auth();
            if($response['status'] == 200){
                $resp = $this->DaftarUnitModel->get_add_data();
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

                $data->cabangtaksasi = $params['cabangtaksasi'];
                $data->idwarnadoc = $params['idwarnadoc'];
                $data->WARNA_DOC = $params['WARNA_DOC'];
                $data->idwarnafisik = $params['idwarnafisik'];
                $data->WARNA = $params['WARNA'];
                $data->biayadmin = $params['biayadmin'];
                $data->biayaparkir = $params['biayaparkir'];
                $data->kodepenitip = $params['kodepenitip'];
                $data->namapenitip = $params['namapenitip'];
                $data->nmridpenitip = $params['nmridpenitip'];
                $data->tipeidpenitip = $params['tipeidpenitip'];
                $data->nonpwp = $params['nonpwp'];
                $data->groupbiodata = $params['groupbiodata'];
                $data->statuspeserta = $params['statuspeserta'];
                $data->jenisusaha = $params['jenisusaha'];
                $data->sebagaiperusahaan = $params['sebagaiperusahaan'];
                $data->teleponpenitip = $params['teleponpenitip'];
                $data->ponselpenitip = $params['ponselpenitip'];
                $data->alamatpenitip = $params['alamatpenitip'];
                $data->kotapenitip = $params['kotapenitip'];
                $data->kodepospenitip = $params['kodepospenitip'];
                $data->idbiodata = $params['idbiodata'];
                $data->jadwalelang = $params['jadwalelang'];
                $data->tglelang = $params['tglelang'];
                $data->cabanglelang = $params['cabanglelang'];
                $data->lokasilelang = $params['lokasilelang'];
                $data->ikutseselelang = $params['ikutseselelang'];
                $data->lokasibrglelang = $params['lokasibrglelang'];
                $data->namapicdisplay = $params['namapicdisplay'];
                $data->namapic = $params['namapic'];
                $data->jabatanpic = $params['jabatanpic'];
                $data->alamatpic = $params['alamatpic'];
                $data->kotapic = $params['kotapic'];
                $data->telppic = $params['telppic'];
                $data->abc = $params['abc'];
                $data->ponselpic = $params['ponselpic'];
                $data->faxpic = $params['faxpic'];
                $data->mailpi = $params['mailpi'];
                $data->catatan = $params['catatan'];
                $data->STNK_SD = $params['STNK_SD'];
                $data->TGL_KEUR = $params['TGL_KEUR'];
                $data->lotnumb = $params['lotnumb'];
                $data->no_polisi = $params['no_polisi'];
                $data->id_user = $params['id_user'];
                $data->abcV = $params['abcV'];

                //print_r($json);
                $resp = $this->PersiapanModel->post_persiapan_unit($data);
                json_output($response['status'],$resp);
            }
        }
    }

}