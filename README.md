## Esensi Core Components Package

> Version 1

An [Esensi](https://github.com/esensi) package, coded by [SiteRocket Labs®](https://www.siterocket.com).

The `Esensi/Core` package is just one package that makes up [Esensi](https://github.com/esensi), a platform built on [Laravel](https://laravel.com). Esensi/Core is the foundational package for the other components package. This package provides core patterns and commonly extended base classes to all other packages of the Esensi platform. These components include an Laravel 8.x compatible HMVC pattern involving UI and API controller classes, an Eloquent model backed repository pattern, confirmed single and bulk actions and so much more. For more details on the inner workings and composition of the classes please consult the generously documented source code.

## Note

This code is specifically designed to be compatible with the [Laravel Framework](https://laravel.com) and may not be compatible as a stand-alone dependency or as part of another framework. The current release of `Esensi/Core` also has some reliance on closed source code. Contact [support@siterocket.com](https://www.siterocket.com/contact) if you would like to get access to these other repositories.

## Quick Start

Install the package with Composer: 

```bash
composer require esensi/core
```

Then add the following providers to the application's config/app.php config file:

```php
'providers' => [
    Esensi\Core\Providers\CoreServiceProvider::class,
    Esensi\Core\Providers\HtmlServiceProvider::class,
    Esensi\Core\Providers\RouteServiceProvider::class,
    Esensi\Core\Providers\ValidationServiceProvider::class,
]
```

Next, publish the vendor resources, configs, and other publishable files for further customization:

```bash
php artisan vendor:publish --provider="Esensi\Core\Providers\CoreServiceProvider"
```

This will publish this package's configs from the `config/` directory to the Laravel application's `config/esensi/core/` directory where they can be loaded under the `esensi/core::` namespace (e.g.: `config('esensi/core::core.aliases')`).

Lastly, begin composing the various layers of the application by creating classes that implement the interface contracts of the `Esensi\Core\Contracts` namespace or extend or compose the `Esensi\Core` namespaced classes and traits.

## How Esensi Works

Esensi implements a derivative of the MVC pattern called Hierarchical MVC (HMVC) or what some would call "orthogonal" architecture. Essentially all domain logic is organized into a domain layer which consists primarily of commands, events, service classes, and of course the familiar repository pattern. This domain layer separates the controller and view layers from the actual model layer. The model layer is largely powered by Laravel's excellent Eloquent ORM (an active-record pattern) and Esensi's own self-validating, traits-based model extension package [`esensi/model`](https://github.com/esensi/model). The controller layer is comprised of UIs which include web UI controllers, RESTful API controllers, and CLI Artisan commands. The UI controllers extend the behavior of the API controllers to provide response presentation via the view layer. This provides a great deal of extension and re-usability while also providing bolt on functionality with the use of traits. Everything else is either convention or syntax sugar to help make development easier and faster.

## Hierarchy Examples

One way to picture Esensi's architecture is to see how common uses cases would be processed. Because so much code is reused between UI contexts, patterns of convention (hence the orthogonal architecture) begin to emerge. For instance a typical CRUD operation could be performed from multiple access points based on UI contexts and yet there is only one definition of business logic.

### Repository

The developer might have the need to store a new blog post. Esensi makes use of the repository pattern so the calling code should load and call the appropriate `App\Repositories\PostRepository@store` method. This repository method would likely then use business logic to create the post described by the passed method arguments. Often this is a simple matter of mapping the request to an Esensi model such as `App\Models\Post` which uses Laravel to hit the database and return the stored model with the new post ID. The active record model is then returned to the calling service class which is free to further manipulate it or pass it off to other service classes. The repository pattern is crucial to defining a barrier between the data layer and the calling classes – often a UI context class.

### API Controller

A mobile app client requests `POST /api/posts`. This request is routed by Laravel to the `App\Http\Apis\PostApi@store` controller method. The API controller is only concerned with the request and not at all concerned about how the framework will handle converting the return value into a JSON response. The API controller maps the request parameters as arguments to a repository method such as `App\Repositories\PostRepository@store`. At this point all of the business logic is reused down the call chain as the client request has been mapped to the repository as defined previously. Once the `App\Models\Post` model is returned from `App\Repositories\PostRepository` the model is simply returned back to the Laravel framework for default conversion into a JSON response. APIs can have all the domain logic organized in the repositories but as a front-end or mobile app friendly RESTful JSON representation.

### UI Controller

A web browser client requests `POST /admin/posts`. This request is routed by Laravel to the `App\Http\Controllers\PostController@store` controller method. This controller is only concerned with responses so it immediately makes a call to it's parent `App\Http\Apis\PostApi@store` controller method. At this point all of the business logic is reused down the call chain as the client request has been mapped to the API controller as defined previously. Once the `App\Models\Post` model is returned from the `App\Http\Apis\PostApi` the model is then passed off to a Blade view for HTML presentation formatting. The Laravel view is then returned by the `PostController` to the framework where it is rendered as an HTML response back to the client. Web UIs can have all the same ability as their API counterparts and all business logic can remain DRY as it is organized in the domain layer.

### Parametization

Other API endpoints would likely point to filterable resources such as `GET /api/posts?page=1&order=id&sort=asc` which would return the first page of `App\Models\Post` results ordered by the `id` attribute and sorted in ascending direction. Because this logic is defined in the `App\Repositories\PostRepository` domain layer class the functionality is available to all UI contexts including the administrative web UI. By simply changing the URL prefix the normal API response can be processed into an HTML table representation instead: `GET /admin/posts?page=1&order=id&sort=asc`.

Furthermore middle-ware classes could be used to handle response formatting further such as converting the JSON representation of a RESTful API response into an XML document or CSV export (e.g.: `?format=xml`). Restricting business logic to the domain layer sets up Esensi applications for more flexible composition choices and greater code reuse that leads to better and cheaper maintainability. Esensi/Core is just a toolbox of conventions and base classes that can be extended to continue using this composition example in Laravel applications.

## Contributing

Thank you for considering contributing to Esensi Core!

## Licensing

Copyright (c) 2022 [SiteRocket Labs](https://www.siterocket.com)

Esensi Core is open-sourced software licensed under the [MIT license](LICENSE.md).
