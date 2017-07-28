<?php
/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 28/07/2017
 * Time: 09.10
 */

class HomeModel extends CI_Model
{
    public function get_data()
    {
        $data = new stdClass();

        $query_daftar = "SELECT a.idauction_item FROM webid_auction_item a
						JOIN webid_auction_subdetail b ON b.idauction_item = a.idauction_item
						WHERE a.deleted = 0 and b.id_biodata != 0 AND a.master_item = 6";

        $data->count_daftar = $this->db->query($query_daftar)->num_rows();

        $query_jual = "SELECT a.idauction_item FROM webid_auction_item a
						JOIN webid_auction_subdetail b ON b.idauction_item = a.idauction_item
						WHERE a.deleted = 0 and a.sold = 'y' and b.id_biodata != 0 AND a.master_item = 6";

        $data->count_jual = $this->db->query($query_jual)->num_rows();

        $query_masuk = "SELECT a.idauction_item FROM webid_auction_item a
						JOIN webid_pemeriksaan_item b ON b.id_auctionitem = a.idauction_item
						WHERE a.deleted = 0 and b.sts_deleted = 0 AND a.master_item = 6";

        $data->count_masuk = $this->db->query($query_masuk)->num_rows();

        $query_keluar = "SELECT a.idauction_item FROM webid_auction_item a
						JOIN webid_pemeriksaan_item b ON b.id_auctionitem = a.idauction_item
						WHERE a.deleted = 0 and b.sts_deleted = 0 and id_pemeriksaan_klr = 1 AND a.master_item = 6";

        $data->count_keluar = $this->db->query($query_keluar)->num_rows();
        
        return $data;
    }
}