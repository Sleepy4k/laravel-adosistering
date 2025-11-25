<?php

namespace App\Console\Commands;

use DateTime;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;

class MakeSitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a sitemap for the application.';

    /**
     * The collection of sitemap data.
     */
    protected array $sitemapData;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Gathering sitemap data...');

        $this->sitemapData = array_map(function ($item) {
            return Url::create($item['url'])
                ->setLastModificationDate(new DateTime($item['last_modified'] ?? 'now'))
                ->setChangeFrequency($item['change_frequency'] ?? null)
                ->setPriority($item['priority'] ?? null);
        }, config('sitemap.list', []));

        $this->info('Data successfully gathered.');

        $this->info('Generating sitemap...');

        try {
            $sitemap = Sitemap::create();

            foreach ($this->sitemapData as $url) {
                $sitemap->add($url);
            }

            $sitemap->writeToFile(public_path('sitemap.xml'));

            $this->info('Sitemap generated successfully.');
        } catch (\Throwable $e) {
            $this->error('Failed to generate sitemap: ' . $e->getMessage());
            return 1;
        }
    }
}
