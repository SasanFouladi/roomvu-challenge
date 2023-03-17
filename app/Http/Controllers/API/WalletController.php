<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\AddMoneyRequest;
use App\Http\Requests\API\GetBalanceRequest;
use App\Services\Wallet\WalletContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class WalletController extends Controller
{
    /**
     * Get specific user balance.
     */
    public function getBalance(GetBalanceRequest $request, WalletContract $wallet): JsonResponse
    {
        return Response::success([
            'balance' => $wallet->getBalance($request->input('user_id')),
        ], 'User balance received');
    }

    /**
     * Deposit or withdraw from user wallet.
     */
    public function addMoney(AddMoneyRequest $request, WalletContract $wallet): JsonResponse
    {
        return Response::success([
            'reference_id' => $wallet->addMoney($request->input('user_id'), (float) $request->input('amount')),
        ], 'Change balance successfully');
    }
}
