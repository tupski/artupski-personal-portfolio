<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Redirect;
use App\Models\SiteSetting;
use App\Models\Tag;
use App\Models\Technology;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\ContactMessagePolicy;
use App\Policies\NavigationItemPolicy;
use App\Policies\PagePolicy;
use App\Policies\PostPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\RedirectPolicy;
use App\Policies\SiteSettingPolicy;
use App\Policies\TagPolicy;
use App\Policies\TechnologyPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Post::class => PostPolicy::class,
        Page::class => PagePolicy::class,
        Project::class => ProjectPolicy::class,
        Category::class => CategoryPolicy::class,
        Tag::class => TagPolicy::class,
        Technology::class => TechnologyPolicy::class,
        ContactMessage::class => ContactMessagePolicy::class,
        Redirect::class => RedirectPolicy::class,
        NavigationItem::class => NavigationItemPolicy::class,
        User::class => UserPolicy::class,
        SiteSetting::class => SiteSettingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin-panel', function (User $user) {
            return in_array($user->role->value, ['super_admin', 'editor', 'author']);
        });
    }
}
