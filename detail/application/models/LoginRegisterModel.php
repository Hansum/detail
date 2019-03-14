<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class LoginRegisterModel extends CI_Model {

    public function __construct(){
        parent::__construct();
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
    public function registerUsers($user)
    {
        $result1 = $this->db->insert('user', $user);
        $insert_id = $this->db->insert_id();
        // $result2 = $this->db->insert('user_detail',$dummy); 

        $dummy = array(
            'user_id' => $insert_id,
            'fname' => 'edit firstname here',
            'lname' => 'edit lastname here',
            'email' => 'edit email here',
            'dob' => 'edit dob here',
            'organization' => 'edit organization here'
        );

        $result2 = $this->db->insert('user_detail',$dummy);

        if($result && $result2)
        {
            return TRUE;
        }else{
            return FALSE;
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
        $this->db->select('*');
        $this->db->from('user_detail');
        $this->db->where('user_id', $id);
        $res=$this->db->get();

        return $res->result();
    }
}