<?php

namespace App\Console\Commands;

use App\Support\ModuleRegistry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command as CommandAlias;

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
            return CommandAlias::FAILURE;
        }

        DB::table('modules')
            ->where('name', $this->argument('module'))
            ->update(['enabled' => false]);

        $this->info("Module [{$this->argument('module')}] successfully disabled.");

        Artisan::call('module:rollback', ['module' => $this->argument('module')]);

        $this->removeProviderFromBootstrap($this->argument('module'));

        return CommandAlias::SUCCESS;
    }
    public function removeProviderFromBootstrap(string $slug): void
    {
        $studly = Str::studly($slug);

        $providerLine = match (true) {
            is_dir(app_path("Modules/{$studly}/Providers"))
            =>"App\\Modules\\{$studly}\\Providers\\{$studly}ServiceProvider::class",
            default => null
        };

        if (!$providerLine){
            $this->warn("No matching ServiceProvider found for [{$studly}] - skipping.");
        }

        if (!Str::contains(file_get_contents(base_path("bootstrap/providers.php")), $providerLine)) {
            $this->info("Provider not present - nothing to remove.");
        } else {
            $escapeProvider = preg_quote($providerLine, "/");

            $contents =  preg_replace("/\s*{$escapeProvider}\s*,?\s*\n/","",
                file_get_contents(base_path("bootstrap/providers.php"))
            );

            file_put_contents(base_path(
                "bootstrap/providers.php"),
                preg_replace(
                    "/(App\\\\[^n]+ServiceProvider::class),\s*];$/m",
                    "$1\n];",
                    $contents
                )
            );

            $this->info("Provider [{$this->argument('module')}] successfully removed.");
        }
    }
}
