<?php

namespace Kauth\Token;

/**
* @author    @code4mk <hiremostafa@gmail.com>
* @author    @0devco <with@0dev.co>
* @since     2019
* @copyright 0dev.co (https://0dev.co)
*/

use \Firebase\JWT\JWT;
use Illuminate\Http\Request;
use DateTime;
use DateInterval;
use Config;
use Cookie;

class Token
{
  public function create($tokenID,$userType){
    $issueDate = new DateTime();
    $expiredDate = new DateTime();
    $tokenDuration = Config::get('kauth.token_exp') ? Config::get('kauth.token_exp') : 'P32D';
    $jwtIss = Config::get('kauth.payload.iss') ? Config::get('kauth.payload.iss') : 'https://code4mk.org';
    $jwtAud = Config::get('kauth.payload.aud') ? Config::get('kauth.payload.aud') : 'https://code4mk.org';
    $expiredDate->add(new DateInterval($tokenDuration));
    $key = "example_key";
    $token = array(
        "iss" => $jwtIss,
        "ut" => $userType,
        "iat" => $issueDate->getTimestamp(),
        "expM" => $expiredDate->getTimestamp(),
        "tid" => $tokenID,
    );
    $jwt = JWT::encode($token,$key);

    return $jwt;
  }

  public function tokon(){
    $token_header = Config::get('kauth.token_header_name') ? Config::get('kauth.token_header_name') : 'tokon';
    $tokon = \Request::header($token_header);

    if($tokon === null && (Config::get('kauth.cookie_auth')) ){
      try {
        //return $_COOKIE['kauth_token'];
        return $_COOKIE['authtoken'];
      } catch (\Exception $e) {
        return $e;
      }


    }
    return $tokon;
  }

  public function payload(){
    $key = "example_key";
    $tokon = $this->tokon();
    $jwt = JWT::decode($tokon, $key, array('HS256'));
    $payload =  (array) $jwt;
    return $payload;
  }

  public function payloader($tokon){
    $key = "example_key";
    $jwt = JWT::decode($tokon, $key, array('HS256'));
    $payload =  (array) $jwt;
    return $payload;
  }

  public function isExpired(){
    try {
      $instanceTime = new DateTime();
      if($instanceTime->getTimestamp() > $this->payload()["expM"] ){
        return true;
      }
      return false;
    } catch (\Exception $e) {
      return "jwt token is error";
    }
  }
}
