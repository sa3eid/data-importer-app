<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getUsersWithTransactions($statusCode = null, $currency = null, $minAmount = null, $maxAmount = null, $minDate = null, $maxDate = null)
    {
        $query = User::with('transactions');

        if ($statusCode) {
            $query->whereHas('transactions', function ($subQuery) use ($statusCode) {
                $subQuery->where('statusCode', $statusCode);
            });
        }

        if ($currency) {
            $query->whereHas('transactions', function ($subQuery) use ($currency) {
                $subQuery->where('Currency', $currency);
            });
        }

        if ($minAmount && $maxAmount) {
            $query->whereHas('transactions', function ($subQuery) use ($minAmount, $maxAmount) {
                $subQuery->whereBetween('paidAmount', [$minAmount, $maxAmount]);
            });
        }

        if ($minDate && $maxDate) {
            $query->whereHas('transactions', function ($subQuery) use ($minDate, $maxDate) {
                $subQuery->whereBetween('paymentDate', [$minDate, $maxDate]);
            });
        }

        return $query->get();
    }
}
