<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class ComplaintsLogsController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ComplaintsLogsModel');

        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
		header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
		header("Access-Control-Allow-Origin: *");
        header('Content-Type: application/json');
    }


    public function getComplaints()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();

        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $id = $data->userId;

        if($token)
        {
            $get = $this->ComplaintsLogsModel->fetchComplaints($id);
            if($get)
            {
                echo json_encode($get);
            }else{
                $result = ['result'=>false,'status'=>'Unauthorized','message'=>'Error in fetching complaints'];
                echo json_encode($result);
            }
        }else{
            $result = ['result'=>false,'status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
    }

    public function getLogs()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();

        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $id = $data->userId;

        if($token)
        {
            $fetch = $this->ComplaintsLogsModel->fetchLogs($id);
            if($fetch)
            {
                echo json_encode($fetch);
            }else{
                $result = ['result'=>false,'status'=>'Unauthorized','message'=>'Error in fetching Logs'];
                echo json_encode($result);
            }
        }else{

        }
    }

    public function insertComplaints()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();

        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));
    }
}