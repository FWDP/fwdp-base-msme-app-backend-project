<?php

namespace App\Console\Commands;

use App\Support\ModuleRegistry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ModuleRollback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:rollback {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback migrations for a specific module.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (ModuleRegistry::isCore(Str::slug($this->argument('module')))) {
            $this->error("You cannot rollback core modules.");
            return CommandAlias::FAILURE;
        }

        $this->info("Rolling back module...");
        $studly = Str::studly($this->argument('module'));
        Artisan::call('migrate:rollback', [
            '--path' => str_replace(base_path(), '',
                app_path("Modules/$studly/database/migrations"))],
            ['--force' => true]
        );

        $this->info("Migrations rollback completed.");

        return CommandAlias::SUCCESS;
    }
}
