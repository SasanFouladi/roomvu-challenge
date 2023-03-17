<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class InsufficientFunds extends Exception
{
    public function render()
    {
        return Response::badRequest('Insufficient funds');
    }
}
