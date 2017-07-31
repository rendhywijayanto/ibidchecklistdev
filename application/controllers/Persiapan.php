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
                $data->ponselpic = $params['ponselpic'];
                $data->faxpic = $params['faxpic'];
                $data->mailpi = $params['mailpi'];
                $data->catatan = $params['catatan'];
                $data->STNK_SD = $params['STNK_SD'];
                $data->TGL_KEUR = $params['TGL_KEUR'];
                $data->lotnumb = $params['lotnumb'];
                $data->nopolisi = $params['nopolisi'];

                $resp = $this->PersiapanModel->post_persiapan_unit($data);
                json_output($response['status'],$resp);
            }
        }
    }

}