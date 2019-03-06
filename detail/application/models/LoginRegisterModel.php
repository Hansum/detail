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

        return $res->result();
    }


    //REGISTRATION
    public function registerUser($res)
    {

        $result = $this->db->insert('user',$res);

        if($result)
        {
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function insertUserInfo($res)
    {
        $result2 = $this->db->insert('user_detail',$res);

        if($result2)
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