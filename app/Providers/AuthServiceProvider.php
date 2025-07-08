<?php

namespace App\Providers;


use App\Models\Menu;
use App\Models\Document;
use App\Policies\MenuPolicy;
use App\Policies\DocumentPolicy;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Menu::class => \App\Policies\MenuPolicy::class,
        \App\Models\Document::class => \App\Policies\DocumentPolicy::class,
        \App\Models\Archive::class => \App\Policies\ArchivePolicy::class,
];

        // Adicione outras policies aqui se necessário
   

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
