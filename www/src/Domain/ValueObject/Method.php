<?php

namespace Dm1tru\Barcoder\Domain\ValueObject;

enum Method
{
    case GET;
    case POST;
    case PUT;
    case DELETE;
    case PATCH;
    case OPTIONS;
    case HEAD;
    case TRACE;
    case CONNECT;
    case CLI;
    case UNKNOWN;
}