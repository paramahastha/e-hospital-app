<?php

namespace App\Console\Commands;

use App\Models\Consultation;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ConsultDoneProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consult:done';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update consult status to be done when the criteria is fulfilled.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $consult = Consultation::where('status', 'ongoing')->where('scheduler_flag', 0)->whereDate('session_end', '<=', Carbon::now('Asia/Jakarta'));        
        $consult->update(['status' => 'done', 'scheduler_flag' => 1]);
        
        $this->info('success execute consult session end to be done');
    }
}
