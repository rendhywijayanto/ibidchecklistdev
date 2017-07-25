<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UnitLelangModel extends CI_Model{

    public function unit_lelang_all_data()
    {
        return $this->db->select('id,subtitle,')->from('webid_Auctions')->order_by('id')->get()->result();
    }

    public function unit_lelang_create_data($data)
    {
        $this->db->insert('webid_Auctions',$data);
        return array('status' => 201,'message' => 'Data has been created.');
    }

    public function unit_lelang_update_data($id,$data)
    {
        $this->db->where('id',$id)->update('webid_Auctions',$data);
        return array('status' => 200,'message' => 'Data has been updated.');
    }

   /*  public function unit_lelang_delete_data($id)
    {
        $this->db->where('id',$id)->delete('webid_Auctions');
        return array('status' => 200,'message' => 'Data has been deleted.');
    }  */
}