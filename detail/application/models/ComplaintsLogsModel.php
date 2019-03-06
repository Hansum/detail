<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ComplaintsLogsModel extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function fetchComplaints()
    {

    }

    public function fetchLogs($id)
    {
        $this->db->select('*');
        $this->db->from('logs');
        $this->db->where('user_id',$id);
        $result = $this->db->get();

        if($result->num_rows()>0){
            return $result->result();
        }else{
            return FALSE;
        }
    }
}
