<?php

return [
  /*
  |--------------------------------------------------------------------------
  | Kauth token key
  |--------------------------------------------------------------------------
  |
  | You can set your desired token key for request header
  | client side / axios headers name
  */

  "token_key" => "",

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
