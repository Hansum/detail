<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class LoginRegisterModel extends CI_Model {

    public function __construct(){
        parent::__construct();

        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
		header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
		header("Access-Control-Allow-Origin: *");
        header('Content-Type: application/json');
        header('Access-Control-Allow-Credentials: true');
    }

    //FETCH USER
    public function getUser($username,$password){

        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $res=$this->db->get();
        if($res)
        {
            return $res->result();
        }
        return FALSE;
    }


    //REGISTRATION
    public function signUp($user)
    {
        $x = FALSE;
        // $this->load->library('mongo_db',array('activate'=>'detailDatabase'),'mongo_detailDatabase');
        $registerResult = $this->db->insert('user', $user);
        if($registerResult){
            $insert_id = $this->db->insert_id();
            $dummy = array(
                'user_id' => $insert_id,
                'fname' => 'edit firstname here',
                'lname' => 'edit lastname here',
                'email' => 'edit email here',
                'dob' => 'edit dob here',
                'organization' => 'edit organization here'
            );
            $detailResult = $this->db->insert('user_detail',$dummy);
            if($detailResult)
            {
                $x = TRUE;
            }
            return $x;
        } else {
            return $x;
        }
    }

    //INSERTING USER DETAILS
    public function insertUserInfo($res)
    {
        $result = $this->db->insert('user_detail',$res);

        if($result)
        {
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function getuserDetail($id)
    {
        $this->db->select('username,fname,lname,email,dob,organization,picture');
		$this->db->from('user_detail');
		$this->db->join('user','user_detail.user_id = user.user_id');
        $this->db->where('user_detail.user_id', $id);
        $res=$this->db->get();

        return $res->result();
    }
}
