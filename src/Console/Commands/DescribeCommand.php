<?php

namespace Fligno\BoilerplateGenerator\Console\Commands;

use Fligno\BoilerplateGenerator\Exceptions\MissingNameArgumentException;
use Fligno\BoilerplateGenerator\Exceptions\PackageNotFoundException;
use Fligno\BoilerplateGenerator\Traits\UsesCommandVendorPackageDomainTrait;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;

/**
 * Class DescribeCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class DescribeCommand extends Command
{
    use UsesCommandVendorPackageDomainTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'bg:describe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display all information about Laravel app and/or package/s.';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->addPackageDomainOptions(has_force: false, has_force_domain: false);
    }

    /**
     * Execute the console command.
     *
     * @return int
     *
     * @throws MissingNameArgumentException|PackageNotFoundException
     */
    public function handle(): int
    {
        $this->setVendorPackageDomain(true, false);

        $this->describePackage($this->package_dir);

        // Create a new Table instance.
        $table = new Table($this->output);

        // Set the table headers.
        $table->setHeaders([
            'Site', 'Description', 'Status',
        ]);

        // Create a new TableSeparator instance.
        $separator = new TableSeparator;

        // Set the contents of the table.
        $table->setRows([
            [new TableCell('First Party', ['colspan' => 3])],
            $separator,
            [
                'https://laravel.com',
                'The official Laravel website',
                '<info>Online</info>',
            ],
            [
                'https://forge.laravel.com/',
                'Painless PHP Servers',
                '<info>Online</info>',
            ],
            [
                'https://envoyer.io/',
                'Zero Downtime PHP Deployment',
                '<info>Online</info>',
            ],
            //            $separator,
            //            [new TableCell('Useful Resources', ['colspan' => 3])],
            //            $separator,
            //            [
            //                'https://laracasts.com/',
            //                'The Best Laravel and PHP Screencasts',
            //                '<info>Online</info>'
            //            ],
            //            [
            //                'https://laracasts.com/discuss',
            //                'Laracasts Web Development Forum',
            //                '<info>Online</info>'
            //            ],
            //            $separator,
            //            [new TableCell('Other', ['colspan' => 3])],
            //            $separator,
            //            [
            //                'example.org',
            //                'An example experiencing issues.',
            //                '<bg=yellow;fg=black>Experiencing Issues</>']
            //            ,
            //            ['example.org', 'An example offline site.', '<error>Offline</error>']
        ]);

        // Render the table to the output.
        $table->render();
        $table->
        $table->setHeaders([
            'Hello', 'World', 'Status',
        ])->render();

        return self::SUCCESS;
    }

    /**
     * Class type to append on filename.
     *
     * @return string|null
     */
    protected function getClassType(): ?string
    {
        return null;
    }

    /**
     * @param  string|null  $package
     * @return void
     */
    public function describePackage(string $package = null): void
    {
        $path = $package ? package_domain_path($package) : null;

        $composerContents = getContentsFromComposerJson($path);

        dd($path, $composerContents);
    }
}
