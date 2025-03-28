<?php

namespace App\Console\Commands;

use App\Services\DatabaseCleanerService;
use Illuminate\Console\Command;

class CleanDatabaseContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean HTML, XML, and XSS content from ideas and comments';

    /**
     * Execute the console command.
     */
    public function handle(DatabaseCleanerService $cleaner)
    {
        $this->info('Starting database content cleaning...');
        
        try {
            $stats = $cleaner->cleanDatabase();
            
            $this->info('Database cleaning completed successfully!');
            $this->table(
                ['Category', 'Items Cleaned'],
                [
                    ['Ideas', $stats['ideas_cleaned']],
                    ['Comments', $stats['comments_cleaned']],
                ]
            );
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Database cleaning failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
