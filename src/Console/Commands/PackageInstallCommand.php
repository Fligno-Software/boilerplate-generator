<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Class ChoiceCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class PackageInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:choice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install packages as a start.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $response = Http::get('https://packagist.org/packages/list.json?vendor=fourello-devs');

        if ($response->ok()) {
            $choices = collect($response->json('packageNames'))->map(function ($package) {
                return $package . ' <fg=green>($10)</>';
            });

            $this->choice('What package do you want to install?', $choices->toArray());
        }

        return 0;
    }
}
