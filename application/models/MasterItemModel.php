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

    public function get_all_warna()
    {
        $itemArr = array();

        $query_warna = $this->db->query("SELECT * FROM webid_warna WHERE sts_deleted = 0 and id_warnaresmi = 1 LIMIT 10");
        foreach ($query_warna->result_array() as $query3) {

            $data = new stdClass();

            $data->id_attrdetail = $query3['id_warna'];
            $data->attributedetail = $query3['nama_warna'];
            array_push($itemArr, $data);

        }

        return $itemArr;
    }

    public function get_all_nama($param){
        if(isset($param->term))
        {
            $return_arr = array();
            $nama= trim($param->term);
            $query2= $this->db->query("SELECT * FROM webid_biodata WHERE name LIKE '%".$nama."%' LIMIT 10");

            foreach ($query2->result_array() as $row)
            {
                $tgl_lahir = date("m/d/Y",strtotime($row['tanggal_lahir']));
                $return_arr[]=array(
                    'value'=> $row['name']."||".$row['no_identitas']."||".$row['groupBiodata']."||".$row['alamat']."||".$row['kota']."||".$row['kode_pos']."||".$row['telepon']."||".$row['ext']."||".$row['ponsel']."||".$row['fax']."||".$row['email']."||".$row['catatan']."||".$row['status_peserta']."||".$row['kode_sap']."||".$row['vendor_code']."||".$row['fee']."||".$row['ppn']."||".$row['pph']."||".$row['nama_bank']."||".$row['nama_rekening']."||".$row['nomor_rekening']."||".$row['jenis_perusahaan']."||".$row['pekerjaan']."||".$row['tempat_lahir']."||". $tgl_lahir."||".$row['kode_anggota']."||".$row['id']."||".$row['tipe_identitas']."||".$row['status_biodata']."||".$row['no_npwp'],
                    'label'=> $row['name']
                );
            }
            return json_encode($return_arr);
        }else{
            return array('status' => 204,'message' => 'Nama Penitip Kosong');
        }
    }
}