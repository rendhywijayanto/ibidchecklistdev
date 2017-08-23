<?php

/**
 * Created by PhpStorm.
 * User: harfi
 * Date: 25/07/2017
 * Time: 10.01
 */
class PersiapanModel extends CI_Model
{

    public function get_item_list()
    {
        $query_auc = "SELECT a.* , b.value AS no_polisi,c.id_pemeriksaanitem FROM webid_auction_item a
						JOIN webid_auction_detail b ON b.idauction_item = a.idauction_item
                        LEFT JOIN webid_pemeriksaan_item c ON a.idauction_item = c.id_auctionitem
						WHERE a.deleted = 0 and b.id_attribute = 16 AND a.master_item = 6";
        $query_auc .=" ORDER BY a.idauction_item DESC LIMIT 5";

        return $this->get_data($query_auc);
    }

    public function get_list_search($id)
    {
        $query_auc = "SELECT a.* , b.value AS no_polisi,c.id_pemeriksaanitem FROM webid_auction_item a
						JOIN webid_auction_detail b ON b.idauction_item = a.idauction_item
                        LEFT JOIN webid_pemeriksaan_item c ON a.idauction_item = c.id_auctionitem
						WHERE a.deleted = 0 and b.id_attribute = 16 AND a.master_item = 6";

        if ($id != "")
        {
            $query_auc .=" AND b.value LIKE '%".$id."%' ";
        }
        $query_auc .=" ORDER BY a.idauction_item DESC LIMIT 5";

        return $this->get_data($query_auc);
    }

    public function get_page_list($data)
    {
        $query_auc = "SELECT a.* , b.value AS no_polisi,c.* FROM webid_auction_item a
						JOIN webid_auction_detail b
                        JOIN webid_pemeriksaan_item c ON b.idauction_item = a.idauction_item
						WHERE a.deleted = 0 and b.id_attribute = 16 AND a.master_item = 6";
        $query_auc .=" ORDER BY a.idauction_item DESC LIMIT $data->start,$data->limit";

        return $this->get_data($query_auc);
    }

    private function get_data($query_auc)
    {
        $run_auc = $this->db->query($query_auc);
        $no = 1;
        $id_item = 6;

        $arrData = array();

        foreach($run_auc->result_array() as $row_auc){

            $data = new stdClass();
            $data->auction = $row_auc;

            $idauction_item = $row_auc['idauction_item'];

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
                    $data->km = $row2['value']."";
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

            $query_checklistin = "SELECT id_auctionitem FROM webid_pemeriksaan_item
								  WHERE id_auctionitem = '".$idauction_item."' and sts_deleted = 0 ";
            $run_checklistin = $this->db->query($query_checklistin);
            $count_checklist = $run_checklistin->num_rows();

            $data->count_checklist = $count_checklist;

            $query_komponen = "SELECT * FROM webid_komponen_pemeriksaan WHERE sts_deleted = 0 AND tampil = 'true' and id_item = '".$id_item."' ORDER BY id_komponenpemeriksaan ASC";
            $run_komponen = $this->db->query($query_komponen);
            $count_komponen = $run_komponen->row_array();

            $arrKomponen = array();

            foreach($run_komponen->result_array() as $row_komponen)
            {
                    array_push($arrKomponen, $row_komponen);
            }
            $data->komponen = $arrKomponen;

            $no++;
            $data->no = $no;
            array_push($arrData, $data);
        }
        return $arrData;
    }

    //METHOD POST

    public function post_persiapan_unit($data)
    {
        $id_item = 6;
        $idauction_item = '';
        $id_user = $data->id_user;

        $sts = 'TERDAFTAR'; //3
        $cabang_taksasi = $data->cabangtaksasi; //Tambahan Untuk Kenza

        if ($cabang_taksasi == "") {
            $cabang_taksasi = 0;
            $taks = 'true';
        } else {
            $taks = 'false';
        }

        if ($data->idwarnadoc == "") {

            $wrndoc = trim($data->WARNA_DOC);
            $query_idwarna = $this->db->query("SELECT id_warna FROM webid_warna WHERE nama_warna = '" . $wrndoc . "' and id_warnaresmi = 1");
            $count_idwarna = $query_idwarna->row_array();

            if ($count_idwarna > 0) {
                $id_warnadoc = $count_idwarna['id_warna'];
            } else {
                $id_warnadoc = 0;
            }

        } else {
            $id_warnadoc = $data->idwarnadoc;
        }

        if ($data->idwarnafisik == "") {

            $wrndoc = trim($data->WARNA);
            $query_idwarna = $this->db->query("SELECT id_warna FROM webid_warna WHERE nama_warna = '" . $wrndoc . "' and id_warnaresmi = 1");
            $count_idwarna = $query_idwarna->row_array();
            $run_idwarna = $query_idwarna->result_array();
            if ($count_idwarna > 0) {
                $id_warnafisik = $run_idwarna['id_warna'];
            } else {
                $id_warnafisik = 0;
            }

        } else {
            $id_warnafisik = $data->idwarnafisik;
        }

        $biaya = trim($data->biayadmin);
        if ($biaya == "") {
            $query_biay = "SELECT biaya_admin FROM webid_msitem WHERE id_item = '" . $id_item . "' and sts_deleted = 0 ";
            $run_biay = $this->db->query($query_biay);
            $row_biay = $run_biay->row_array();
            $biaya = $row_biay['biaya_admin'];
        }
        $biayaparkir = trim($data->biayaparkir);

        if ($biayaparkir == "") {
            $biayaparkir = 0;
        }

//$tglregister = date("Y-m-d", strtotime($data->tgl_register));

        $kode_penitip = $data->kodepenitip; //8
        $nm_penitip = $data->namapenitip;
        $nmrId_penitip = $data->nmridpenitip;
        $tipeId_penitip = $data->tipeidpenitip;
        $nmr_NPWP = $data->nonpwp; //tambah
        $group_biodata = $data->groupbiodata; //
        $status_peserta = $data->statuspeserta; //
        $jenis_usaha = $data->jenisusaha; //
        $sbg_perusahaan = $data->sebagaiperusahaan;    // Status Biodata
        $telepon_penitip = $data->teleponpenitip;
        $ponsel_penitip = $data->ponselpenitip;
        $almt_penitip = $data->alamatpenitip;
        $kota_penitip = $data->kotapenitip;
        $kodepos_penitip = $data->kodepospenitip;
        $id_biodata = $data->idbiodata;

        if ($id_biodata == "") {
            $id_biodata = 0;
        }

        $jdwl_lelang = $data->jadwalelang; //5
        $tgl_lelang = $data->tglelang;
        $cbng_lelang = $data->cabanglelang;
        $lokasi_lelang = str_replace(',', '.', $data->lokasilelang);
        $ikutsesi_lelang = $data->ikutseselelang;

        $lks_brg_lelang = trim($data->lokasibrglelang); //8
        $almt_brg_lelang = trim($data->alamatbrglelang);
        $kota_brg_lelang = trim($data->kotabrglelang);
        $lks_display = trim($data->lokasidisplay);
        $kota_display = trim($data->kotadisplay);
        $almt_display = trim($data->alamatdisplay);
        $namapic_display = trim($data->namapicdisplay);
        $telppic_display = trim($data->telppicdisplay);

        $namapic = trim($data->namapic); //9
        $jbtnpic = trim($data->jabatanpic);
        $almtpic = trim($data->alamatpic);
        $kotapic = trim($data->kotapic);
        $telppic = trim($data->telppic);
        $ponselpic = trim($data->ponselpic);
        $faxpic = trim($data->faxpic);
        $mailpic = trim($data->mailpi);
        $cttn = trim($data->catatan);
//$data->idschedule']
        $id_schedule = 0; //

//$stnksd = date('d/m/Y',strtotime($data->STNK_SD));

        $data->STNK_SD = date('d/m/Y', strtotime($data->STNK_SD));
        $data->TGL_KEUR = date('d/m/Y', strtotime($data->TGL_KEUR));

//echo $data->TGL_KEUR'].'||error';
//exit();

        $lot_numb = $data->lotnumb; //

        date_default_timezone_set("Asia/Jakarta");
        $time = time();
        $tglall = date("Y-m-d H:i:s", $time); // dapat digunakan untuk tgl edit dan insert

        $time2 = time();
        $tgladd = date("Y-m-d H:i:s", $time2);

        if ($tgl_lelang == "") {
            $tgl_lelang = '0000-00-00';
        }

        $aucI_lastpos = "SELECT idauction_item FROM webid_auction_item ORDER BY idauction_item DESC LIMIT 1";
        $run_aucI_lastpos = $this->db->query($aucI_lastpos);
        $id1 = $run_aucI_lastpos->row_array();
        if ($id1['idauction_item'] == 0) {
            $idV = 1;
        } else {
            $idV = $id1['idauction_item'] + 1;
        }

        if ($id_schedule != 0 OR $id_schedule != "") {
            $aucI_lastlot = "SELECT lot_numb FROM webid_auction_item where id_schedule = '" . $id_schedule . "' ORDER BY lot_numb DESC LIMIT 1";
            $run_aucI_lastlot = $this->db->query($aucI_lastlot);
            $idlot = $run_aucI_lastlot->result_array();
            if ($idlot['lot_numb'] == 0) {
                $idVlot = 1;
            } else {
                $idVlot = $idlot['lot_numb'] + 1;
            }
        }
        //JOIN webid_msitem i ON i.`id_item` = a.`master_item`
        $querySv = "SELECT name_attribute FROM webid_msattribute 
			WHERE `sts_deleted` = 0 AND `master_item` = '" . $id_item . "' and hv_mandatory = 1
			ORDER BY `pst_order`"; //var_dump($query); exit();
        $runSv = $this->db->query($querySv);
        $countSv = $runSv->num_rows() - 1;

        $messageEm = "";
        foreach ($runSv->result_array() as $rowSv)
        {
            $abc = str_replace(' ', '_', $rowSv['name_attribute']);
            $array = $data->$abc;
            if (empty($array)
            ) {
                $messageEm = "$messageEm $abc,";
            }
        }
        if (!empty($messageEm)
        ) {

            if (empty($data->ponselpic)) {
                $acdA = 'DATA PIC PONSEl,';
            }

            if (empty($data->namapenitip)) {
                $acdA = 'NAMA PENITIP,';
            }

            echo "Maaf $acdA $messageEm $acdA !! Tidak ada data yang anda masukkan||error";
            exit();

        } else {

            if (empty($data->namapenitip)) {
                $acdC = 'NAMA PENITIP,';
                echo "Maaf $acdC !! Tidak ada data yang anda masukkan||error";
                exit();
            }

            if (empty($data->ponselpic)) {
                $acdA = 'DATA PIC PONSEl,';
                echo "Maaf $acdA !! Tidak ada data yang anda masukkan||error";
                exit();
            }

        }

        if ($idauction_item != "") {
            echo $idauction_item . ' ' . "Proses Update Item Gagal||error";
            exit();

            $no_polisi = trim($data->NO_POLISI);
            $cek_polisi = str_replace(' ', '', $no_polisi);
            //JOIN webid_msitem i ON i.`id_item` = b.`master_item`
            $query_cekno_polisi = "SELECT a.value, b.sold , b.sts_tarik , b.idauction_item FROM webid_auction_detail a
        JOIN webid_auction_item b ON b.`idauction_item` = a.`idauction_item`
        JOIN webid_msattribute c ON c.`id_attribute` = a.`id_attribute`
        WHERE b.`deleted` = 0 AND b.`master_item` = '" . $id_item . "' AND c.name_attribute = 'NO POLISI'
        AND a.value = '" . $cek_polisi . "' ORDER BY a.`idauction_item`, c.`pst_order`";
            $run_cekno_polisi = $this->db->query($query_cekno_polisi);
            $row_cekno_polisi = $run_cekno_polisi->result_array();
            $sold = $row_cekno_polisi['sold'];
            $unsold_tarik = $row_cekno_polisi['sts_tarik'];
            //$unsold_tarik = $row_cekno_polisi['unsold_tarik']; // Pilihan antara y dan n
            $count_cekno_polisi = $run_cekno_polisi->row_array();

            if ($count_cekno_polisi > 0) {
                $idAuctionitem = $row_cekno_polisi['idauction_item'];
                if ($idAuctionitem != $idauction_item and $sold == 'n' and $unsold_tarik == 0) {
                    echo "Maaf !! No Polisi Sudah ada||error";
                    exit();
                }
            }

            $query_soru = "SELECT sold , sts_tarik FROM webid_auction_item WHERE idauction_item = '" . $idauction_item . "' ";
            $run_soru = $this->db->query($query_soru);
            $row_soru = $run_soru->result_array();

            if ($row_soru['sold'] == 'n' and $row_soru['sts_tarik'] == 1) {
                echo "Maaf !! data unit sudah dilakukan penarikan||error";
                exit();
            }

            if ($row_soru['sold'] == 'y' and $row_soru['sts_tarik'] == 0) {
                $query_updsubQ = "UPDATE webid_auction_subdetail SET 
                    `kode_anggota_pntp` = '" . $kode_penitip . "',`name_pntp` = '" . $nm_penitip . "',`nomor_idents_pntp` = '" . $nmrId_penitip . "',`tipe_idents_pntp` = '" . $tipeId_penitip . "',`telepon_pntp` = '" . $telepon_penitip . "',`ponsel_pntp` = '" . $ponsel_penitip . "',`alamat_pntp` = '" . $almt_penitip . "',`kota_pntp` = '" . $kota_penitip . "',`kodepos_pntp` = '" . $kodepos_penitip . "',
                    `tgl_edit`= '" . $tglall . "',`status_biodata` = '" . $sbg_perusahaan . "' ,`status_peserta` = '" . $status_peserta . "',`jenis_usaha` = '" . $jenis_usaha . "',`no_npwp` = '" . $nmr_NPWP . "',`groupBiodata` = '" . $group_biodata . "',id_biodata = '" . $id_biodata . "',biaya_parkir = '" . $biayaparkir . "'
                    WHERE idauction_item = '" . $idauction_item . "'";
                $run_updsubQ = $this->db->query($query_updsubQ);

                echo "Maaf !! unit sudah terjual , Proses Update Penitip , biaya Parkir dan Ekspedisi berhasil||success";
                exit();
            }
            // Di Non Aktifkan Sementara
            $query_auctions = "SELECT closed,id_schedule FROM webid_auctions WHERE idauction_item = '" . $idauction_item . "' order by id DESC LIMIT 1";
            $run_auctions = $this->db->query($query_auctions);
            $count_a = $run_auctions->row_array();
            $row_auction = $run_auctions->result_array();

            if ($row_auction > 0) {
                if ($row_auction['closed'] == 0) {
                    $idsch = $row_auction['id_schedule'];
                    $query_sch = "SELECT schedule_date FROM webid_schedule WHERE schedule_id = '" . $idsch . "' ";
                    $run_sch = $this->db->query($query_sch);
                    $row_sch = $run_sch->result_array();
                    $tglsch = strtotime($row_sch['schedule_date']);
                    $tgltime = date('Y-m-d', strtotime(time()));
                    $tgltime2 = strtotime($tgltime);
                    if ($tgltime2 > $tglsch) {
                        echo "Maaf !! unit belum dilakukan close pada Lelang Sebelumnya||error";
                        exit();
                    }
                }
            }

            if ($id_schedule != "" or $id_schedule != 0) {

                $query_numb = "SELECT lot_numb FROM webid_auction_item WHERE idauction_item = '" . $idauction_item . "' and id_schedule = '" . $id_schedule . "' ";
                $run_numb = $this->db->query($query_numb);
                $row_numb = $run_numb->result_array();

                if ($row_numb['lot_numb'] == 0 or $row_numb['lot_numb'] == "") {
                    $lot_numb = $idVlot;
                } else {
                    $lot_numb = $row_numb['lot_numb'];
                }

                $query_updauc = $this->db->query("UPDATE webid_auction_item SET lot_numb = '" . $lot_numb . "' , id_schedule = '" . $id_schedule . "' , id_user = '" . $id_user . "' , id_cabang = '" . $cabang_taksasi . "', id_warnadoc = '" . $id_warnadoc . "' , id_warnafisik = '" . $id_warnafisik . "',sts_lelang = 1 WHERE idauction_item = '" . $idauction_item . "'");
            } else {
                $query_updauc = $this->db->query("UPDATE webid_auction_item SET lot_numb = NULL , id_schedule = 0 , id_user = '" . $id_user . "' , id_cabang = '" . $cabang_taksasi . "' , id_warnadoc = '" . $id_warnadoc . "' , id_warnafisik = '" . $id_warnafisik . "', sts_lelang = 0 WHERE idauction_item = '" . $idauction_item . "'");
            }

            //Update nilai item taksasi
            //$query_updnilaitem = $this->db->query("UPDATE webid_nilai_item SET no_polisi = '".$data->no_polisi']."' , stnk_an = '".$data->STNK_AN']."' , kota = '".$data->KOTA']."', id_user = '".$id_user."' WHERE idauction_item = '".$idauction_item."'");

            $querySvup = "SELECT * FROM webid_msattribute
        WHERE `sts_deleted` = 0 AND `master_item` = '" . $id_item . "'
        ORDER BY `pst_order`"; //var_dump($query); exit();
            $runSvup = $this->db->query($querySvup);

            foreach ($runSvup->result_array() as $rowSvup) {
                $abcUp = str_replace(' ', '_', $rowSvup['name_attribute']);
                $arrayUp = trim($data->$abcUp);
                $IDUp = $rowSvup['id_attribute'];
                $query_upd = "UPDATE webid_auction_detail SET `value` = '" . trim($arrayUp) . "' 
                    WHERE idauction_item = '" . $idauction_item . "' AND id_attribute = '" . $IDUp . "'";
                $run_upd = $this->db->query($query_upd);
            }

            $query_ceksubdetail = $this->db->query("SELECT idauction_item FROM webid_auction_subdetail WHERE idauction_item = '" . $idauction_item . "'");
            $count_ceksubdetail = $query_ceksubdetail->row_array();

            if ($count_ceksubdetail == 0) {

                $query_addsubA = "INSERT INTO webid_auction_subdetail 
            (`idauction_item`,`taksasi`,`biaya_admin`,`status`,`kode_anggota_pntp`, `name_pntp`,`nomor_idents_pntp`,`tipe_idents_pntp`,`telepon_pntp`,`ponsel_pntp`,`alamat_pntp`,`kota_pntp`,`kodepos_pntp`,`jadwal_lelang`,`tgl_lelang`,`cbng_lelang`,`lokasi_lelang`,`ikut_sesi`,`lokasi_brg_lelang`,`alamat_brg_lelang`,`kota_brg_lelang`,`lokasi_display_lelang`,`alamat_display_lelang`,`kota_display_lelang`,`namapic_display`,`telppic_display`,`nama_pic`,`jabatan_pic`,`ponsel_pic`,`mail_pic`,`cttn_pndftrn`,`tgl_register`,`status_biodata`,`status_peserta`,`jenis_usaha`,`no_npwp`,`groupBiodata`,id_biodata,biaya_parkir) 
            VALUES ('" . $idauction_item . "','" . $taks . "','" . $biaya . "','" . $sts . "','" . $kode_penitip . "','" . $nm_penitip . "','" . $nmrId_penitip . "','" . $tipeId_penitip . "','" . $telepon_penitip . "','" . $ponsel_penitip . "','" . $almt_penitip . "','" . $kota_penitip . "','" . $kodepos_penitip . "','" . $jdwl_lelang . "','" . $tgl_lelang . "','" . $cbng_lelang . "','" . $lokasi_lelang . "','" . $ikutsesi_lelang . "','" . $lks_brg_lelang . "','" . $almt_brg_lelang . "','" . $kota_brg_lelang . "','" . $lks_display . "','" . $almt_display . "','" . $kota_display . "','" . $namapic_display . "','" . $telppic_display . "','" . $namapic . "','" . $jbtnpic . "','" . $ponselpic . "','" . $mailpic . "','" . $cttn . "','" . $tglall . "','" . $sbg_perusahaan . "','" . $status_peserta . "','" . $jenis_usaha . "','" . $nmr_NPWP . "','" . $group_biodata . "','" . $id_biodata . "','" . $biayaparkir . "')";

                $run_addsubA = $this->db->query($query_addsubA);

            } else {

                $query_updsub = "UPDATE webid_auction_subdetail SET 
                    `taksasi` = '" . $taks . "',`biaya_admin` = '" . $biaya . "',`kode_anggota_pntp` = '" . $kode_penitip . "',`name_pntp` = '" . $nm_penitip . "',`nomor_idents_pntp` = '" . $nmrId_penitip . "',`tipe_idents_pntp` = '" . $tipeId_penitip . "',`telepon_pntp` = '" . $telepon_penitip . "',`ponsel_pntp` = '" . $ponsel_penitip . "',`alamat_pntp` = '" . $almt_penitip . "',`kota_pntp` = '" . $kota_penitip . "',`kodepos_pntp` = '" . $kodepos_penitip . "',`jadwal_lelang` = '" . $jdwl_lelang . "',`tgl_lelang` = '" . $tgl_lelang . "',`cbng_lelang` = '" . $cbng_lelang . "',`lokasi_lelang` = '" . $lokasi_lelang . "',`ikut_sesi` = '" . $ikutsesi_lelang . "',
                    `lokasi_brg_lelang` = '" . $lks_brg_lelang . "' , `lokasi_display_lelang` = '" . $lks_display . "',`alamat_display_lelang` = '" . $almt_display . "',`kota_display_lelang` = '" . $kota_display . "',`namapic_display` = '" . $namapic_display . "',`telppic_display` = '" . $telppic_display . "',`nama_pic` = '" . $namapic . "',`jabatan_pic` = '" . $jbtnpic . "',`ponsel_pic` = '" . $ponselpic . "',`mail_pic` = '" . $mailpic . "',`cttn_pndftrn` = '" . $cttn . "',`tgl_edit`= '" . $tglall . "',`status_biodata` = '" . $sbg_perusahaan . "' ,`status_peserta` = '" . $status_peserta . "',`jenis_usaha` = '" . $jenis_usaha . "',`no_npwp` = '" . $nmr_NPWP . "',`groupBiodata` = '" . $group_biodata . "',`id_biodata` = '" . $id_biodata . "',`biaya_parkir` = '" . $biayaparkir . "'
                    WHERE idauction_item = '" . $idauction_item . "'";
                $run_updsub = $this->db->query($query_updsub);
            }
            echo $idV."update";
            return array('status' => 201,'message' => 'Proses Update Item Berhasil||success');
        } else {
            $no_polisi = trim($data->no_polisi);
            $cek_polisi = str_replace(' ', '', $no_polisi);

            $query_cekno_polisi = "SELECT a.value, b.* FROM webid_auction_detail a
                JOIN webid_auction_item b ON b.`idauction_item` = a.`idauction_item`
                JOIN webid_msattribute c ON c.`id_attribute` = a.`id_attribute`
                WHERE b.`deleted` = 0 AND b.`master_item` = '" . $id_item . "' AND c.name_attribute = 'NO POLISI'
                AND a.value = '" . $cek_polisi . "' ORDER BY a.`idauction_item`, c.`pst_order`";
            $run_cekno_polisi = $this->db->query($query_cekno_polisi);
            $row_cekno_polisi = $run_cekno_polisi->row_array();
            $sold = $row_cekno_polisi['sold'];
            $unsold_tarik = $row_cekno_polisi['sts_tarik'];
            //$unsold_tarik = $row_cekno_polisi['unsold_tarik']; // Pilihan antara y dan n
            $count_cekno_polisi = $run_cekno_polisi->row_array();

            if ($count_cekno_polisi > 0) {

                if ($sold == 'n' and $unsold_tarik == 0) {
                    // AND ditarik sama dengan n
                    return array('status' => 204,'message' => 'Item sudah ada.');
                } else {
                    echo "TEST||error";
                    exit();
                    $querySv1 = "SELECT * FROM webid_msattribute
                WHERE `sts_deleted` = 0 AND `master_item` = '" . $id_item . "'
                ORDER BY `pst_order`";
                    $runSv1 = $this->db->query($querySv1);

                    echo $idV;

                    if ($id_schedule != "" or $id_schedule != 0) {
                        $query_add = "INSERT INTO webid_auction_item (`idauction_item`,`master_item`,`lot_numb`,`id_schedule`,`id_user`,`id_cabang`,`id_warnadoc`,`id_warnafisik`,`sts_lelang`) 
                VALUES ('" . $idV . "','" . $id_item . "','" . $idVlot . "','" . $id_schedule . "','" . $id_user . "','" . $cabang_taksasi . "','" . $id_warnadoc . "','" . $id_warnafisik . "',1)";
                    } else {
                        $query_add = "INSERT INTO webid_auction_item (`idauction_item`,`master_item`,`id_user`,`id_cabang`,`id_warnadoc`,`id_warnafisik`) 
                    VALUES ('" . $idV . "','" . $id_item . "','" . $id_user . "','" . $cabang_taksasi . "','" . $id_warnadoc . "','" . $id_warnafisik . "')";
                    }

                    //var_dump($query_add);exit();

                    $run_add = $this->db->query($query_add);

                    echo $idV."update";
                    foreach ($runSv1->result_array() as $rowSv1) {
                        $abcV = str_replace(' ', '_', $rowSv1['name_attribute']);
                        $idSv = $rowSv1['id_attribute'];
                        $arrayV = trim($data->$abcV);

                        echo $arrayV;
                        if ($abcV == 'no_polisi') {
                            $arrayV = $cek_polisi;
                        }
                        $query_add2 = "INSERT INTO webid_auction_detail (`idauction_item`,`id_attribute`,`value`) 
                            VALUES ('" . $idV . "','" . $idSv . "','" . trim($arrayV) . "')";
                        $run_add2 = $this->db->query($query_add2);
                    }

                    $query_addsubA = "INSERT INTO webid_auction_subdetail 
            (`idauction_item`,`taksasi`,`biaya_admin`,`status`,`kode_anggota_pntp`, `name_pntp`,`nomor_idents_pntp`,`tipe_idents_pntp`,`telepon_pntp`,`ponsel_pntp`,`alamat_pntp`,`kota_pntp`,`kodepos_pntp`,`jadwal_lelang`,`tgl_lelang`,`cbng_lelang`,`lokasi_lelang`,`ikut_sesi`,`lokasi_brg_lelang`,`alamat_brg_lelang`,`kota_brg_lelang`,`lokasi_display_lelang`,`alamat_display_lelang`,`kota_display_lelang`,`namapic_display`,`telppic_display`,`nama_pic`,`jabatan_pic`,`ponsel_pic`,`mail_pic`,`cttn_pndftrn`,`tgl_register`,`status_biodata`,`status_peserta`,`jenis_usaha`,`no_npwp`,`groupBiodata`,id_biodata,biaya_parkir) 
            VALUES ('" . $idV . "','" . $taks . "','" . $biaya . "','" . $sts . "','" . $kode_penitip . "','" . $nm_penitip . "','" . $nmrId_penitip . "','" . $tipeId_penitip . "','" . $telepon_penitip . "','" . $ponsel_penitip . "','" . $almt_penitip . "','" . $kota_penitip . "','" . $kodepos_penitip . "','" . $jdwl_lelang . "','" . $tgl_lelang . "','" . $cbng_lelang . "','" . $lokasi_lelang . "','" . $ikutsesi_lelang . "','" . $lks_brg_lelang . "','" . $almt_brg_lelang . "','" . $kota_brg_lelang . "','" . $lks_display . "','" . $almt_display . "','" . $kota_display . "','" . $namapic_display . "','" . $telppic_display . "','" . $namapic . "','" . $jbtnpic . "','" . $ponselpic . "','" . $mailpic . "','" . $cttn . "','" . $tglall . "','" . $sbg_perusahaan . "','" . $status_peserta . "','" . $jenis_usaha . "','" . $nmr_NPWP . "','" . $group_biodata . "','" . $id_biodata . "','" . $biayaparkir . "')";

                    $run_addsubA = $this->db->query($query_addsubA);

                    return array('status' => 200,'message' => 'Proses Penambahan Item Berhasil||success');
                }

            } else {
                echo $idV."update1";

                $querySv1 = "SELECT * FROM webid_msattribute
                    WHERE `sts_deleted` = 0 AND `master_item` = '" . $id_item . "'
                    ORDER BY `pst_order`";
                $runSv1 = $this->db->query($querySv1);

                if ($id_schedule != "" or $id_schedule != 0) {
                    $query_add = "INSERT INTO webid_auction_item (`idauction_item`,`master_item`,`lot_numb`,`id_schedule`,`id_user`,`id_cabang`,`id_warnadoc`,`id_warnafisik`,`sts_lelang`) 
                    VALUES ('" . $idV . "','" . $id_item . "','" . $idVlot . "','" . $id_schedule . "','" . $id_user . "','" . $cabang_taksasi . "','" . $id_warnadoc . "','" . $id_warnafisik . "',1)";
                } else {
                    $query_add = "INSERT INTO webid_auction_item (`idauction_item`,`master_item`,`id_user`,`id_cabang`,`id_warnadoc`,`id_warnafisik`) 
                    VALUES ('" . $idV . "','" . $id_item . "','" . $id_user . "','" . $cabang_taksasi . "','" . $id_warnadoc . "','" . $id_warnafisik . "')";
                }

                $run_add = $this->db->query($query_add);

                foreach ($runSv1->result_array() as $rowSv1) {
                    $abcV = str_replace(' ', '_', $rowSv1['name_attribute']);
                    $idSv = $rowSv1['id_attribute'];
                    $arrayV = trim($data->$abcV);
                    echo " ". $idSv. " ".$abcV;
                    if ($abcV == 'no_polisi') {
                        $arrayV = $cek_polisi;
                    }
                    $query_add2 = "INSERT INTO webid_auction_detail (`idauction_item`,`id_attribute`,`value`) 
                    VALUES ('" . $idV . "','" . $idSv . "','" . trim($arrayV) . "')";

                    $run_add2 = $this->db->query($query_add2);
                }

                $query_addsubA = "INSERT INTO webid_auction_subdetail 
            (`idauction_item`,`taksasi`,`biaya_admin`,`status`,`kode_anggota_pntp`, `name_pntp`,`nomor_idents_pntp`,`tipe_idents_pntp`,`telepon_pntp`,`ponsel_pntp`,`alamat_pntp`,`kota_pntp`,`kodepos_pntp`,`jadwal_lelang`,`tgl_lelang`,`cbng_lelang`,`lokasi_lelang`,`ikut_sesi`,`lokasi_brg_lelang`,`alamat_brg_lelang`,`kota_brg_lelang`,`lokasi_display_lelang`,`alamat_display_lelang`,`kota_display_lelang`,`namapic_display`,`telppic_display`,`nama_pic`,`jabatan_pic`,`ponsel_pic`,`mail_pic`,`cttn_pndftrn`,`tgl_register`,`status_biodata`,`status_peserta`,`jenis_usaha`,`no_npwp`,`groupBiodata`,`id_biodata`,`biaya_parkir`) 
            VALUES ('" . $idV . "','" . $taks . "','" . $biaya . "','" . $sts . "','" . $kode_penitip . "','" . $nm_penitip . "','" . $nmrId_penitip . "','" . $tipeId_penitip . "','" . $telepon_penitip . "','" . $ponsel_penitip . "','" . $almt_penitip . "','" . $kota_penitip . "','" . $kodepos_penitip . "','" . $jdwl_lelang . "','" . $tgl_lelang . "','" . $cbng_lelang . "','" . $lokasi_lelang . "','" . $ikutsesi_lelang . "','" . $lks_brg_lelang . "','" . $almt_brg_lelang . "','" . $kota_brg_lelang . "','" . $lks_display . "','" . $almt_display . "','" . $kota_display . "','" . $namapic_display . "','" . $telppic_display . "','" . $namapic . "','" . $jbtnpic . "','" . $ponselpic . "','" . $mailpic . "','" . $cttn . "','" . $tglall . "','" . $sbg_perusahaan . "','" . $status_peserta . "','" . $jenis_usaha . "','" . $nmr_NPWP . "','" . $group_biodata . "','" . $id_biodata . "','" . $biayaparkir . "')";

                $run_addsubA = $this->db->query($query_addsubA);

                return array('status' => 202,'message' => 'Proses Penambahan Item Berhasil||success');
            }
        }
    }

}