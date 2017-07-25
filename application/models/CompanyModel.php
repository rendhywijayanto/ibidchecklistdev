<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CompanyModel extends CI_Model{

    public function company_all_data()
    {
        return $this->db->select('id,nama,code_company')->from('webid_company')->order_by('id')->get()->result();
    }

    public function company_create_data($data)
    {
        $this->db->insert('webid_company',$data);
        return array('status' => 201,'message' => 'Data has been created.');
    }

    public function company_update_data($id,$data)
    {
        $this->db->where('id',$id)->update('webid_company',$data);
        return array('status' => 200,'message' => 'Data has been updated.');
    }

    public function company_delete_data($id)
    {
        $this->db->where('id',$id)->delete('webid_company');
        return array('status' => 200,'message' => 'Data has been deleted.');
    } 
}