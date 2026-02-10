<?php

namespace App\Console\Commands;

use App\Support\ModuleRegistry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ModuleDisable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:disable {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diable a module.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (ModuleRegistry::isCore($this->argument('module'))) {
            $this->error("You cannot disable core module [{$this->argument('module')}]");
            $this->warn("Core modules are required for platform to function.");
            return Command::FAILURE;
        }

        DB::table('modules')
            ->where('name', $this->argument('module'))
            ->update(['enabled' => false]);

        $this->info("Module [{$this->argument('module')}] successfully disabled.");

        return Command::SUCCESS;
    }
}
