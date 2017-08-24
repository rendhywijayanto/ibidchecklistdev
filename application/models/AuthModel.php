<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthModel extends CI_Model {

    var $client_service = "frontend-client";
    var $auth_key       = "simplerestapi";
    var $MD5_PREFIX = "830afea5006de36e41eddb4b33182483";
    var $API_KEY = "dws123sdfs23wrwetsdf2234";

    public function check_auth_client(){
//        $client_service = $this->input->get_request_header('Client-Service', TRUE);
//        $auth_key  = $this->input->get_request_header('Auth-Key', TRUE);
//        if($client_service == $this->client_service && $auth_key == $this->auth_key){
//            return true;
//        } else {
//            return json_output(401,array('status' => 401,'message' => 'Unauthorized.'));
//        }

        if($_GET['api_key'] == $this->API_KEY){
            return true;
        }else{
            return json_output(401,array('status' => 401,'message' => 'Unauthorized Key'));
        }
    }

    public function login($email,$password)
    {
        $password = md5($this->MD5_PREFIX .$password);
        $query = "SELECT id, hash, suspended, name, scan_id_karyawan,setuju_peraturan,level_group FROM webid_users WHERE
                email = '" .$email . "'
                AND password = '" . $password . "' LIMIT 1";
        $res = $this->db->query($query);
        $hasil = $res->row_array();

        $username = $hasil['name'];
        if($hasil > 0){
            if ($hasil['suspended'] == 0) {
                return array('status' => 200, 'message' => 'Successfully login.', 'email' => $email, 'name' => $username, 'user_id' => $hasil['id']);
            }else{
                return array('status' => 204, 'message' => 'Account has been suspended', 'email' => $email, 'name' => $username);
            }
        }else{
            return array('status' => 204,'message' => 'Wrong password.', 'name' => $username);
        }
    }

//    public function logout()
//    {
//        $users_id  = $this->input->get_request_header('User-ID', TRUE);
//        $token     = $this->input->get_request_header('Authorization', TRUE);
//        $this->db->where('users_id',$users_id)->where('token',$token)->delete('users_authentication');
//        return array('status' => 200,'message' => 'Successfully logout.');
//    }

    public function auth()
    {
//        $users_id  = $this->input->get_request_header('User-ID', TRUE);
//        $token     = $this->input->get_request_header('Authorization', TRUE);
//        $q  = $this->db->select('expired_at')->from('users_authentication')->where('users_id',$users_id)->where('token',$token)->get()->row();
//        if($q == ""){
//            return json_output(401,array('status' => 401,'message' => 'Unauthorized.'));
//        } else {
//            if($q->expired_at < date('Y-m-d H:i:s')){
//                return json_output(401,array('status' => 401,'message' => 'Your session has been expired.'));
//            } else {
//                $updated_at = date('Y-m-d H:i:s');
//                $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
//                $this->db->where('users_id',$users_id)->where('token',$token)->update('users_authentication',array('expired_at' => $expired_at,'updated_at' => $updated_at));
//                return array('status' => 200,'message' => 'Authorized.');
//            }
//        }
        return array('status' => 200,'message' => 'Authorized.');
    }

}
