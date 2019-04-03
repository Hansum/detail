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
            'picture'=> 'data:image/png;base64,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'
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
    public function receiveCard()
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
            $res = $this->CardModel->sendPersonalCards($data);
            if($res)
            {
                $res = true;
                echo json_encode($res);
            }else{
                $res = false;
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

    public function cardDetails()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();
        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $id = $data->id;
        // $id = 19;

        if($token)
        {
            $get = $this->CardModel->fetchCardDetail($id);

            if($get)
            {
                echo json_encode($get);
            }else{
                $res = ['result'=>false,'status'=>'Authorized','message'=>'Card detail error'];
                echo json_encode($res);
            }
        }else{
            $result = ['result'=>false,'status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
    }
}
