<?php
/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 04/09/2017
 * Time: 09.38
 */

class StockModel extends CI_Model
{

    public function get_stock_list()
    {
        $timeAS = date('Y-m-d', strtotime('-7 days'));
        $timenows = date('Y-m-d', time());
        $query_nilai = "SELECT a.* FROM webid_pemeriksaan_item a
							  JOIN webid_auction_item b ON b.idauction_item = a.id_auctionitem
							  WHERE a.`sts_deleted` = 0 AND a.id_item = 6 AND b.deleted = 0 AND (a.tgl_serah_msk >= '" . $timeAS . "' 
							  AND a.tgl_serah_msk <= '" . $timenows . "')
							  ";

        $query_nilai .= " ORDER BY a.tgl_serah_msk DESC ";

        return $this->get_data($query_nilai);
    }

    public function get_stock_search($id)
    {
        $timeAS = date('Y-m-d', strtotime('-7 days'));
        $timenows = date('Y-m-d', time());
        $query_nilai = "SELECT a.* FROM webid_pemeriksaan_item a
							  JOIN webid_auction_item b ON b.idauction_item = a.id_auctionitem
							  WHERE a.`sts_deleted` = 0 AND a.id_item = 6 AND b.deleted = 0 AND (a.tgl_serah_msk >= '" . $timeAS . "' 
							  AND a.tgl_serah_msk <= '" . $timenows . "')
							  ";

        if ($id != "") {
            $str = str_replace(' ','',trim($id));
            $query_nilai .= "AND a.no_polisi LIKE '%".$str."%'";
        }

        $query_nilai .= " ORDER BY a.tgl_serah_msk DESC ";

        return $this->get_data($query_nilai);
    }

    public function get_pagestock_list($data)
    {

        $timeAS = date('Y-m-d', strtotime('-7 days'));
        $timenows = date('Y-m-d', time());
        $query_nilai = "SELECT a.* FROM webid_pemeriksaan_item a
							  JOIN webid_auction_item b ON b.idauction_item = a.id_auctionitem
							  WHERE a.`sts_deleted` = 0 AND a.id_item = 6 AND b.deleted = 0 AND (a.tgl_serah_msk >= '" . $timeAS . "' 
							  AND a.tgl_serah_msk <= '" . $timenows . "')
							  ";

        $query_nilai .= " ORDER BY a.tgl_serah_msk DESC LIMIT $data->start,$data->limit";

        return $this->get_data($query_nilai);
    }
    
    public function get_data($query_nilai)
    {
        $run_auc = $this->db->query($query_nilai);
        $no = 1;
        $id_item = 6;

        $arrData = array();

        foreach ($run_auc->result_array() as $row_auc) {

            $data = new stdClass();

            $idauction_item = $row_auc['id_auctionitem'];
            $data->cabang = $row_auc['poolkota'];
            $data->nopol = $row_auc['no_polisi'];
            $data->tgl_serah_msk = $row_auc['tgl_serah_msk'];

            $query_idmerk = "SELECT b.value FROM webid_auction_detail b 
								  JOIN webid_msattribute c ON c.id_attribute = b.id_attribute
								  WHERE c.name_attribute = 'MERK' AND b.idauction_item = '" . $idauction_item . "' ";
            $run_idmerk = $this->db->query($query_idmerk);
            $row_idmerk = $run_idmerk->row_array();
            $id_merk = $row_idmerk['value'];
            $data->id_merk = $id_merk;

            $query_merk = "SELECT attributedetail FROM webid_msattrdetail 
									  WHERE `sts_deleted` = 0 AND id_attrdetail = '" . $id_merk . "'";
            $run_merk = $this->db->query($query_merk);
            $row_merk = $run_merk->row_array();
            $data->nama_merk = $row_merk['attributedetail'];

            $query_tipe = "SELECT b.value FROM webid_auction_detail b 
								  JOIN webid_msattribute c ON c.id_attribute = b.id_attribute
								  WHERE c.hv_skemataksasi = 1 AND c.sbg_parent = 0 and c.hv_attdetail = 1 AND b.idauction_item = '" . $idauction_item . "' order by c.pst_order";
            $run_tipe = $this->db->query($query_tipe);

            //====================================================

            $asc = "";

            foreach ($run_tipe->result_array() as $row_tipe) {
                $temp = new stdClass();
                $id_attrdetail = $row_tipe['value'];

                $query_alltipe = "SELECT attributedetail FROM webid_msattrdetail 
									  WHERE `sts_deleted` = 0 AND id_attrdetail = '" . $id_attrdetail . "'";
                $run_alltipe = $this->db->query($query_alltipe);
                $row_alltipe = $run_alltipe->row_array();
                $join_tipe = $row_alltipe['attributedetail'];

                $asc = "$asc $join_tipe";

            }
            $data->code_b = str_replace('-','',$asc);

            //====================================================

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

            $query_pgg = "SELECT b.value FROM webid_auction_detail b 
								  JOIN webid_msattribute c ON c.id_attribute = b.id_attribute
								  WHERE c.name_attribute = 'PENGGERAK' AND b.idauction_item = '" . $idauction_item . "' ";
            $run_pgg = $this->db->query($query_pgg);
            $row_pgg = $run_pgg->row_array();
            $data->penggerak = $row_pgg['value'];

            $query_subdetail = "SELECT b.name_pntp FROM webid_auction_item a
										JOIN webid_auction_subdetail b ON b.idauction_item = a.idauction_item 
										WHERE a.deleted = 0 AND a.master_item = 6 AND b.idauction_item = '" . $idauction_item . "' AND b.remove = 0 ORDER BY b.idauction_subdetail ASC";
            $run_subdetail = $this->db->query($query_subdetail);
            $row_subdetail = $run_subdetail->row_array();

            $data->pemilik = $row_subdetail['name_pntp'];

            $query_expedisi = "SELECT deskripsi_biaya FROM webid_auction_subdetail_lampiran
										WHERE del = 0 AND id_dsb = 1 AND idauction_item = '" . $idauction_item . "' order by idauction_subdetail_lamp DESC LIMIT 1";
            $run_expedisi = $this->db->query($query_expedisi);
            $count_exps = $run_expedisi->num_rows();

            if ($count_exps > 0) {
                $row_expedisi = $run_expedisi->row_array();
                $nama_exps = $row_expedisi['deskripsi_biaya'];
            } else {
                $nama_exps = '-';
            }

            $data->nama_exps = $nama_exps;

            $query_schd = "SELECT id_schedule , (SELECT schedule_date FROM webid_schedule WHERE schedule_id = webid_auctions.id_schedule) as tglsold FROM webid_auctions 
							  WHERE num_bids > 0 AND idauction_item = '" . $idauction_item . "' AND status_item = 0 ";
            $run_schd = $this->db->query($query_schd);
            $row_schd = $run_schd->row_array();
            if ($row_schd['tglsold'] == '') {
                $tglsold = '-';
            } else {
                $tglsold = date('d M Y', strtotime($row_schd['tglsold']));
            }

            $data->tgl_sold = $tglsold;

            if ($row_auc['tgl_serah_klr'] == '') {
                $tgl_out = '-';
                $selisih_hari = ceil(((time() - strtotime($row_auc['tgl_serah_msk'])) / (60 * 60 * 24)));
            } else {
                $tgl_out = date("d M Y", strtotime($row_auc['tgl_serah_klr']));
                $selisih_hari = ceil(((strtotime($row_auc['tgl_serah_klr']) - strtotime($row_auc['tgl_serah_msk'])) / (60 * 60 * 24)));
            }

            $data->tgl_out = $tgl_out;
            $data->selisih_hari = $selisih_hari;

            $query_auct = "SELECT id_schedule FROM webid_auctions 
							  WHERE idauction_item = '" . $idauction_item . "' AND status_item != 2 ";
            $run_auct = $this->db->query($query_auct);
            $ikutlelang =  $run_auct->num_rows(); // Berapa Kali Ikut Lelang

            if ($ikutlelang == null) {
                $ikutlelang = 0;
            }
            $data->ikutlelang = $ikutlelang;
            $data->cases = $row_auc['cases'];

            array_push($arrData, $data);
        }
        return $arrData;
    }
}