<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class EditProfileController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('EditProfileModel');

        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
		header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
		header("Access-Control-Allow-Origin: *");
        header('Content-Type: application/json');
    }

    public function editProfile()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();

        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $res = array(
            'user_id'=>$data->userId,
            'fname' => $data->fname,
            'lname' => $data->lname,
            'email'=> $data->email,
            'dob' => $data->dob,
            'organization' => $data->organization
        );

        if($token)
        {
            $update = $this->EditProfileModel->updateProfile($res);
            if($update)
            {
                $message = ['status'=>'Authorized','result'=>true,'message'=>'Updating profile successfully'];
                echo json_encode($message);
            }else{
                $message = ['status'=>'Unauthorized','result'=>false,'message'=>'Error updating profile'];
                echo json_encode($message);
            }
        }else{
            $result = ['status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
    }

    public function changePassword()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();

        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $res = array(
            'user_id'=>$data->userId,
            'password'=>$data->newPassword
        );
        $old = $data->oldPassword;

        if($token)
        {
            $update = $this->EditProfileModel->updatePassword($res,$old);
            
            if($update)
            {
                $message = ['result'=>true,'status'=>'Authorized','message'=>'Password update successfully'];
                echo json_encode($message);
            }else{
                $message = ['result'=>false,'status'=>'Unauthorized','message'=>'Error updating password'];
                echo json_encode($message);
            }
        }else{
            $result = ['status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
    }


    public function uploadPicture()
    {
        $id = json_decode(file_get_contents("php://input"));
        // $decode = base64_decode($id->data);
        // $data = explode(',',$id->data);
        $headers = $this->input->request_headers();
        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $res = array(
            'user_id' => $id->userId,
            // 'picture' => $data[1]
            'picture' => $id->data
        );

        if($token)
        {
            $upload = $this->EditProfileModel->uploadpic($res);
            if($upload)
            {
                $message = ['result'=>true,'status'=>'Authorized','message'=>'Upload picture success'];
                echo json_encode($message);
            }else{
                $message = ['result'=>false,'status'=>'Authorized','message'=>'Error in uploading picture'];
                echo json_encode($message);
            }
        }else{
            $result = ['status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
        // echo json_encode($res);
    }
}