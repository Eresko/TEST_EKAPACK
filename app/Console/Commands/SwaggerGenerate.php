<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenApi\Generator;

class SwaggerGenerate extends Command
{
    protected $signature = 'swagger:generate';
    protected $description = 'Generate Swagger JSON';

    public function handle()
    {

        $openapi = Generator::scan([
            app_path('Http/Controllers/Api'), // сканируем только API-контроллеры
            app_path('Swagger/SwaggerInfo.php') // добавляем Info
        ]);
        $outputFile = public_path('swagger.json');
        file_put_contents($outputFile, $openapi->toJson());
        $this->info('Swagger JSON generated: ' . $outputFile);
    }
}