<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class FlignoPackageListCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-12-06
 */
class FlignoPackageListCommand extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'fligno:package:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all locally installed packages.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->call('packager:list', [
            '--git' => true
        ]);
    }
}
