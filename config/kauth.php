<?php

return [
  /*
  |--------------------------------------------------------------------------
  | Kauth token key name
  |--------------------------------------------------------------------------
  |
  | You can set your desired token key name for request header
  | client side / axios headers name as (Authorization)
  | default is tokon
  */

  "token_header_name" => "",

  /*
  |--------------------------------------------------------------------------
  | Kauth token expired duration
  |--------------------------------------------------------------------------
  |
  | You can set duration of your  token
  | pattern will be follow P7Y5M4DT4H3M2S
  | http://php.net/manual/en/datetime.gettimestamp.php
  */

  "token_exp" => "",

  /*
  |--------------------------------------------------------------------------
  | Kauth jwt payload iss and aud
  |--------------------------------------------------------------------------
  |
  | You can set jwt iss
  |
  | your url host name
  */
  "payload" => [
    "iss" => ""
  ],

  /*
  |--------------------------------------------------------------------------
  | Kauth cookie auth
  |--------------------------------------------------------------------------
  |
  | You can use for socialite system
  |
  |
  */

  "cookie_auth" => false,

  /*
  |--------------------------------------------------------------------------
  | Kauth guard setup
  |--------------------------------------------------------------------------
  |
  | You can set guard
  | set table name
  |
  */

  "guard" => [
    "users" => [
      "table" => "users",
    ],
  ],
];
