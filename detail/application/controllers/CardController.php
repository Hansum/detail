<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class CardController extends CI_Controller {

    // private $headers, $token;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('CardModel');

        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
		header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
		header("Access-Control-Allow-Origin: *");
        header('Content-Type: application/json');
        header('Access-Control-Allow-Credentials: true');
    }




    //INSERTING OF PERSONAL CARDS TO THE DATABASE
    public function insertCard()
    {

        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();
        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $res = array(
            'card_name'=>$data->card_name,
            'position'=>$data->position,
            'organization'=>$data->organization,
            'address'=>$data->address,
            'telephone'=>$data->telephone,
            'social_media'=>$data->social_media,
            'email'=>$data->email,
            'website'=>$data->website,
            'cellphone'=>$data->cellphone,
            'user_id'=>$data->userId,
        );
        // $res = array(
        //     'user_id'=>3,
        //     'card_name'=> 'NUMBER 3 CARD',
        //     'position'=> 'OJT',
        //     'organization'=> 'forward',
        //     'address'=>'mandaue city',
        //     'telephone'=>432443,
        //     'social_media'=>'fwdbpo',
        //     'email'=>'takingyouforward@gmail.org',
        //     'website'=>'fwdbpo',
        //     'cellphone'=>254333,
        // );
        

        if($token){
            
            $insert = $this->CardModel->cardInsertion($res);

            if($insert)
            {
                $result = ['result'=>true,'status'=>'Authorized','message'=>'card successfully inserted'];
                 echo json_encode($result);
            }else{
                $result = ['result'=>false,'status'=>'Authorized','message'=>'card was not inserted'];
                echo json_encode($result);
            }
        }else{
            $result = ['status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
    }

    //GET PERSONAL CARDS FUNCTION
    public function getBusinessCard()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();
        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $id = $data->userId;
        // $id = 20;

        if($token)
        {
            $fetch = $this->CardModel->getBusinessCardDetails($id);
            if($fetch){
                // foreach($fetch as $fetchdetails)
                // {
                //     $result = [
                //         'card_name' => $fetchdetails->card_name,
                //         'position'=> $fetchdetails->position,
                //         'organization'=> $fetchdetails->organization,
                //         'address'=> $fetchdetails->address,
                //         'cellphone'=> $fetchdetails->cellphone,
                //         'telephone'=> $fetchdetails->telephone,
                //         'social_media'=> $fetchdetails->social_media,
                //         'email'=> $fetchdetails->email,
                //         'website'=> $fetchdetails->website
                //     ];

                // }
                $json=array(
                    'result'=>true,
                    'status'=>'Authorized',
                    'message'=>'No personal cards in the database',
                    'cardInformation'=>$fetch
                );
                echo json_encode($json);
            }else{
                $result = ['result'=>false,'status'=>'Authorized','message'=>'No personal cards in the database'];
                echo json_encode($result);
            }
        }else{
            $result = ['result'=>false,'status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
    }

    // DELETE CARDS FUNCTION
    public function deleteCard()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();
        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));
       
        // $id = $this->uri->segment('3');
        $cardId = $data->id;

        // echo json_encode($token);
        //$id = $cardId;
        // echo json_encode($cardId);
        // $cardId = 5;
        if($token)
        {
            $delete = $this->CardModel->delCard($cardId);
            if($delete)
            {
                $message = ['result'=>true,'status'=>'Authorized','message'=>'Delete card successful'];
                echo json_encode($message);
            }else{
                $result = ['result'=>false,'status'=>'Authorized','message'=>'Error in deleting cards'];
                echo json_encode($result);
            }
        }else{
            $result = ['result'=>false,'status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
    }

    //DELETING USER RECEIVED CARDS
    public function deleteReceivedCards()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();
        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $userId = $data->userId;
        $businessCardId = $data->businessCard_id;

        if($token)
        {
            $res = $this->CardModel->delReceivedCards($userId,$businessCardId);
            if($res)
            {
                $message = ['result'=>true,'status'=>'Authorized','message'=>'Delete received card successful'];
                echo json_encode($message);
            }else{
                $message = ['result'=>false,'status'=>'Authorized','message'=>'Error in Deleting received card'];
                echo json_encode($message);
            }
        }else{
            $result = ['result'=>false,'status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
    }

    //UPDATING OR EDITING PERSONAL CARDS
    public function editBusinessCard()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();
        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $payload = array(
            'businessCard_id' => $data->id,
            'card_name'=> $data->card_name,
            'address' => $data->address,
            'cellphone' => $data->cellphone,
            'email' => $data->email,
            'organization'=> $data->organization,
            'telephone' => $data->telephone,
            'website'=> $data->website,
            'position'=> $data->position
        );

        // $payload = array(
        //     'businessCard_id' => 23,
        //     'address' => 'Mandaue City,Cebu',
        //     'cellphone' => 942345,
        //     'email' => 'forward@gmail.com',
        //     'organization'=> 'forwardbpo',
        //     'telephone' => 4221345,
        //     'website'=> 'fwdbpo.io',
        //     'position'=> 'CEO',
        //     'card_name'=>'sample'
        // );

        if($token)
        {
            $res = $this->CardModel->updateCard($payload);
            if($res)
            {
                $message = ['result'=>true,'status'=>'Authorized','message'=>'Update card successful'];
                echo json_encode($message);
            }else{
                $message = ['result'=>false,'status'=>'Authorized','message'=>'Update card error'];
                echo json_encode($message);
            }
        }else{
            $result = ['result'=>false,'status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
    }

    //GET USER RECEIVED CARDS
    public function getReceivedCards()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();
        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $id = $data->userId;
        //  $id = 3;
        if($token)
        {
            $result = $this->CardModel->fetchReceivedCards($id);
            if($result)
            {
                $json=array(
                    'result'=>true,
                    'status'=>'Authorized',
                    'message'=>'Personal card/s found',
                    'cardInformation'=>$result
                );
                echo json_encode($json);
            }else{
                $res = ['result'=>false,'status'=>'Authorized','message'=>'No cards in the database'];
                echo json_encode($res);
            }
        }else{
            $result = ['result'=>false,'status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }   
    }


    //SENDING OF PERSONAL CARDS TO OTHER USER
    public function saveReceivedCards()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();
        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $data = array(
            'businessCard_id'=>$data->cardId,
            'user_id'=> $data->userId
        );
        if($token)
        {
            $res = $this->CardModel->receivedPersonalCards($data);
            if($res)
            {
                $res = ['result'=>true,'status'=>'Authorized','message'=>'Card was send successfully'];
                echo json_encode($res);
            }else{
                $res = ['result'=>false,'status'=>'Authorized','message'=>'Error in sending card'];
                echo json_encode($res);
            }
        }else{
            $result = ['result'=>false,'status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
    }


    // public function getLogs()
    // {
    //     $data = json_decode(file_get_contents("php://input"));
    //     $headers = $this->input->request_headers();
    //     $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        
    // }

    public function sendLogs()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();
        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $users = array(
            'receiver_id'=>$data->receiver_id,
            'sender_id' =>$data->sender_id
        );

        if($token)
        {
            $res = $this->CardModel->logs($users);
            if($res)
            {
                $res = ['result'=>true,'status'=>'Authorized','message'=>'Log was send successfuly'];
                echo json_encode($res);
            }else{
                $res = ['result'=>false,'status'=>'Authorized','message'=>'Error sending logs'];
                echo json_encode($res);
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
    }

    public function uploadcardImage()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();
        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $res = array(
            'businessCard_id' => $data->businessCard_id,
            'picture'=> $data->data
        );

        if($token)
        {
            $upload = $this->CardModel->uploadimage($res);
            if($upload)
            {
                $res = ['result'=>true,'status'=>'Authorized','message'=>'Uploading image success'];
                echo json_encode($res);
            }else{
                $res = ['result'=>true,'status'=>'Authorized','message'=>'Uploading image error'];
                echo json_encode($res);
            }
        }else{
            $result = ['result'=>false,'status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
    }
}