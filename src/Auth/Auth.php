<?php

namespace Kauth\Auth;

use Illuminate\Routing\Controller as BaseController;
use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Device;
use Sinergi\BrowserDetector\Os;
use Illuminate\Http\Request;
use Kauth\Model\KauthModel;
use Kauth\Token\Token;
use DateTime;
use Config;
use Hash;
use DB;

/**
 * @author    @code4mk <hiremostafa@gmail.com>
 * @author    @0devco <with@0dev.co>
 * @since     2019
 * @copyright 0dev.co (https://0dev.co)
 */

class Auth
{

  protected $guard;

  protected $socialite;

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
    $browser = new Browser;
    $os = new Os();
    $device = new Device();
    $credential = [];
    $usernames = $credentials['usernames'];
    $username = $credentials['username'];

    // store credentials without password & username

    foreach ($credentials as $key => $value){
      if($key === 'password' || !empty($key === 'usernames') || !empty($key === 'username')){
        // nothing
      } else {
        $credential[$key] = $value;
      }
    }

    // fetch user by credentials

    $user = DB::table(Config::get('kauth.guard.' . $this->guard. '.table'))
            // multiple username accept (id||username||email >> etc)
            ->where(function ($query) use ($usernames,$username){
              foreach ($usernames as $key => $value) {
                $query->orWhere($value,$username)
                      ->orWhere($value,$username);
              }
            })
            ->where(function ($query) use ($credential) {
              foreach ($credential as $key => $value) {
                $query->where($key,$value);
              }
            })
            ->first();

    // check has user and socialite

    if(!empty($user) && !($this->socialite)){
      $userPassword = Hash::check($credentials['password'],$user->password);
    } elseif (!empty($user) && $this->socialite) {
      $userPassword = true;
    } else {
      $userPassword = false;
    }

    // check user and password then store jwt token

    if (!empty($user) && $userPassword ) {
      $jwt = new KauthModel;
      $jwt->user_id = $user->id;
      $jwt->browser = \Request::get('browser');
      //$jwt->os = \Request::get('os');
      $jwt->device = \Request::get('device');
      $jwt->active = true;
      $jwt->save();

      $secret = new Token();
      $tokon = $secret->create($jwt->id);
      $payloader = $secret->payloader($tokon);
      $jwt->tokon = $tokon;
      $jwt->iat = $payloader['iat'];
      $jwt->exp = $payloader['expM'];
      $jwt->save();
      return $jwt;
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
    $token = new Token();
    $user = KauthModel::where('tokon',$token->tokon())->first();
    $instanceTime = new DateTime();
    if(!empty($user) && ($instanceTime->getTimestamp() <= $user->exp)){
      return true;
    }
    return false;
  }

  /**
   *
   * auth user id
   *
   *@return integer id
   */
  public function id()
  {
    $token = new Token();
    $user = KauthModel::where('tokon',$token->tokon())->first();
    $instanceTime = new DateTime();
    if(!empty($user) && ($instanceTime->getTimestamp() <= $user->exp)){
      return $user->user_id;
    }
    return 0;
  }

  /**
   *
   * auth logout
   *
   * delete auth credentials
   */
  public function logout()
  {
    $token = new Token();
    $user = KauthModel::where('tokon',$token->tokon())->first();
    $user->delete();
    return "done";
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
    $token = new Token();
    $user = KauthModel::where('tokon',$token->tokon())->first();

    // fetch all token without current token

    KauthModel::where('user_id',$user->user_id)
                      -> where(function ($query) use ($user){
                        $query->whereNotIn('id',[$user->id]);
                      })
                      ->delete();
  }
}
