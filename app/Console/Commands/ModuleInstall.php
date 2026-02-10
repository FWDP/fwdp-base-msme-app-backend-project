<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ModuleInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:install {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable a module.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('modules')
            ->where('name', $this->argument('module'))
            ->update(['enabled' => true]);

        $this->info("Module [{$this->argument('module')}] successfully enabled.");

        Artisan::call('migrate');

        $this->info("Migrations executed.");

        return CommandAlias::SUCCESS;
    }
}
