<?php

namespace App\Jobs;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CalculateDailyTotalAmountOfTransactions
{
    use Dispatchable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $total = Transaction::query()
            ->where('created_at', '>=', Carbon::now()->startOfDay())
            ->where('created_at', '<=', Carbon::now()->endOfDay())
            ->sum('amount');

        echo "Total amount of transactions: $total";
    }
}
