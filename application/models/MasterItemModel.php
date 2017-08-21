<?php
/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 20/08/2017
 * Time: 21.45
 */

class MasterItemModel extends CI_Model
{
    public function get_All_item($param)
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
}