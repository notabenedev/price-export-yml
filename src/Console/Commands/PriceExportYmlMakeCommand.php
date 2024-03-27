<?php

namespace Notabenedev\PriceExportYml\Console\Commands;

use PortedCheese\BaseSettings\Console\Commands\BaseConfigModelCommand;

class PriceExportYmlMakeCommand extends BaseConfigModelCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:price-export-yml
                    {--all : Run all}
                    {--controllers : Export controllers}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make price-export-yml settings';
    protected $vendorName = 'Notabenedev';
    protected $packageName = "PriceExportYml";


    /**
     * Make Controllers
     */
    protected $controllers = [
        "Site" => ["PriceExportYmlController"],
    ];


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $all = $this->option("all");

        if ($this->option("controllers") || $all) {
             $this->exportControllers("Site");
        }

    }

}
