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
}