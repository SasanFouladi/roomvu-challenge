<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\GetBalanceRequest;
use App\Services\Wallet\WalletContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class WalletController extends Controller
{
    public function getBalance(GetBalanceRequest $request, WalletContract $wallet): JsonResponse
    {
        return Response::success([
            'balance' => $wallet->getBalance($request->input('user_id')),
        ], 'User balance received');
    }
}
