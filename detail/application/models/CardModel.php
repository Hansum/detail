<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CardModel extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('CardModel');
    }


    public function cardInsertion($data)
    {

        $insert = $this->db->insert('business_card',$data);
        $x = FALSE;
        if($insert)
        {
            // $user = array(
            //     'user_id'=>$id,
            //     'businesscard_id' => $this->db->insert_id()
            // );
            // $insertPersonal = $this->db->insert('personal_card',$user);
           $x = TRUE;
        }
        return $x;
    }

    //FETCH BUSINESSCARD DETAILS QUERY
    public function getBusinessCardDetails($id)
    {
        $this->db->select('*');
        $this->db->from('business_card');
        $this->db->where('user_id',$id);
        $result = $this->db->get();

        if($result->num_rows()>0){
            return $result->result();
        }else{
            return FALSE;
        }
    }

    //DELETE CARD QUERY
    public function delCard($id)
    {
        $result = $this->db->delete('business_card',array('businessCard_id'=>$id));
        $x = FALSE;
        if($result)
        {
            $x = TRUE;
        }
        return $x;
    }

    //GET USERS RECEIVED CARDS QUERY
    public function fetchReceivedCards($id)
    {
        // $this->db->distinct();
        $this->db->select('business_card.businessCard_id,user.user_id,card_name,position,organization,address,cellphone,telephone,social_media,email,website');
        $this->db->from('business_card');
        $this->db->join('received_cards','business_card.businessCard_id = received_cards.businessCard_id','left');
        $this->db->join('user','received_cards.user_id = user.user_id','left');
        $this->db->where('user.user_id',$id);
        $this->db->where('business_card.user_id != received_cards.user_id');
        $result = $this->db->get();

        if($result->num_rows()>0){
            return $result->result();
        }else{
            return FALSE;
        }
    }

    public function updateCard($payload)
    {
        $this->db->where('businessCard_id',$payload['businessCard_id']);
        $result = $this->db->update('business_card',$payload);

        $x= FALSE;

        if($result)
        {
            $x = TRUE;
        }
        return $x;
    }


    // public function test(){
    //     $this->db->from('user as u');
    //     $this->db->from('userdetails as ud');
    //     $this->db->select('u.*,ud.*');
    //     $this->db->where('u.user_id=ud.user_id')
    // }
}