<?php

namespace App\Providers;

use App\Console\Commands\MakeRepositoryCommand;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * @var array|string[]
     */
    protected array $commands = [
        MakeRepositoryCommand::class,
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->commands($this->commands);
    }
}
