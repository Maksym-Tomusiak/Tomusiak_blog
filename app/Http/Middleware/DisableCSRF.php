<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class DisableCSRF extends Middleware
{
    protected $except = [
        'api/blog/posts/*',
        'api/blog/categories/*'
    ];
}

