<?php

/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 25/07/2017
 * Time: 10.01
 */
class ListItemModel extends CI_Model
{

    public function get_all_item()
    {
        $query_auc = "SELECT a.* , b.value FROM webid_auction_item a
						JOIN webid_auction_detail b ON b.idauction_item = a.idauction_item
						WHERE a.deleted = 0 and b.id_attribute = 16 AND a.master_item = 6";
        $query_auc .=" ORDER BY a.idauction_item DESC LIMIT 5";

        return $this->get_data($query_auc);
    }

    public function get_search_item($id)
    {
        $query_auc = "SELECT a.* , b.value FROM webid_auction_item a
						JOIN webid_auction_detail b ON b.idauction_item = a.idauction_item
						WHERE a.deleted = 0 and b.id_attribute = 16 AND a.master_item = 6";

        if ($id != "")
        {
            $query_auc .=" AND b.value LIKE '%".$id."%' ";
        }
        $query_auc .=" ORDER BY a.idauction_item DESC LIMIT 5";

        return $this->get_data($query_auc);
    }

    private function get_data($query_auc)
    {

        $run_auc = $this->db->query($query_auc);
        $no = 1;

        $arrData = array();

        foreach($run_auc->result_array() as $row_auc){

            $data = new stdClass();

            $data->no_polisi = $row_auc['value'];
            $idauction_item = $row_auc['idauction_item'];
            $data->alfas = 'TERDAFTAR';

            if ($row_auc['sold'] == 'y') {
                $data->alfas = 'TERJUAL';
            }
            if ($row_auc['sold'] == 'n' AND $row_auc['sts_tarik'] == 1) {
                $data->alfas = 'DITARIK';
            }
            $query_hs = $this->db->query("SELECT id_auctionitem FROM webid_history_manajemenlot WHERE id_auctionitem = '" . $row_auc['idauction_item'] . "' ");
            $count_hs = $query_hs->row_array();

            if ($count_hs > 0 and $row_auc['sold'] == 'n' AND $row_auc['sts_tarik'] == 0) {
                $data->alfas = 'TD TERJUAL';
            }

            $query_idmerk = "SELECT b.value FROM webid_auction_detail b 
								  JOIN webid_msattribute c ON c.id_attribute = b.id_attribute
								  WHERE c.name_attribute = 'MERK' AND b.idauction_item = '" . $idauction_item . "' ";
            $run_idmerk = $this->db->query($query_idmerk);
            $row_idmerk = $run_idmerk->row_array();
            $id_merk = $row_idmerk['value'];

            $query_merk = "SELECT attributedetail FROM webid_msattrdetail 
									  WHERE `sts_deleted` = 0 AND id_attrdetail = '" . $id_merk . "'";
            $run_merk = $this->db->query($query_merk);
            $row_merk = $run_merk->row_array();
            $data->nama_merk = $row_merk['attributedetail'];

            $query_tipe = "SELECT b.value FROM webid_auction_detail b 
								  JOIN webid_msattribute c ON c.id_attribute = b.id_attribute
								  WHERE c.hv_skemataksasi = 1 AND c.sbg_parent = 0 and c.hv_attdetail = 1 AND b.idauction_item = '" . $idauction_item . "' order by c.pst_order";
            $run_tipe = $this->db->query($query_tipe);

            $asc = "";
            foreach($run_tipe->result_array() as $row_tipe){
                $idtipe = $row_tipe['value'];

                $query_alltipe = "SELECT attributedetail FROM webid_msattrdetail 
									  WHERE `sts_deleted` = 0 AND id_attrdetail = '" . $idtipe . "'";
                $run_alltipe = $this->db->query($query_alltipe);
                $row_alltipe = $run_alltipe->row_array();
                $join_tipe = $row_alltipe['attributedetail'];

                $asc = "$asc $join_tipe";
            }

            $data->code_b = $asc;

            $query_transmisi = "SELECT b.value FROM webid_auction_detail b 
								  JOIN webid_msattribute c ON c.id_attribute = b.id_attribute
								  WHERE c.name_attribute = 'TRANSMISI' AND b.idauction_item = '" . $idauction_item . "' ";
            $run_transmisi = $this->db->query($query_transmisi);
            $row_trans = $run_transmisi->row_array();
            $data->transmisi = $row_trans['value'];

            $query_thn = "SELECT b.value FROM webid_auction_detail b 
								  JOIN webid_msattribute c ON c.id_attribute = b.id_attribute
								  WHERE c.name_attribute = 'TAHUN' AND b.idauction_item = '" . $idauction_item . "' ";
            $run_thn = $this->db->query($query_thn);
            $row_thn = $run_thn->row_array();
            $data->tahun = $row_thn['value'];

            $query_mdl = "SELECT b.value FROM webid_auction_detail b 
								  JOIN webid_msattribute c ON c.id_attribute = b.id_attribute
								  WHERE c.name_attribute = 'MODEL' AND b.idauction_item = '" . $idauction_item . "' ";
            $run_mdl = $this->db->query($query_mdl);
            $row_mdl = $run_mdl->row_array();
            $data->model = $row_mdl['value'];

            $query_checklistin = "SELECT id_auctionitem FROM webid_pemeriksaan_item
								  WHERE id_auctionitem = '" . $idauction_item . "' and sts_deleted = 0 ";
            $run_checklistin = $this->db->query($query_checklistin);
            $data->count_checklist = $run_checklistin->row_array();

            $no++;
            $data->no = $no;

            array_push($arrData, $data);
        }
        return $arrData;
    }
}