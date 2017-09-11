<?php
/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 20/08/2017
 * Time: 21.45
 */

class MasterItemModel extends CI_Model
{
    public function get_all_item($param)
    {
        $itemArr = array();

        if($param->id_item == 0) {
            array_push($itemArr, array('id_attrdetail' => 0,'attributedetail' => ''));
        }else{
            $query1 = "SELECT * FROM webid_msattrdetail where id_parent = '" . $param->id_item . "' and sts_deleted = 0 ORDER by attributedetail ASC";
            $query2 = $this->db->query($query1);
            $count1 = $query2->row_array();

            if ($count1 < 1) {
                array_push($itemArr, array('id_attrdetail' => 0,'attributedetail' => ''));
            } else {
                foreach ($query2->result_array() as $query3) {
                    array_push($itemArr, $query3);
                }
            }
        }
        return $itemArr;
    }

    public function get_warna_search($id)
    {
        $itemArr = array();

        $query_warna = $this->db->query("SELECT * FROM webid_warna WHERE nama_warna LIKE '%".$id."%' and sts_deleted = 0 and id_warnaresmi = 1 LIMIT 10");
        foreach ($query_warna->result_array() as $query3) {

            $data = new stdClass();

            $data->id_attrdetail = $query3['id_warna'];
            $data->attributedetail = $query3['nama_warna'];
            array_push($itemArr, $data);

        }

        return $itemArr;
    }

    public function get_all_nama($id){

        $return_arr = array();
        $query2= $this->db->query("SELECT * FROM webid_biodata WHERE name LIKE '%".$id."%' LIMIT 10");

        foreach ($query2->result_array() as $row)
        {
            $tgl_lahir = date("m/d/Y",strtotime($row['tanggal_lahir']));
            $return_arr[]= array(
                'nama_penitip' => $row['name'],
                'no_identitas' => $row['no_identitas'],
                'groupBiodata' => $row['groupBiodata'],
                'alamat' => $row['alamat'],
                'kota' => $row['kota'],
                'kode_pos' => $row['kode_pos'],
                'telepon' => $row['telepon'],
                'ext' => $row['ext'],
                'ponsel' => $row['ponsel'],
                'fax' => $row['fax'],
                'email' => $row['email'],
                'catatan' => $row['catatan'],
                'status_peserta' => $row['status_peserta'],
                'kode_sap' => $row['kode_sap'],
                'vendor_code' => $row['vendor_code'],
                'fee' => $row['fee'],
                'ppn' => $row['ppn'],
                'pph' => $row['pph'],
                'nama_bank' => $row['nama_bank'],
                'nama_rekening' => $row['nama_rekening'],
                'jenis_perusahaan' => $row['jenis_perusahaan'],
                'pekerjaan' => $row['pekerjaan'],
                'tempat_lahir' => $row['tempat_lahir'],
                'tgl_lahir' => $tgl_lahir,
                'kode_anggota' => $row['kode_anggota'],
                'id' => $row['id'],
                'tipe_identitas' => $row['tipe_identitas'],
                'status_biodata' => $row['status_biodata'],
                'no_npwp' => $row['no_npwp']
            );
        }
        return $return_arr;
    }

    public function get_all_model($id){
        $return_arr = array();

        $query2=$this->db->query("SELECT * FROM webid_msmodel WHERE nama LIKE '%".$id."%' and sts_deleted = 0 LIMIT 10");
        foreach ($query2->result_array() as $query3)
        {
            $return_arr[]=array(
                'value'=> $query3['id_model'],
                'label'=> $query3['nama']
            );
        }

        return $return_arr;
    }

    public function get_all_kota($id){
        $return_arr = array();

        $query2=$this->db->query("SELECT * FROM webid_ms_kota WHERE nama_kota LIKE '%".$id."%' and sts_deleted = 0 LIMIT 10");
        foreach ($query2->result_array() as $query3)
        {
            $return_arr[]=array(
                'value'=> $query3['id'],
                'label'=> $query3['nama_kota']
            );
        }

        return $return_arr;
    }

    //=============================================

    public function get_sign_img_msk($data){

        $id_pemeriksaanitem = $data['id_pemeriksaanitem'];
        $id_auctionitem = $data['id_auctionitem'];

        $query_nilai_item = "SELECT sign_ibid_msk, sign_cust_msk FROM webid_pemeriksaan_item
			WHERE id_pemeriksaanitem = '".$id_pemeriksaanitem."' AND id_auctionitem = '".$id_auctionitem."' ";

        $run_sign = $this->db->query($query_nilai_item);
        $row_sign = $run_sign->row_array();

        return $row_sign;

    }

    public function get_sign_img_klr($data){

        $id_pemeriksaanitem = $data['id_pemeriksaanitem'];
        $id_auctionitem = $data['id_auctionitem'];

        $query_nilai_item = "SELECT sign_ibid_klr, sign_cust_klr FROM webid_pemeriksaan_item
			WHERE id_pemeriksaanitem = '".$id_pemeriksaanitem."' AND id_auctionitem = '".$id_auctionitem."' ";

        $run_sign = $this->db->query($query_nilai_item);
        $row_sign = $run_sign->row_array();

        return $row_sign;
    }

    public function get_lamp_img($id_pemeriksaanitem){

        $query_lamp = "SELECT * FROM webid_pemeriksaan_item_subdetail 
                        WHERE id_pemeriksaanitem = '.$id_pemeriksaanitem.' LIMIT 2";

        $run_lamp = $this->db->query($query_lamp);

        $lamp = array();

        foreach($run_lamp->result_array() as $row_lamp) {

            $data = new stdClass();

            $data->nama_lampiran = $row_lamp['deskripsi_lampiran'];
            $data->base64img = $row_lamp['url_lampiran'];

            array_push($lamp, $data);
        }

        return $lamp;
    }

    public function post_sign_img_msk($data){

        $id_pemeriksaanitem = $data['id_pemeriksaanitem'];
        $id_auctionitem = $data['id_auctionitem'];
        $sign_ibid_msk = $data['sign_ibid_msk'];
        $sign_cust_msk = $data['sign_cust_msk'];

        $query_nilai_item = "UPDATE webid_pemeriksaan_item SET sign_ibid_msk = '".$sign_ibid_msk."' , sign_cust_msk = '".$sign_cust_msk."' 
			WHERE id_pemeriksaanitem = '".$id_pemeriksaanitem."' AND id_auctionitem = '".$id_auctionitem."' ";

        $run_nilai_item = $this->db->query($query_nilai_item);

        return array('status' => 200,'message' => 'Proses Update Image Berhasil||success');
    }

    public function post_sign_img_klr($data){

        $id_pemeriksaanitem = $data['id_pemeriksaanitem'];
        $id_auctionitem = $data['id_auctionitem'];
        $sign_ibid_klr = $data['sign_ibid_klr'];
        $sign_cust_klr = $data['sign_cust_klr'];

        $query_nilai_item = "UPDATE webid_pemeriksaan_item SET sign_ibid_msk = '".$sign_ibid_klr."' , sign_cust_msk = '".$sign_cust_klr."' 
        WHERE id_pemeriksaanitem = '".$id_pemeriksaanitem."' AND id_auctionitem = '".$id_auctionitem."' ";

        $run_nilai_item = $this->db->query($query_nilai_item);

        return array('status' => 200,'message' => 'Proses Update Image Berhasil||success');
    }

    public function post_lamp_img($data){

        if (!empty($data)) {
            for ($j = 0; $j < 2; $j++) {

                $id_pemeriksaanitem = $data[$j]['idpemeriksaan_item'];
                $lampiran_namaimg = trim($data[$j]['nama_lampiran']);
                $nmimage = $data[$j]['base64img'];

                $query_lamp_a = "INSERT INTO webid_pemeriksaan_item_subdetail (`id_pemeriksaanitem`,`deskripsi_lampiran`,`url_lampiran`) 
                            VALUES ('" . $id_pemeriksaanitem . "','" . $lampiran_namaimg . "','" . $nmimage . "')";
                $run_lamp_a = $this->db->query($query_lamp_a);
            }
            return array('status' => 201,'message' => 'Proses penambahan image berhasil||success');
        }else{
            return array('status' => 204,'message' => 'Proses penambahan image gagal||gagal');
        }
    }

    //============================================

}