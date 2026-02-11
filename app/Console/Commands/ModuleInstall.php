<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
            ->updateOrInsert(
                ['name' => $this->argument('module')],
                [
                    'enabled' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
            );

        $this->info("Module [{$this->argument('module')}] successfully enabled.");

        $studly = Str::studly($this->argument('module'));

        if (is_dir(app_path("Modules/{$studly}/database/migrations")))
        {
            Artisan::call('migrate', [
               '--path' => app_path("Modules/{$studly}/database/migrations"),
               '--force' => true,
            ]);

            $this->info("Migration for module [{$studly}] successfully executed.");
        } else {
            Artisan::call('migrate', ['--force' => true]);
            $this->info("No module-specific migration found. ");
        }

        return CommandAlias::SUCCESS;
    }
}
