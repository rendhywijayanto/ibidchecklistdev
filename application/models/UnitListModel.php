<?php

/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 27/07/2017
 * Time: 14.21
 */

class UnitListModel extends CI_Model
{
    // METHOD GET //

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

    public function get_pagemasuk_list($data)
    {
        $query_nilai = "SELECT a.* FROM webid_pemeriksaan_item a
							  JOIN webid_auction_item b ON b.idauction_item = a.id_auctionitem
							  WHERE a.`sts_deleted` = 0 AND a.id_item = 6 AND b.deleted = 0
							  ";

        $query_nilai .= " ORDER BY a.`id_pemeriksaanitem` DESC LIMIT $data->start,$data->limit";

        return $this->get_data($query_nilai);
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

    public function get_pagekeluar_list($data)
    {
        $query_nilai = "SELECT a.* FROM webid_pemeriksaan_item a
							JOIN webid_auction_item b ON b.idauction_item = a.id_auctionitem
							  WHERE a.`sts_deleted` = 0 AND a.id_item = 6 AND a.id_pemeriksaan_klr = 1 AND b.deleted = 0
							  ";

        $query_nilai .= " ORDER BY a.`id_pemeriksaanitem` DESC LIMIT $data->start,$data->limit";

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
            $id_pemeriksaankeluar = $row_auc['id_pemeriksaanitem'];

            $query_idmerk = "SELECT b.value FROM webid_auction_detail b 
								  JOIN webid_msattribute c ON c.id_attribute = b.id_attribute
								  WHERE c.name_attribute = 'MERK' AND b.idauction_item = '" . $idauction_item . "' ";
            $run_idmerk = $this->db->query($query_idmerk);
            $row_idmerk = $run_idmerk->row_array();
            $data->id_merk = $row_idmerk['value'];

            $query_merk = "SELECT attributedetail FROM webid_msattrdetail 
									  WHERE `sts_deleted` = 0 AND id_attrdetail = '" . $data->id_merk . "'";
            $run_merk = $this->db->query($query_merk);
            $row_merk = $run_merk->row_array();
            $data->nama_merk = $row_merk['attributedetail'];

            $query_tipe = "SELECT b.value FROM webid_auction_detail b 
								  JOIN webid_msattribute c ON c.id_attribute = b.id_attribute
								  WHERE c.hv_skemataksasi = 1 AND c.sbg_parent = 0 and c.hv_attdetail = 1 AND b.idauction_item = '" . $idauction_item . "' order by c.pst_order";
            $run_tipe = $this->db->query($query_tipe);

            $seri =  array();

            foreach($run_tipe->result_array() as $row_tipe){
                $temp = new stdClass();
                $temp->id_attrdetail = $row_tipe['value'];

                $query_alltipe = "SELECT attributedetail FROM webid_msattrdetail 
									  WHERE `sts_deleted` = 0 AND id_attrdetail = '" . $temp->id_attrdetail . "'";
                $run_alltipe = $this->db->query($query_alltipe);
                $row_alltipe = $run_alltipe->row_array();
                $temp->attributedetail = $row_alltipe['attributedetail'];

                array_push($seri, $temp);
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

            $run_km = $this->db->query("SELECT a.value, c.name_attribute FROM webid_auction_detail a
			JOIN webid_auction_item b ON b.`idauction_item` = a.`idauction_item`
			JOIN webid_msattribute c ON c.`id_attribute` = a.`id_attribute`
			WHERE b.`deleted` = 0 AND b.`master_item` = '".$id_item."' AND a.idauction_item = '".$idauction_item."' AND c.hv_periksa = 1
			ORDER BY c.pst_order ASC");
            $count = $run_km->row_array();

            foreach($run_km->result_array() as $row2)
            {
                if ($row2['name_attribute'] == 'KM') {
                    $msg[] = number_format((float)$row2['value'],0,',','.')."^";
                    $data->km = number_format((float)$row2['value'],0,',','.');
                } else {
                    $msg[] = $row2['value']."^";
                }
            }

            $query_pgg = "SELECT b.value FROM webid_auction_detail b 
								  JOIN webid_msattribute c ON c.id_attribute = b.id_attribute
								  WHERE c.name_attribute = 'PENGGERAK' AND b.idauction_item = '".$idauction_item."' ";
            $run_pgg = $this->db->query($query_pgg);
            $row_pgg = $run_pgg->row_array();
            $data->penggerak = $row_pgg['value'];

            $query_komponen = "SELECT a.* , b.* FROM webid_komponen_pemeriksaan a
                JOIN webid_pemeriksaan_item_detail b on b.id_komponenpemeriksaan = a.id_komponenpemeriksaan 
                WHERE a.sts_deleted = 0 AND a.tampil = 'true' AND a.id_item = '".$id_item."' AND b.id_pemeriksaanitem = '".$id_pemeriksaankeluar."' ORDER BY a.id_komponenpemeriksaan ASC";

            $run_komponen = $this->db->query($query_komponen);
            $data->komponen = $run_komponen->result_array();

            $no++;
            $data->no = $no;
            array_push($arrData, $data);
        }
        return $arrData;
    }

    // METHOD POST //

    public function post_unit_keluar($data){
        $id_item = 6;
        $id_pemeriksaanitem = $data->idpemeriksaanitem;
        $id_auctionitem = $data->idauctionitem;
        $batas_komponen = $data->bataskomponen;

        $fuel = trim($data->fuel);
        $cat_body = trim($data->catbody);
        $cttn = trim($data->catatan);
        $WEBID_LOGGED_IN = $data->WEBID_LOGGED_IN;

        date_default_timezone_set("Asia/Jakarta");
        $time = time();
        $tglall = date("Y-m-d", $time); // dapat digunakan untuk tgl edit dan insert

        $tglpemeriksaan_klr = date('Y-m-d', strtotime($data->tglpemeriksaan));
        $jam_klr = $data->jampemeriksaan;
        $menit_klr = $data->menitpemeriksaan;
        $time_klr = $jam_klr.':'.$menit_klr.':'.'00';

        $no_polisi = trim($data->NO_POLISI);

        $cek_polisi = str_replace(' ','',$no_polisi);

        $nama_pengemudi = $data->namapengemudi;
        $alamat_pengemudi = $data->alamatpengemudi;
        $kota_pengemudi = $data->kotapengemudi;
        $telepon_pengemudi = $data->teleponpengemudi;

        $catatan = $data->catatan;

        if($no_polisi == ""){
            echo "Maaf.'$data->NO_POLISI'.!! Tidak ada data yang anda keluarkan||error";
            exit();
        }

        if($tglpemeriksaan_klr == ""){
            echo "Maaf TGL Penyerahan !! Tidak ada data yang anda keluarkan||error";
            exit();
        }

        if($nama_pengemudi == ""){
            echo "Maaf Nama Pengemudi !! Tidak ada data yang anda keluarkan||error";
            exit();
        }

        if($alamat_pengemudi == ""){
            echo "Maaf Alamat Penyerah !! Tidak ada data yang anda keluarkan||error";
            exit();
        }

        if($kota_pengemudi == ""){
            echo "Maaf Kota Penyerah !! Tidak ada data yang anda keluarkan||error";
            exit();
        }

        if($telepon_pengemudi == ""){
            echo "Maaf Telepon Penyerah !! Tidak ada data yang anda keluarkan||error";
            exit();
        }

        if ($id_pemeriksaanitem != "") {

            $query_soru = "SELECT sold , sts_tarik FROM webid_auction_item WHERE idauction_item = '".$id_auctionitem."' ";
            $run_soru = $this->db->query($query_soru);
            $row_soru = $run_soru->row_array();

            $id_user = $data->iduser;

            if ($row_soru['sold'] == 'n' and $row_soru['sts_tarik'] == 1) {
                echo "Maaf !! data unit sudah dilakukan penarikan||error";
                exit();
            }

            if ($row_soru['sold'] == 'y' and $row_soru['sts_tarik'] == 0) {
                echo "Maaf !! data unit sudah terjual||error";
                exit();
            }

            // Proses Update Auction Item
            $querySvup = "SELECT * FROM webid_msattribute a
				WHERE `sts_deleted` = 0 AND `master_item` = '".$id_item."' AND hv_periksa = 1 AND id_attribute != 10
				ORDER BY pst_order ASC";
            $runSvup = $this->db->query($querySvup);

            foreach($runSvup->result_array() as $rowSvup)
            {
                $abcUp = str_replace(' ','_',$rowSvup['name_attribute']);
                $arrayUp = trim($data->$abcUp);
                $IDUp = $rowSvup['id_attribute'];
                $query_upd = "UPDATE webid_auction_detail SET `value` = '".$arrayUp."'
						WHERE idauction_item = '".$id_auctionitem."' AND id_attribute = '".$IDUp."'";
                $run_upd = $this->db->query($query_upd);
            }

            $query_nilai_item = "UPDATE webid_pemeriksaan_item SET no_polisi = '".$cek_polisi."' , fuel = '".$fuel."' , cat_body = '".$cat_body."' ,
			`tgl_serah_klr` = '".$tglpemeriksaan_klr."',`waktu_klr` = '".$time_klr."',
			`nama_pengemudi_klr` = '".trim($nama_pengemudi)."',`alamat_pengemudi_klr` = '".trim($alamat_pengemudi)."',
			`kota_klr` = '".trim($kota_pengemudi)."', `telepon_klr` = '".trim($telepon_pengemudi)."',`id_pemeriksaan_klr` = 1,
			`catatan` = '".trim($catatan)."' , id_user = '".$id_user."'
			WHERE id_pemeriksaanitem = '".$id_pemeriksaanitem."' AND id_auctionitem = '".$id_auctionitem."' ";

            $run_nilai_item = $this->db->query($query_nilai_item);

            // $system->check_mysql($run_nilai_item, $query_nilai_item, __LINE__, __FILE__);

            for ($ti = 1; $ti < $batas_komponen; $ti++) {
                $tampilbaik_t =  $data->cektampilkanbaik[$ti];
                $tampilrusak_t =  $data->cektampilkanrusak[$ti];
                $tampiltidakada_t =  $data->cektampilkantidakada[$ti];
                $id_komponenpemeriksaan_t = $data->idkomponenpemeriksaan[$ti];

                $query_upd_detail = "UPDATE webid_pemeriksaan_item_detail SET `tampil_b` = '".$tampilbaik_t."' ,`tampil_r` = '".$tampilrusak_t."' ,`tampil_t` = '".$tampiltidakada_t."' 
				WHERE id_pemeriksaanitem = '".$id_pemeriksaanitem."' and id_komponenpemeriksaan = '".$id_komponenpemeriksaan_t."' ";
                $run_upd_detail = $this->db->query($query_upd_detail);
            }

            return array('status' => 200,'message' => 'Proses Update Item Berhasil||success');

        } else {

            $query_ceknopolisi = "SELECT * FROM webid_pemeriksaan_item 
		WHERE sts_deleted = 0 and id_item = '".$id_item."' and id_auctionitem = '".$id_auctionitem."' ";

            $run_ceknopolisi = $this->db->query($query_ceknopolisi);
            $row_ceknopolisi = $run_ceknopolisi->row_array();
            $count_ceknopolisi = $row_ceknopolisi['COUNT(*)'];

            if ($count_ceknopolisi > 0) {
                echo "Maaf Penambahan No Polisi Pemeriksaan keluar sudah ada||error";
                exit();
            } else {
                // Hanya KM Saja
                $querySvup = "SELECT * FROM webid_msattribute
				WHERE `sts_deleted` = 0 AND `master_item` = '".$id_item."' AND hv_periksa = 1 and id_attribute = 24
				ORDER BY pst_order ASC";
                $runSvup = $this->db->query($querySvup);
                // $system->check_mysql($runSvup, $querySvup, __LINE__, __FILE__);

                foreach($runSvup->result_array() as $rowSvup)
                {
                    $abcUp = str_replace(' ','_',$rowSvup['name_attribute']);
                    $arrayUp = trim($abcUp);
                    $IDUp = $rowSvup['id_attribute'];
                    $query_upd = "UPDATE webid_auction_detail SET `value` = '".$arrayUp."' 
						WHERE idauction_item = '".$id_auctionitem."' AND id_attribute = '".$IDUp."'";
                    $run_upd = $this->db->query($query_upd);
                }

                $query_nilai_item = "INSERT INTO webid_pemeriksaan_item (`id_item`,`id_auctionitem`,`no_polisi`,`fuel`,`cat_body`,`tgl_serah_klr`,`waktu_klr`,`nama_pengemudi_klr`,`alamat_pengemudi_klr`,`kota_klr`,`telepon_klr`,`id_pemeriksaan_klr`,`catatan`, `id_user` , `id_usercreateklr`) 
			VALUES ('".$id_item."','".$id_auctionitem."','".$cek_polisi."','".$fuel."','".$cat_body."','".$tglpemeriksaan_klr."','".$time_klr."','".trim($nama_pengemudi)."','".trim($alamat_pengemudi)."','".trim($kota_pengemudi)."','".trim($telepon_pengemudi)."',1,'".trim($catatan)."',
			'".$WEBID_LOGGED_IN."' , '".$WEBID_LOGGED_IN."') ";

                $run_nilai_item = $this->db->query($query_nilai_item);

                $id_pemeriksaan_item = $this->db->insert_id();
                $cek_tampilkan_baik =  $data->cektampilkanbaik;
                $cek_tampilkan_rusak = $data->cektampilkanrusak;
                $cek_tampilkan_tidakada = $data->cektampilkantidakada;
                $id_komponen_pemeriksaan = $data->idkomponenpemeriksaan;

                for ($ti = 1; $ti < $batas_komponen; $ti++) {

                    $tampilbaik_t =  $cek_tampilkan_baik[$ti];
                    $tampilrusak_t =  $cek_tampilkan_rusak[$ti];
                    $tampiltidakada_t =  $cek_tampilkan_tidakada[$ti];
                    $id_komponenpemeriksaan_t = $id_komponen_pemeriksaan[$ti];

                    $query_add_detail = "INSERT INTO webid_pemeriksaan_item_detail (`id_pemeriksaanitem`,`id_komponenpemeriksaan`,`tampil_b`,`tampil_r`,`tampil_t`) 
				VALUES ('".$id_pemeriksaan_item."','".$id_komponenpemeriksaan_t."','".$tampilbaik_t."','".$tampilrusak_t."','".$tampiltidakada_t."')";
                    $run_add_detail = $this->db->query($query_add_detail);
                }

                return array('status' => 201,'message' => 'Proses Penambahan Pemeriksaan Item keluar berhasil||success');
            }
        }
    }

    public function post_unit_masuk($data)
    {
        $id_item = 6;
        $id_pemeriksaanitem = $data->idpemeriksaanitem;
        $id_auctionitem = $data->idauctionitem;
        $batas_komponen = $data->bataskomponen;

        $fuel = trim($data->fuel);
        $cat_body = trim($data->catbody);
        $cases = trim($data->cases);
        $poolkota = trim($data->poolkota);
        $WEBID_LOGGED_IN = $data->WEBID_LOGGED_IN;

        date_default_timezone_set("Asia/Jakarta");
        $time = time();
        $tglall = date("Y-m-d", $time); // dapat digunakan untuk tgl edit dan insert

        $tglpemeriksaan_msk = date('Y-m-d', strtotime($data->tglpemeriksaan));
        $jam_msk = $data->jampemeriksaan;
        $menit_msk = $data->menitpemeriksaan;
        $time_msk = $jam_msk.':'.$menit_msk.':'.'00';

        $no_polisi = trim($data->NO_POLISI);

        $cek_polisi = str_replace(' ','',$no_polisi);

        $nama_pengemudi = $data->namapengemudi;
        $alamat_pengemudi = $data->alamatpengemudi;
        $kota_pengemudi = $data->kotapengemudi;
        $telepon_pengemudi = $data->teleponpengemudi;

        $catatan = $data->catatan;
        $sign_ibid_msk =$data->signibidmsk;
        $sign_cust_msk = $data->signcustmsk;

        $arrLampiran = array();
        array_push($arrLampiran, $data->lampiran);

        if($no_polisi == ""){
            echo "Maaf.'$data->NO_POLISI'.!! Tidak ada data yang anda keluarkan||error";
            exit();
        }

        if($tglpemeriksaan_msk == ""){
            echo "Maaf TGL Penyerahan !! Tidak ada data yang anda keluarkan||error";
            exit();
        }

        if($nama_pengemudi == ""){
            echo "Maaf Nama Pengemudi !! Tidak ada data yang anda keluarkan||error";
            exit();
        }

        if($alamat_pengemudi == ""){
                echo "Maaf Alamat Penyerah !! Tidak ada data yang anda keluarkan||error";
                exit();
        }

        if($kota_pengemudi == ""){
                echo "Maaf Kota Penyerah !! Tidak ada data yang anda keluarkan||error";
                exit();
        }

        if($telepon_pengemudi == ""){
            echo "Maaf Telepon Penyerah !! Tidak ada data yang anda keluarkan||error";
            exit();
        }

        if ($id_pemeriksaanitem != "") {

            $query_soru = "SELECT sold , sts_tarik FROM webid_auction_item WHERE idauction_item = '".$id_auctionitem."' ";
            $run_soru = $this->db->query($query_soru);
            $row_soru = $run_soru->row_array();

            $id_user = $data->iduser;

            if ($row_soru['sold'] == 'n' and $row_soru['sts_tarik'] == 1) {
                echo "Maaf !! data unit sudah dilakukan penarikan||error";
                exit();
            }

            if ($row_soru['sold'] == 'y' and $row_soru['sts_tarik'] == 0) {
                echo "Maaf !! data unit sudah terjual||error";
                exit();
            }

            // Proses Update Auction Item
            $querySvup = "SELECT * FROM webid_msattribute a
				WHERE `sts_deleted` = 0 AND `master_item` = '".$id_item."' AND hv_periksa = 1 AND id_attribute != 10
				ORDER BY pst_order ASC";
            $runSvup = $this->db->query($querySvup);

            foreach($runSvup->result_array() as $rowSvup)
            {
                $abcUp = str_replace(' ','_',$rowSvup['name_attribute']);
                $arrayUp = trim($data->$abcUp);
                $IDUp = $rowSvup['id_attribute'];
                $query_upd = "UPDATE webid_auction_detail SET `value` = '".$arrayUp."'
						WHERE idauction_item = '".$id_auctionitem."' AND id_attribute = '".$IDUp."'";
                $run_upd = $this->db->query($query_upd);
            }

            $query_nilai_item = "UPDATE webid_pemeriksaan_item SET no_polisi = '".$cek_polisi."' , fuel = '".$fuel."' , cat_body = '".$cat_body."' ,
			`tgl_serah_msk` = '".$tglpemeriksaan_msk."',`waktu_msk` = '".$time_msk."',
			`nama_pengemudi_msk` = '".trim($nama_pengemudi)."',`alamat_pengemudi_msk` = '".trim($alamat_pengemudi)."',
			`kota_msk` = '".trim($kota_pengemudi)."', `telepon_msk` = '".trim($telepon_pengemudi)."',
			`catatan` = '".trim($catatan)."' , id_user = '".$id_user."', sign_ibid_msk = '".$sign_ibid_msk."', sign_cust_msk = '".$sign_cust_msk."'
			WHERE id_pemeriksaanitem = '".$id_pemeriksaanitem."' AND id_auctionitem = '".$id_auctionitem."' ";

            $run_nilai_item = $this->db->query($query_nilai_item);

            for ($ti = 1; $ti < $batas_komponen; $ti++) {
                $tampilbaik_t =  $data->cektampilkanbaik[$ti];
                $tampilrusak_t =  $data->cektampilkanrusak[$ti];
                $tampiltidakada_t =  $data->cektampilkantidakada[$ti];
                $id_komponenpemeriksaan_t = $data->idkomponenpemeriksaan[$ti];

                $query_upd_detail = "UPDATE webid_pemeriksaan_item_detail SET `tampil_b` = '".$tampilbaik_t."' ,`tampil_r` = '".$tampilrusak_t."' ,`tampil_t` = '".$tampiltidakada_t."' 
				WHERE id_pemeriksaanitem = '".$id_pemeriksaanitem."' and id_komponenpemeriksaan = '".$id_komponenpemeriksaan_t."' ";
                $run_upd_detail = $this->db->query($query_upd_detail);
            }

            for ($j= 0; $j < 2; $j++) {

                $lampiran_namaimg = trim($arrLampiran[$j]['nama_lampiran']);
                $nmimage = $arrLampiran[$j]['base64img'];

                $query_lamp_a = "INSERT INTO webid_pemeriksaan_item_subdetail (`id_pemeriksaanitem`,`deskripsi_lampiran`,`url_lampiran`) 
                            VALUES ('" . $id_pemeriksaanitem . "','" . $lampiran_namaimg . "','" . $nmimage . "')";
                $run_lamp_a = $this->db->query($query_lamp_a);
            }

            return array('status' => 200,'message' => 'Proses Update Item Berhasil||success');

        } else {

            $query_ceknopolisi = "SELECT * FROM webid_pemeriksaan_item 
		WHERE sts_deleted = 0 and id_item = '".$id_item."' and id_auctionitem = '".$id_auctionitem."' ";

            $run_ceknopolisi = $this->db->query($query_ceknopolisi);
            $row_ceknopolisi = $run_ceknopolisi->row_array();
            $count_ceknopolisi = $row_ceknopolisi['COUNT(*)'];

            if ($count_ceknopolisi > 0) {
                echo "Maaf Penambahan No Polisi Pemeriksaan keluar sudah ada||error";
                exit();
            } else {
                // Hanya KM Saja
                $querySvup = "SELECT * FROM webid_msattribute
				WHERE `sts_deleted` = 0 AND `master_item` = '".$id_item."' AND hv_periksa = 1 and id_attribute = 24
				ORDER BY pst_order ASC";
                $runSvup = $this->db->query($querySvup);

                foreach($runSvup->result_array() as $rowSvup)
                {
                    $abcUp = str_replace(' ','_',$rowSvup['name_attribute']);
                    $arrayUp = trim($abcUp);
                    $IDUp = $rowSvup['id_attribute'];
                    $query_upd = "UPDATE webid_auction_detail SET `value` = '".$arrayUp."' 
						WHERE idauction_item = '".$id_auctionitem."' AND id_attribute = '".$IDUp."'";
                    $run_upd = $this->db->query($query_upd);
                }

                $query_nilai_item = "INSERT INTO webid_pemeriksaan_item (`id_item`,`id_auctionitem`,`no_polisi`,`fuel`,`cat_body`,`tgl_serah_msk`,`waktu_msk`,`nama_pengemudi_msk`,`alamat_pengemudi_msk`,`kota_msk`,`telepon_msk`,`catatan`,`cases`,`poolkota`, `id_user` , `id_usercreatemsk`) 
			VALUES ('".$id_item."','".$id_auctionitem."','".$cek_polisi."','".$fuel."','".$cat_body."','".$tglpemeriksaan_msk."','".$time_msk."','".trim($nama_pengemudi)."','".trim($alamat_pengemudi)."','".trim($kota_pengemudi)."','".trim($telepon_pengemudi)."','".trim($catatan)."',
			'".$cases."','".$poolkota."','".$WEBID_LOGGED_IN."' , '".$WEBID_LOGGED_IN."') ";

                $run_nilai_item = $this->db->query($query_nilai_item);

                $id_pemeriksaan_item = $this->db->insert_id();
                $cek_tampilkan_baik =  $data->cektampilkanbaik;
                $cek_tampilkan_rusak = $data->cektampilkanrusak;
                $cek_tampilkan_tidakada = $data->cektampilkantidakada;
                $id_komponen_pemeriksaan = $data->idkomponenpemeriksaan;

                for ($ti = 1; $ti < $batas_komponen; $ti++) {
                    
                    $tampilbaik_t =  $cek_tampilkan_baik[$ti];
                    $tampilrusak_t =  $cek_tampilkan_rusak[$ti];
                    $tampiltidakada_t =  $cek_tampilkan_tidakada[$ti];
                    $id_komponenpemeriksaan_t = $id_komponen_pemeriksaan[$ti];

                    $query_add_detail = "INSERT INTO webid_pemeriksaan_item_detail (`id_pemeriksaanitem`,`id_komponenpemeriksaan`,`tampil_b`,`tampil_r`,`tampil_t`) 
				VALUES ('".$id_pemeriksaan_item."','".$id_komponenpemeriksaan_t."','".$tampilbaik_t."','".$tampilrusak_t."','".$tampiltidakada_t."')";
                    $run_add_detail = $this->db->query($query_add_detail);
                }

                for ($j= 0; $j < 2; $j++) {

                    $lampiran_namaimg = trim($arrLampiran[$j]['nama_lampiran']);
                    $nmimage = $arrLampiran[$j]['base64img'];

                    $query_lamp_a = "INSERT INTO webid_pemeriksaan_item_subdetail (`id_pemeriksaanitem`,`deskripsi_lampiran`,`url_lampiran`) 
                            VALUES ('" . $id_pemeriksaanitem . "','" . $lampiran_namaimg . "','" . $nmimage . "')";
                    $run_lamp_a = $this->db->query($query_lamp_a);

                }

                return array('status' => 201,'message' => 'Proses Penambahan Pemeriksaan Item keluar berhasil||success');
            }
        }
    }
}

