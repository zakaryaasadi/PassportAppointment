<?php

namespace App\Console\Commands;

use App\Http\Core\Singleton;
use App\Http\Services\AppointmentService;
use Illuminate\Console\Command;

class UpdatePendingToDoneCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdatePendingToDone';
    private $appointmentService;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
        $this->appointmentService = Singleton::Create(AppointmentService::class);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->appointmentService->convertAllPendingAppointmentsToDone();
        return Command::SUCCESS;
    }
}
