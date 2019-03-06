<?php

class AUTHORIZATION
{
    public static function validateTimestamp($token)
    {
        $CI =& get_instance();
        $token = self::validateToken($token);
        if ($token != false && (now() - $token->timestamp < ($CI->config->item('token_timeout') * 60))) {
            return $token;
        }
        return false;
    }

    public static function validateToken($token)
    {
        $CI =& get_instance();
        return JWT::decode($token, $CI->config->item('jwt_key'));
    }

    public static function generateToken($data)
    {
        $CI =& get_instance();
        return JWT::encode($data, $CI->config->item('jwt_key'));
    }

    public static function isAuthorize(array $headers,$key)
    {   
        // $headers = $this->header;
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $token = explode(' ',$headers['Authorization']);

            $decodedToken = AUTHORIZATION::validateTimestamp($token[1]);
            if ($decodedToken != false){
                return true;
            }else{
               return false;
            }
        }
    }

}