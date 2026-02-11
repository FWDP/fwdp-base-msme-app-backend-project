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

        if (is_dir(app_path("Modules\\{$studly}\database\migrations")))
        {
            Artisan::call('migrate:rollback', [
                '--path' => "app\\Modules\\$studly\\database\\migrations",
                '--force' => true
            ]);

            $this->line(Artisan::output());

            $this->info("Migration for module [{$studly}] successfully executed.");
        } else {
            $this->info("No module-specific migration found. ");
        }

        $this->info("Migrations rollback completed.");

        return CommandAlias::SUCCESS;
    }

}
