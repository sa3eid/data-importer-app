<?php

namespace App\Console\Commands;

use App\Models\Transaction as ModelsTransaction;
use App\Models\User as ModelsUser;
use Illuminate\Console\Command;
use App\User;
use App\Transaction;
use Exception;
use Illuminate\Database\QueryException;

class ImportDataCommand extends Command
{
    protected $signature = 'data:import';

    protected $description = 'Import data from JSON files';

    public function handle()
    {
        $users_file = base_path('app/data/users.json');
        $usersJson = file_get_contents($users_file);
        $usersData = json_decode($usersJson, true)['users'];

        try {
            foreach ($usersData as $userData) {
                ModelsUser::create($userData);
            }

            $transactions_file = base_path('app/data/transactions.json');
            $transactionsJson = file_get_contents($transactions_file);
            $transactionsData = json_decode($transactionsJson, true)['transactions'];

            foreach ($transactionsData as $transactionData) {
                ModelsTransaction::create($transactionData);
            }
            $this->info('Data imported successfully!');
        } 
        // exceptions handling
        catch (QueryException $exception) {
            $this->error('Error importing data: ' . $exception->getMessage());
        } catch (Exception $exception) {
            $this->error('An unexpected error occurred: ' . $exception->getMessage());
        }
    }
}
