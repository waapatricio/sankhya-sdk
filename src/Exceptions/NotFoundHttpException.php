<?php

namespace Sankhya\Exceptions;

use Exception;

class NotFoundHttpException extends Exception
{
    protected $message = 'The provided API endpoint could not be found. Please try again.';
}
