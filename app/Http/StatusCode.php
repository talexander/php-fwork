<?php
/**
 * User: tarakanov
 */

namespace App\Http;


class StatusCode {
    const CODE_CUSTOM = 0;
    const CODE_100 = 100;
    const CODE_101 = 101;
    const CODE_102 = 102;
    const CODE_200 = 200;
    const CODE_201 = 201;
    const CODE_202 = 202;
    const CODE_203 = 203;
    const CODE_204 = 204;
    const CODE_205 = 205;
    const CODE_206 = 206;
    const CODE_207 = 207;
    const CODE_208 = 208;
    const CODE_300 = 300;
    const CODE_301 = 301;
    const CODE_302 = 302;
    const CODE_303 = 303;
    const CODE_304 = 304;
    const CODE_305 = 305;
    const CODE_306 = 306;
    const CODE_307 = 307;
    const CODE_400 = 400;
    const CODE_401 = 401;
    const CODE_402 = 402;
    const CODE_403 = 403;
    const CODE_404 = 404;
    const CODE_405 = 405;
    const CODE_406 = 406;
    const CODE_407 = 407;
    const CODE_408 = 408;
    const CODE_409 = 409;
    const CODE_410 = 410;
    const CODE_411 = 411;
    const CODE_412 = 412;
    const CODE_413 = 413;
    const CODE_414 = 414;
    const CODE_415 = 415;
    const CODE_416 = 416;
    const CODE_417 = 417;
    const CODE_418 = 418;
    const CODE_422 = 422;
    const CODE_423 = 423;
    const CODE_424 = 424;
    const CODE_425 = 425;
    const CODE_426 = 426;
    const CODE_428 = 428;
    const CODE_429 = 429;
    const CODE_431 = 431;
    const CODE_500 = 500;
    const CODE_501 = 501;
    const CODE_502 = 502;
    const CODE_503 = 503;
    const CODE_504 = 504;
    const CODE_505 = 505;
    const CODE_506 = 506;
    const CODE_507 = 507;
    const CODE_508 = 508;
    const CODE_511 = 511;


    protected static $codeDescr = array(
        // INFORMATIONAL CODES
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        // SUCCESS CODES
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        // REDIRECTION CODES
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy', // Deprecated
        307 => 'Temporary Redirect',
        // CLIENT ERROR
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        // SERVER ERROR
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required',
    );
}