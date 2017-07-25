<?php

/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 25/07/2017
 * Time: 16.14
 */
class UnitKeluarListModel extends CI_Model
{
    public function get_all_item()
    {
        $query_nilai = "SELECT a.* FROM webid_pemeriksaan_item a
							JOIN webid_auction_item b ON b.idauction_item = a.id_auctionitem
							  WHERE a.`sts_deleted` = 0 AND a.id_item = 6 AND a.id_pemeriksaan_klr = 1 AND b.deleted = 0
							  ";

        $query_nilai .= " ORDER BY a.`id_pemeriksaanitem` DESC LIMIT 5";

        $run_auc = mysql_query($query_nilai);
        $no = 1;

        $arrData = array();

        while ($row_auc = mysql_fetch_assoc($run_auc)) {

            $data = new stdClass();
            $idauction_item = $row_auc['id_auctionitem'];

            $query_idmerk = "SELECT b.value FROM webid_auction_detail b 
								  JOIN webid_msattribute c ON c.id_attribute = b.id_attribute
								  WHERE c.name_attribute = 'MERK' AND b.idauction_item = '" . $idauction_item . "' ";
            $run_idmerk = mysql_query($query_idmerk);
            $row_idmerk = mysql_fetch_assoc($run_idmerk);
            $id_merk = $row_idmerk['value'];

            $query_merk = "SELECT attributedetail FROM webid_msattrdetail 
									  WHERE `sts_deleted` = 0 AND id_attrdetail = '" . $id_merk . "'";
            $run_merk = mysql_query($query_merk);
            $row_merk = mysql_fetch_assoc($run_merk);
            $data->nama_merk = $row_merk['attributedetail'];

            $query_tipe = "SELECT b.value FROM webid_auction_detail b 
								  JOIN webid_msattribute c ON c.id_attribute = b.id_attribute
								  WHERE c.hv_skemataksasi = 1 AND c.sbg_parent = 0 and c.hv_attdetail = 1 AND b.idauction_item = '" . $idauction_item . "' order by c.pst_order";
            $run_tipe = mysql_query($query_tipe);

            $asc = "";
            while ($row_tipe = mysql_fetch_assoc($run_tipe)) {
                $idtipe = $row_tipe['value'];

                $query_alltipe = "SELECT attributedetail FROM webid_msattrdetail 
									  WHERE `sts_deleted` = 0 AND id_attrdetail = '" . $idtipe . "'";
                $run_alltipe = mysql_query($query_alltipe);
                $row_alltipe = mysql_fetch_assoc($run_alltipe);
                $join_tipe = $row_alltipe['attributedetail'];

                $asc = "$asc $join_tipe";
            }

            $data->code_b = str_replace('-', '', $asc);

            $query_transmisi = "SELECT b.value FROM webid_auction_detail b 
								  JOIN webid_msattribute c ON c.id_attribute = b.id_attribute
								  WHERE c.name_attribute = 'TRANSMISI' AND b.idauction_item = '" . $idauction_item . "' ";
            $run_transmisi = mysql_query($query_transmisi);
            $row_trans = mysql_fetch_assoc($run_transmisi);
            $data->transmisi = $row_trans['value'];

            $query_thn = "SELECT b.value FROM webid_auction_detail b 
								  JOIN webid_msattribute c ON c.id_attribute = b.id_attribute
								  WHERE c.name_attribute = 'TAHUN' AND b.idauction_item = '" . $idauction_item . "' ";
            $run_thn = mysql_query($query_thn);
            $row_thn = mysql_fetch_assoc($run_thn);
            $data->tahun = $row_thn['value'];

            $query_mdl = "SELECT b.value FROM webid_auction_detail b 
								  JOIN webid_msattribute c ON c.id_attribute = b.id_attribute
								  WHERE c.name_attribute = 'MODEL' AND b.idauction_item = '" . $idauction_item . "' ";
            $run_mdl = mysql_query($query_mdl);
            $row_mdl = mysql_fetch_assoc($run_mdl);
            $data->model = $row_mdl['value'];

            $no++;
            $data->no = $no;
            array_push($arrData, $data);
        }
        return $arrData;
    }
}