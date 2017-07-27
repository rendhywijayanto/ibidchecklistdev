<?php

/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 27/07/2017
 * Time: 14.21
 */
class UnitListModel extends CI_Model
{
    public function get_item_list()
    {
        $query_auc = "SELECT a.* , b.value FROM webid_auction_item a
						JOIN webid_auction_detail b ON b.idauction_item = a.idauction_item
						WHERE a.deleted = 0 and b.id_attribute = 16 AND a.master_item = 6";
        $query_auc .=" ORDER BY a.idauction_item DESC LIMIT 5";

        return $this->get_data($query_auc);
    }

    public function get_list_search($id)
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

    public function get_unitmasuk_list()
    {
        $query_nilai = "SELECT a.* FROM webid_pemeriksaan_item a
							  JOIN webid_auction_item b ON b.idauction_item = a.id_auctionitem
							  WHERE a.`sts_deleted` = 0 AND a.id_item = 6 AND b.deleted = 0
							  ";

        $query_nilai .= " ORDER BY a.`id_pemeriksaanitem` DESC LIMIT 5";

        return $this->get_data($query_nilai);
    }

    public function get_unitmasuk_search($id)
    {
        $query_nilai = "SELECT a.* FROM webid_pemeriksaan_item a
							  JOIN webid_auction_item b ON b.idauction_item = a.id_auctionitem
							  WHERE a.`sts_deleted` = 0 AND a.id_item = 6 AND b.deleted = 0
							  ";

        if ($id != "") {
            $str = str_replace(' ','',trim($id));
            $query_nilai .= "AND a.no_polisi LIKE '%".$str."%'";
        }

        $query_nilai .= " ORDER BY a.`id_pemeriksaanitem` DESC LIMIT 5";

        return $this->get_data($query_nilai);
    }

    public function get_auction_detail($id)
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

    public function get_unitkeluar_list()
    {
        $query_nilai = "SELECT a.* FROM webid_pemeriksaan_item a
							JOIN webid_auction_item b ON b.idauction_item = a.id_auctionitem
							  WHERE a.`sts_deleted` = 0 AND a.id_item = 6 AND a.id_pemeriksaan_klr = 1 AND b.deleted = 0
							  ";

        $query_nilai .= " ORDER BY a.`id_pemeriksaanitem` DESC LIMIT 5";

        return $this->get_data($query_nilai);
    }

    public function get_unitkeluar_search($id)
    {
        $query_nilai = "SELECT a.* FROM webid_pemeriksaan_item a
							JOIN webid_auction_item b ON b.idauction_item = a.id_auctionitem
							  WHERE a.`sts_deleted` = 0 AND a.id_item = 6 AND a.id_pemeriksaan_klr = 1 AND b.deleted = 0
							  ";

        if ($id != "") {
            $str = str_replace(' ','',trim($id));
            $query_nilai .= "AND a.no_polisi LIKE '%".$str."%'";
        }

        $query_nilai .= " ORDER BY a.`id_pemeriksaanitem` DESC LIMIT 5";

        return $this->get_data($query_nilai);
    }

    public function get_stock_list()
    {
        $query_nilai = "SELECT a.* FROM webid_pemeriksaan_item a
							  JOIN webid_auction_item b ON b.idauction_item = a.id_auctionitem
							  WHERE a.`sts_deleted` = 0 AND a.id_item = 6 AND b.deleted = 0
							  ";

        $query_nilai .= " ORDER BY a.`id_pemeriksaanitem` DESC LIMIT 5";

        return $this->get_data($query_nilai);
    }

    public function get_stock_search($id)
    {
        $query_nilai = "SELECT a.* FROM webid_pemeriksaan_item a
							  JOIN webid_auction_item b ON b.idauction_item = a.id_auctionitem
							  WHERE a.`sts_deleted` = 0 AND a.id_item = 6 AND b.deleted = 0
							  ";
        if ($id != "") {
            $str = str_replace(' ','',trim($id));
            $query_nilai .= "AND a.no_polisi LIKE '%".$str."%'";
        }

        $query_nilai .= " ORDER BY a.`id_pemeriksaanitem` DESC LIMIT 5";

        return $this->get_data($query_nilai);
    }

    private function get_data($query_nilai){
        $run_auc = $this->db->query($query_nilai);
        $no = 1;
        $id_item = 6;

        $arrData = array();

        foreach($run_auc->result_array() as $row_auc){

            $data = new stdClass();
            $data->auction = $row_auc;
            $idauction_item = $row_auc['id_auctionitem'];
            $id_pemeriksaanmasuk = $row_auc['id_pemeriksaanitem'];

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

            $seri =  array();

            foreach($run_tipe->result_array() as $row_tipe){
                $idtipe = $row_tipe['value'];

                $query_alltipe = "SELECT attributedetail FROM webid_msattrdetail 
									  WHERE `sts_deleted` = 0 AND id_attrdetail = '" . $idtipe . "'";
                $run_alltipe = $this->db->query($query_alltipe);
                $row_alltipe = $run_alltipe->row_array();
                $join_tipe = $row_alltipe['attributedetail'];

                array_push($seri, $join_tipe);
            }
            $data->tipe = $seri;

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

            $query_subdetail = "SELECT b.name_pntp , b.alamat_pntp , b.kota_pntp , b.ponsel_pntp FROM webid_auction_item a
                JOIN webid_auction_subdetail b ON b.idauction_item = a.idauction_item 
                WHERE a.deleted = 0 AND a.master_item = '".$id_item."' AND b.idauction_item = '".$idauction_item."' AND b.remove = 0 ORDER BY b.idauction_subdetail ASC";
            $run_subdetail = $this->db->query($query_subdetail);
            $data->pntp = $run_subdetail->row_array();

            $query_km="SELECT a.value, c.name_attribute FROM webid_auction_detail a
			JOIN webid_auction_item b ON b.`idauction_item` = a.`idauction_item`
			JOIN webid_msattribute c ON c.`id_attribute` = a.`id_attribute`
			WHERE b.`deleted` = 0 AND b.`master_item` = '".$id_item."' AND a.idauction_item = '".$idauction_item."' AND c.hv_periksa = 1
			ORDER BY c.pst_order ASC";
            $run_km = $this->db->query($query_km);
            $data->km = $run_km->row_array();

            foreach($run_km->result_array() as $row2){
                if ($row2['name_attribute'] == 'KM') {
                    $msg[] = number_format($row2['value'],0,',','.')."^";
                } else {
                    $msg[] = $row2['value']."^";
                }
            }

            $query_komponen = "SELECT a.* , b.* FROM webid_komponen_pemeriksaan a
                JOIN webid_pemeriksaan_item_detail b on b.id_komponenpemeriksaan = a.id_komponenpemeriksaan 
                WHERE a.sts_deleted = 0 AND a.tampil = 'true' AND a.id_item = '".$id_item."' AND b.id_pemeriksaanitem = '".$id_pemeriksaanmasuk."' ORDER BY a.id_komponenpemeriksaan ASC";

            $run_komponen = $this->db->query($query_komponen);
            $data->komponen = $run_komponen->result_array();

            $no++;
            $data->no = $no;
            array_push($arrData, $data);
        }
        return $arrData;
    }
}