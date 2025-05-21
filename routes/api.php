<?php

// ✅ Fungsi respons untuk menangani preflight (OPTIONS)
function corsOptionsResponse()
{
    return respond('', 204)
        ->setHeader('Access-Control-Allow-Origin', '*')
        ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
        ->setHeader('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization')
        ->setHeader('Access-Control-Allow-Credentials', 'true');
}

use App\Controllers\Api\AuthController;
use App\Controllers\Api\CommentController;
use App\Controllers\Api\DashboardController;
use App\Middleware\AuthMiddleware;
use App\Middleware\DashboardMiddleware;
use App\Middleware\TzMiddleware;
use Core\Routing\Route;

/**
 * Make something great with this app
 * keep simple yeah.
 */

Route::prefix('/session')->group(function () {
    Route::post('/', [AuthController::class, 'login']);
    Route::options('/', fn () => corsOptionsResponse()); // ✅ CORS
});

Route::middleware([AuthMiddleware::class, TzMiddleware::class])->group(function () {

    // Dashboard
    Route::middleware(DashboardMiddleware::class)->group(function () {
        Route::get('/download', [DashboardController::class, 'download']);
        Route::options('/download', fn () => corsOptionsResponse()); // ✅

        Route::get('/stats', [DashboardController::class, 'stats']);
        Route::options('/stats', fn () => corsOptionsResponse()); // ✅

        Route::put('/key', [DashboardController::class, 'rotate']);
        Route::options('/key', fn () => corsOptionsResponse()); // ✅

        Route::get('/user', [DashboardController::class, 'user']);
        Route::patch('/user', [DashboardController::class, 'update']);
        Route::options('/user', fn () => corsOptionsResponse()); // ✅
    });

    Route::get('/config', [DashboardController::class, 'config']);
    Route::options('/config', fn () => corsOptionsResponse()); // ✅

    // Comment
    Route::prefix('/comment')->group(function () {

        Route::controller(CommentController::class)->group(function () {
            Route::get('/', 'get');
            Route::post('/', 'create');
        });

        Route::options('/', fn () => corsOptionsResponse()); // ✅

        Route::prefix('/{id}')->group(function () {
            Route::controller(CommentController::class)->group(function () {
                Route::get('/', 'show');
                Route::put('/', 'update');
                Route::delete('/', 'destroy');

                // Like or unlike comment
                Route::post('/', 'like');
                Route::patch('/', 'unlike');
            });

            Route::options('/', fn () => corsOptionsResponse()); // ✅
        });
    });

    // api v2 comment
    Route::prefix('/v2')->group(function () {
        Route::get('/config', [DashboardController::class, 'configV2']);
        Route::options('/config', fn () => corsOptionsResponse()); // ✅

        Route::prefix('/comment')->group(function () {
            Route::get('/', [CommentController::class, 'getV2']);
            Route::options('/', fn () => corsOptionsResponse()); // ✅
        });
    });
});
