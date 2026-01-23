<?php

use Illuminate\Http\Request;

return [

    /*
    |--------------------------------------------------------------------------
    | Trusted Proxies
    |--------------------------------------------------------------------------
    |
    | Set trusted proxy IP addresses.
    |
    | Both IPv4 and IPv6 addresses are supported, along with CIDR notation.
    |
    | The "*" character is syntactic sugar within TrustedProxy to trust any
    | proxy that connects directly to your server, a requirement when you
    | cannot know the address of your proxy (e.g. if using ELB or similar).
    |
    */

    'proxies' => null, // [<ip addresses>,], '*', '<ip addresses>,'

    /*
    |--------------------------------------------------------------------------
    | Trusted Headers
    |--------------------------------------------------------------------------
    |
    | Which headers to use to detect proxy related data (For, Host, Proto, Port)
    |
    | Options include:
    |
    | - Request::HEADER_X_FORWARDED_FOR
    | - Request::HEADER_X_FORWARDED_HOST
    | - Request::HEADER_X_FORWARDED_PORT
    | - Request::HEADER_X_FORWARDED_PROTO
    | - Request::HEADER_X_FORWARDED_AWS_ELB
    |
    | Note: HEADER_X_FORWARDED_ALL is deprecated in Laravel 9+
    |
    | @link https://symfony.com/doc/current/deployment/proxies.html
    |
    */

    'headers' => Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO | Request::HEADER_X_FORWARDED_AWS_ELB,

];
