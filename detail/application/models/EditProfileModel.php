<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class EditProfileModel extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function updateProfile($res)
    {
        $this->db->where('user_id',$res['user_id']);
        $result = $this->db->update('user_detail',$res);
        if($result)
        {
            return TRUE;
        }else{
            return FALSE;
        }
    }


    public function updatePassword($res,$old)
    {

        $this->db->where('user_id',$res['user_id']);
        $this->db->where('password',$old);

        $result = $this->db->update('user',$res);

        if($result)
        {
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function uploadpic($res)
    {
        $this->db->where('user_id',$res['user_id']);
        $result = $this->db->update('user_detail',$res);

        if($result)
        {
            return TRUE;
        }else{
            return FALSE;
        }
    }
}
