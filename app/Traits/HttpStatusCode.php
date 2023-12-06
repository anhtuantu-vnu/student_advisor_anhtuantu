<?php

namespace App\Traits;

class HttpStatusCode
{
    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;
    const NO_CONTENT = 204;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOW = 405;
    const ERROR = 500;
    const USER_ERROR = 'INVALID_DATA_ERROR';
    const ERROR_UPDATED = 'UPDATED_ERROR';
}
