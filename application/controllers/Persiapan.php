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
            $check_auth_client = $this->AuthModel->check_auth_client();
            if($check_auth_client = true){
                $response = $this->AuthModel->auth();
                if($response['status'] == 200){
                    $resp = $this->PersiapanModel->get_item_list();
                    json_output($response['status'],$resp);
                }
            }
        }
    }

    public function search($id)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET' || $this->uri->segment(3) == ''){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->AuthModel->check_auth_client();
            if($check_auth_client == true){
                $response = $this->AuthModel->auth();
                if($response['status'] == 200){
                    $resp = $this->PersiapanModel->get_list_search($id);
                    json_output($response['status'],$resp);
                }
            }
        }
    }

    public function page()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {

            $check_auth_client = $this->AuthModel->check_auth_client();
            if($check_auth_client == true){
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
    }

    public function add_data()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(400,array('status' => 400,'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->AuthModel->check_auth_client();
            if($check_auth_client == true){
                $response = $this->AuthModel->auth();
                if($response['status'] == 200){
                    $resp = $this->DaftarUnitModel->get_add_data();
                    json_output($response['status'],$resp);
                }
            }
        }
    }

    public function insert()
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

                    $data->cabangtaksasi = $params['cabangtaksasi'];
                    $data->idwarnadoc = $params['idwarnadoc'];
                    $data->WARNA_DOC = $params['WARNA_DOC'];
                    $data->idwarnafisik = $params['idwarnafisik'];
                    $data->WARNA = $params['WARNA'];

                    $data->NO_POLISI = $params['no_polisi'];
                    $data->NO_RANGKA = $params['no_rangka'];
                    $data->STNK_AN = $params['STNK_AN'];
                    $data->NO_MESIN = $params['NO_MESIN'];
                    $data->ALAMAT = $params['ALAMAT'];
                    $data->NO_BPKB = $params['NO_BPKB'];
                    $data->KOTA = $params['KOTA'];
                    $data->NO_FAKTUR = $params['NO_FAKTUR'];
                    $data->MERK = $params['MERK'];
                    $data->NO_STNK = $params['NO_STNK'];
                    $data->SERI = $params['SERI'];
                    $data->SILINDER = $params['SILINDER'];
                    $data->NO_KEUR = $params['NO_KEUR'];
                    $data->GRADE = $params['GRADE'];
                    $data->SUB_GRADE = $params['SUB_GRADE'];
                    $data->MODEL = $params['MODEL'];
                    $data->PLAT_DASAR = $params['PLAT_DASAR'];
                    $data->PENGGERAK = $params['PENGGERAK'];
                    $data->TRANSMISI = $params['TRANSMISI'];
                    $data->BAHAN_BAKAR = $params['BAHAN_BAKAR'];
                    $data->TAHUN = $params['TAHUN'];
                    $data->KM = $params['KM'];
                    $data->SUMBER = $params['SUMBER'];
                    $data->KUNCI_TAMBAHAN = $params['KUNCI_TAMBAHAN'];

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
                    $data->kotabrglelang = $params['kotabrglelang'];
                    $data->alamatbrglelang = $params['alamatbrglelang'];

                    $data->lokasidisplay = $params['lokasidisplay'];
                    $data->kotadisplay = $params['kotadisplay'];
                    $data->alamatdisplay = $params['alamatdisplay'];
                    $data->namapicdisplay = $params['namapicdisplay'];
                    $data->telppicdisplay = $params['telppicdisplay'];
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
                    $data->no_polisi = $params['no_polisi'];
                    $data->id_user = $params['id_user'];

                    $resp = $this->PersiapanModel->post_persiapan_unit($data);
                    json_output($response['status'],$resp);
                }
            }

        }
    }

}