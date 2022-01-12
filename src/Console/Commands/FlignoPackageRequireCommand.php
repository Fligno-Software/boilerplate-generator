<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class FlignoPackageRequireCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class FlignoPackageRequireCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        return self::SUCCESS;
    }
}
