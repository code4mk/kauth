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

  "token_key" => "authh",

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

  "payload" => [
    "iss" = "",
    "aud" = ""
  ]

  "guard" => [
    "users" => [
      "table" => "users",
    ],
    "user" => [
      "table" => "users",
    ]
  ],
];
