<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class CardController extends CI_Controller {

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
            'user_id'=>$data->userId
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
                $result = ['result'=>false,'status'=>'Unauthorized','message'=>'card was not inserted'];
                echo json_encode($result);
            }
        }else{
            $result = ['status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
    }


    public function getBusinessCard()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();
        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $id = $data->userId;
        //$id = 3;

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
                echo json_encode($fetch);
            }else{
                $result = ['result'=>false,'status'=>'Unauthorized','message'=>'Error fetching cards'];
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

        $id = $data->businessCard_id;
        // $id = 28;
        if($token)
        {
            $delete = $this->CardModel->delCard($id);
            if($delete)
            {
                $message = ['result'=>true,'status'=>'Authorized','message'=>'Delete card successful'];
                echo json_encode($message);
            }else{
                $result = ['result'=>false,'status'=>'Unauthorized','message'=>'Error in deleting cards'];
                echo json_encode($result);
            }
        }else{
            $result = ['result'=>false,'status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
    }

    public function deleteReceivedCards()
    {

    }


    public function editCards()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();
        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $payload = array(
            'businessCard_id' => $data->id,
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
                $message = ['result'=>false,'status'=>'Unauthorized','message'=>'Update card error'];
                echo json_encode($message);
            }
        }else{
            $result = ['result'=>false,'status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
    }


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
                echo json_encode($result);
            }else{
                $res = ['result'=>false,'status'=>'Unauthorized','message'=>'Error fetching cards'];
                echo json_encode($res);
            }
        }else{
            $result = ['result'=>false,'status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }   
    }

    public function sendCards()
    {
        $data = json_decode(file_get_contents("php://input"));
        $headers = $this->input->request_headers();
        $token=AUTHORIZATION::isAuthorize($headers,$this->config->item('jwt_key'));

        $cardId = $data->business_id;
        $userId = $data->userId;
        if($token)
        {
            
        }else{
            $result = ['result'=>false,'status'=>'Unauthorized','message'=>'token expired'];
            echo json_encode($result);
        }
    }
}