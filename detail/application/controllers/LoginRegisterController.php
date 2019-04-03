<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class LoginRegisterController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('LoginRegisterModel');

        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
		header("Access-Control-Allow-Origin: *");

       
    }

    //USER LOGIN AND GENERATE THE TOKEN
    public function getUserLogin()
    {
       
        $data = json_decode(file_get_contents("php://input"));
        $uname = $data->username;
        $pass=$data->password;
        // $uname='admin';
        // $pass='admin';

        $res=$this->LoginRegisterModel->getUser($uname, $pass);
        if($res){
            $tokenPayload = array(
                'iss'=> $uname,
                'iat'=>now(),
                'admin'=>true,
                'timestamp'=>now()
            );

            $accessToken = AUTHORIZATION::generateToken($tokenPayload);
            foreach($res as $user){
                $json = array(
                    'auth_message'=>array(
                        'userId'=>$user->user_id,
                        '_message'=>'Success',
                        'Authorization'=>'Authorize',
                        '_isSuccessful'=>true,
                        'accessToken'=>$accessToken,
                    )
                );
            }
            echo json_encode($json);
        }else{
            $message = array(
                'auth_message'=>array(
                    '_message'=>'Fail',
                    'Authorization'=>'Incorrect username or password',
                    '_isSuccessful'=>false
                    )
                );
            echo json_encode($message);
        }
    }

    //REGISTER USERNAME AND PASSWORD
    public function registerUser()
    {

        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
        header("Access-Control-Allow-Origin: *");
        
        $data = json_decode(file_get_contents("php://input"));

        // $uname = $data->username;
        // $pass = $data->password;
        $user = array(
            'username'=>$data->username,
            'password'=>$data->password
        );

        // echo json_encode($data);

        $insert = $this->LoginRegisterModel->signUp($user);
        if($insert){
            // $message = ['result'=>TRUE];
            $message = TRUE;
            echo json_encode($message);
        }else{
            // $message = ['result'=>FALSE];
            $message = FALSE;
            echo json_encode($message);
        }
        // echo json_encode($insert);
    }


    //REGISTER USER DETAILS
    public function insertuserDetails()
    {
        $headers = $this->input->request_headers();

        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $data = json_decode(file_get_contents("php://input"));

        $res = array(
            'user_id'=>$data->userId,
            'fname'=>$data->fname,
            'lname'=>$data->lname,
            'email'=>$data->email,
            'organization'=> $data->organization,
            'dob'=> $data->dob
        );

        if($token)
        {
            $insert = $this->LoginRegisterModel->insertUserInfo($res);

            if($insert){
                $message = ['result'=>true,'status'=>'Authorized','message'=>'User Information successfuly inserted'];
                echo json_encode($message);
            }else{
                $message2 = ['result'=>false,'status'=>'Unauthorized','message'=>'Error inserting user details'];
                echo json_encode($message2);
            }
        }else{
            $result = ['status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
    }


    //FETCH/GET USER DETAILS
    public function fetchUserDetails()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();
        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $data = json_decode(file_get_contents("php://input"));

        $id = $data->userId;

        if($token)
        {
            $fetch = $this->LoginRegisterModel->getuserDetail($id);

            if($fetch)
            {
                foreach($fetch as $fetchDetail){
                    $result = [
						// 'result'=>true,
						'username'=>$fetchDetail->username,
                        'fname' => $fetchDetail->fname,
                        'lname' => $fetchDetail->lname,
                        'email' => $fetchDetail->email,
                        'birthdate' => $fetchDetail->dob,
                        'organization'=> $fetchDetail->organization,
                        'picture' => $fetchDetail->picture
                    ];
                }
                echo json_encode($result);
            }else{
                // $message = ['result'=>false,'status'=>'Unauthorized','message'=>'User detail not found'];
                // echo json_encode($message);
                $message = ['result'=>false,'message'=>'User detail not found'];
                echo json_encode($message);
            }
        }else{
            $result = ['result'=>false,'status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
    }

}
