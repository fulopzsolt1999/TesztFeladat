<?php

class Kernel
{
   protected $middleware = [
      // other middleware...
      \App\Http\Middleware\CorsMiddleware::class,
   ];
}
