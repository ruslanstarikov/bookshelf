<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class IsbnSearchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:isbn-search-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isbn = $this->ask('ISBN');

        $book = $this->getBookTitleByIsbn($isbn);
        $this->info(json_encode($book));
    }

    function getBookTitleByIsbn(string $isbn)
    {
        $url = "https://www.googleapis.com/books/v1/volumes?q=isbn:" . urlencode($isbn);

        $response = file_get_contents($url);

        if ($response === false) {
            return null;
        }

        $data = json_decode($response, true);

        if (!isset($data['items'][0]['volumeInfo']['title'])) {
            return null;
        }

        return $data['items'][0]['volumeInfo'];
    }
}
