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
            ->where('name', Str::lower($this->argument('module')))
            ->updateOrInsert(
                ['name' => $this->argument('module')],
                [
                    'enabled' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
            );

        $this->info("Module [{$this->argument('module')}] successfully enabled.");

        $this->registerProviderInBootstrap($this->argument('module'));

        $studly = Str::studly($this->argument('module'));


        if (is_dir(app_path("Modules\\{$studly}\database\migrations")))
        {
            Artisan::call('migrate', [
                '--path' => "app\\Modules\\{$studly}\\database\\migrations",
                '--force' => true,
            ]);

            $this->line(Artisan::output());

            $this->info("Migration for module [{$studly}] successfully executed.");
        } else {
            $this->info("No module-specific migration found. ");
        }

        return CommandAlias::SUCCESS;
    }

    public function registerProviderInBootstrap(string $slug): void
    {
        $studly = Str::studly($slug);

        $providerLine = "App\\Modules\\{$studly}\\Providers\\{$studly}ServiceProvider::class";

        $providerFile = base_path('bootstrap\providers.php');

        if (str_contains(file_get_contents($providerFile), $providerLine)) {
            $this->line("Provider already registered: {$providerLine}");
            return;
        }

        $normaliseContent = preg_replace(
            '/(App\\\\[^\n]+ServiceProvider::class)\s*];$/m',
            "$1,\n];",
            file_get_contents($providerFile)
        );

        file_put_contents(
            $providerFile,
            preg_replace(
                '/];\s*$/',
                "\t$providerLine\n];",
                $normaliseContent
            )
        );

        $this->info("Provider [{$studly}] successfully registered.");
    }
}
