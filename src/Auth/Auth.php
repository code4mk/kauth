<?php

namespace Kauth\Auth;

/**
 * @author    @code4mk <hiremostafa@gmail.com>
 * @author    @0devco <with@0dev.co>
 * @since     2019
 * @copyright 0dev.co (https://0dev.co)
 */

use Illuminate\Http\Request;
use Kauth\Model\KauthModel;
use Kauth\Token\Token;
use DateTime;
use Schema;
use Config;
use Hash;
use DB;

class Auth
{

  protected $guard;

  protected $socialite;

  protected $user_type = '';

  public function __construct()
  {
    $this->guard();
    $this->socialite(false);
  }


 /**
  *
  * set gaurd name
  *
  *@return string guard
  */
  public function guard($guard ='users')
  {
    $this->guard = $guard;
    return $this;
  }

 /**
  *
  * socialite system
  *
  *@return boolean
  */
  public function socialite($status = true)
  {
    $this->socialite = $status;
    return $this;
  }


  /**
   *
   * attempt credentials
   *
   *@return string jwt
   */
  public function attempt($credentials)
  {
    $credential = [];
    $getTokennames = $credentials['usernames'] ?? [];
    $getTokenname = $credentials['username'] ?? '';

    // store credentials without password & username

    foreach ($credentials as $key => $value){
      if($key === 'password' || !empty($key === 'usernames') || !empty($key === 'username')){
        // nothing
      } else {
        $credential[$key] = $value;
      }
    }

    // fetch user by credentials

    $getToken = DB::table(Config::get('kauth.guard.' . $this->guard . '.table'))
            // multiple username accept (id||username||email >> etc)
            ->where(function ($query) use ($getTokennames,$getTokenname){
              foreach ($getTokennames as $key => $value) {
                $query->orWhere($value,$getTokenname)
                      ->orWhere($value,$getTokenname);
              }
            })
            ->where(function ($query) use ($credential) {
              foreach ($credential as $key => $value) {
                $query->where($key,$value);
              }
            })
            ->first();

    // check has user and socialite

    if(!empty($getToken) && !($this->socialite)){
      $getTokenPassword = Hash::check($credentials['password'],$getToken->password);
    } elseif (!empty($getToken) && $this->socialite) {
      $getTokenPassword = true;
    } else {
      $getTokenPassword = false;
    }

    // check user and password then store jwt token

    if (!empty($getToken) && $getTokenPassword ) {
      $jwt = new KauthModel;
      $jwt->user_id = $getToken->id;
      $jwt->browser = \Request::get('browser');
      $jwt->os = \Request::get('os');
      $jwt->device = \Request::get('device');
      $jwt->ip = \Request::get('ip');
      $jwt->active = true;
      $jwt->guard = $this->guard;
      $jwt->save();

      $secret = new Token();
      if (Schema::hasColumn(Config::get('kauth.guard.' . $this->guard . '.table'), 'user_type')) {
        $this->user_type = $getToken->user_type;
      }
      $tokon = $secret->create($jwt->id,$this->user_type);
      $payloader = $secret->payloader($tokon);
      $jwt->tokon = $tokon;
      $jwt->iat = $payloader['iat'];
      $jwt->exp = $payloader['expM'];

      if (Schema::hasColumn($this->guard, 'user_type')) {
        $jwt->user_type = $getToken->user_type;
      }
      $jwt->save();
      return $jwt->tokon;
    }
    return "wrong credentials";
  }

  /**
   *
   * auth check
   *
   *@return boolean
   */
  public function check()
  {
    try {
      $token = new Token();
      $getToken = KauthModel::where('tokon',$token->tokon())->first();
      $instanceTime = new DateTime();
      if(!empty($getToken) && ($instanceTime->getTimestamp() <= $getToken->exp)){
        return true;
      }
      return false;
    } catch (\Exception $e) {
      return "jwt-error";
    }

  }

  /**
   *
   * auth user id
   *
   *@return integer id
   */
  public function id()
  {
    try {
      $token = new Token();
      $getToken = KauthModel::where('tokon',$token->tokon())->first();
      $instanceTime = new DateTime();
      if(!empty($getToken) && ($instanceTime->getTimestamp() <= $getToken->exp)){
        return $getToken->user_id;
      }
      return 0;
    } catch (\Exception $e) {
      return "jwt-error";
    }

  }

  public function auths()
  {
    $auths = KauthModel::where('user_id',$this->id())
                      ->orderBy('id','desc')
                      ->get();
    return $auths;
  }

  /**
   *
   * auth logout
   *
   * delete auth credentials
   */
  public function logout()
  {
    try {
      $token = new Token();
      $getToken = KauthModel::where('tokon',$token->tokon())->first();
      $getToken->delete();
    } catch (\Exception $e) {
      return "jwt-error";
    }


  }

  /**
   *
   * logout all devices
   *
   * delete auth user's all token
   * without current token id
   */
  public function logoutOtherDevices()
  {
    try {
      $token = new Token();
      $getToken = KauthModel::where('tokon',$token->tokon())->first();

      // fetch all token without current token

      KauthModel::where('user_id',$getToken->user_id)
                        -> where(function ($query) use ($getToken){
                            $query->whereNotIn('id',[$getToken->id]);
                          })
                        ->delete();
    } catch (\Exception $e) {
      return "jwt-error";
    }

  }

  /**
   *
   * refresh token
   *
   * edit existing token
   *
   */
  public function refreshToken()
  {
    try {
      $token = new Token();
      $getToken = KauthModel::where('tokon',$token->tokon())->first();
      $getToken->tokon = $token->create($getToken->id);
      $payloader = $token->payloader($token->create($getToken->id));
      $getToken->iat = $payloader['iat'];
      $getToken->exp = $payloader['expM'];
      $getToken->save();
      return $getToken->tokon;
    } catch (\Exception $e) {
      return "jwt-error";
    }
  }
}
