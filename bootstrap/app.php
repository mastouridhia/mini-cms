<?php

use App\Http\Middleware\EnsureSiteIsWritable;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'writable' => EnsureSiteIsWritable::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            $previous = $exception->getPrevious();

            if ($request->expectsJson() || ! $previous instanceof ModelNotFoundException) {
                return null;
            }

            return match ($previous->getModel()) {
                Post::class => response()->view('errors.article-introuvable', [
                    'recentPosts' => Post::published()->latest('published_at')->take(3)->get(),
                ], 404),
                Category::class => response()->view('errors.categorie-introuvable', [
                    'categories' => Category::orderBy('name')->get(),
                ], 404),
                default => null,
            };
        });
    })->create();
