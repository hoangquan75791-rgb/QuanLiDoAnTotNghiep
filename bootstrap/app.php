<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Thêm 3 dòng 'use' này
use App\Http\Middleware\CheckAdminRole;
use Illuminate\Routing\Middleware\SubstituteBindings;
// Import đúng class 'verified'
use Illuminate\Auth\Middleware\EnsureEmailIsVerified; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // Cập nhật alias (bí danh)
        $middleware->alias([
            
            // === DÒNG ĐÃ SỬA LỖI ===
            // Chúng ta dùng class EnsureEmailIsVerified đã import ở trên
            'verified' => EnsureEmailIsVerified::class, 
            
            'admin' => CheckAdminRole::class,
        ]);
        
        $middleware->web(append: [
            SubstituteBindings::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
