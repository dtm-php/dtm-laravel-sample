<?php

namespace App\Providers;

use DtmClient\Api\ApiInterface;
use DtmClient\Api\HttpApi;
use DtmClient\ApiFactory;
use DtmClient\BranchIdGenerator;
use DtmClient\BranchIdGeneratorInterface;
use DtmClient\DbTransaction\DBTransactionInterface;
use DtmClient\DbTransaction\LaravelDbTransaction;
use GuzzleHttp\Client;
use Hyperf\Contract\ConfigInterface;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Http\Client\Response;

class DtmProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->app->singleton(BranchIdGeneratorInterface::class, function ($app) {
            return new BranchIdGenerator();
        });

        $this->app->singleton(DBTransactionInterface::class, function ($app) {
            return new LaravelDbTransaction();
        });

        $this->app->singleton(ConfigInterface::class, function ($app) {
            $laravelConfig = $app->get('config');
            return new \Hyperf\Config\Config($laravelConfig->get('dtm'));
        });

        // 依赖注入 HttpApi::class
        $this->app->singleton( HttpApi::class, function ($app) {
            $client = $app->get(Client::class);
            $config = $app->get(ConfigInterface::class);
            return new HttpApi($client, $config);
        });

        // 依赖注入 ApiInterface::class
        $this->app->singleton( ApiInterface::class, function ($app) {
            return (new ApiFactory())($app);
        });


        // 依赖注入 TCC:class
        $this->app->singleton( \DtmClient\TCC::class, function ($app) {
            $idGenerator = $app->get(BranchIdGeneratorInterface::class);
            $apiInterface = $app->get(HttpApi::class);
            return new \DtmClient\TCC($apiInterface, $idGenerator);
        });

        $this->app->singleton( Hyperf\HttpServer\Contract\ResponseInterface::class, function ($app) {
            return $app->get(Response::class);
        });
    }
}
