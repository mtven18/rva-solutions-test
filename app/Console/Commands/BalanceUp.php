<?php

namespace App\Console\Commands;

use App\Exceptions\ForbiddenException;
use App\Services\TransactionService;
use Illuminate\Console\Command;

class BalanceUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balance:up {username} {amount}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add amount to user balance';

    /**
     * @var TransactionService
     */
    private $service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TransactionService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws ForbiddenException
     */
    public function handle(): int
    {
        $this->service->send(
            $this->argument('username'),
            (float) $this->argument('amount')
        );

        $this->info(__('app.transaction_sent'));

        return 0;
    }
}
