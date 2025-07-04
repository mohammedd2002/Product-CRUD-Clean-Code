<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class CreateProductCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:create {name} {--fixedAge}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new product';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $fixedAge = $this->option('fixedAge');
         $product = Product::UpdateOrCreate(['name' => $name] , [
            'price' => $fixedAge ? 300 : rand(100,500) ,
         ]);
         if ($product) {
           $this->info('sucessfully');
        //    $this->error('sucessfully');
         }
      
    }
}
