<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 02/08/2017
 * Time: 15.15
 */

class DaftarUnitModel extends CI_Model
{

    public function get_add_data()
    {
        $data = new stdClass();
        
        $query_merk = "SELECT b.id_attrdetail , b.attributedetail FROM webid_msattribute a JOIN 
											webid_msattrdetail b ON b.master_attribute = a.id_attribute WHERE a.id_attribute = 1 
											and a.master_item = 6 order by attributedetail ASC ";
        $run_merk = $this->db->query($query_merk);

        $arrData = array();

        foreach($run_merk->result_array() as $row_merk){
            array_push($arrData , $row_merk);
        }
        $data->merk = $arrData;

        $query_subgr = "SELECT b.id_attrdetail , b.attributedetail FROM webid_msattribute a JOIN 
											webid_msattrdetail b ON b.master_attribute = a.id_attribute WHERE a.id_attribute = 5 
											and a.master_item = 6 order by attributedetail ASC ";
        $run_subgr = $this->db->query($query_subgr);

        $arrData = array();

        foreach($run_subgr->result_array() as $row_subgr)
        {
            array_push($arrData , $row_subgr);
        }
        $data->subgr = $arrData;

        $query_plat = "SELECT b.id_attrdetail , b.attributedetail FROM webid_msattribute a JOIN 
											webid_msattrdetail b ON b.master_attribute = a.id_attribute WHERE a.id_attribute = 25 
											and a.master_item = 6 order by attributedetail ASC ";
        $run_plat = $this->db->query($query_plat);

        $arrData = array();

        foreach($run_plat->result_array() as $row_plat)
        {
            array_push($arrData , $row_plat);
        }
        $data->plat = $arrData;

        return $data;
    }

}