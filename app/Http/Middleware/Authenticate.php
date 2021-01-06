<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware {

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request) {
        if (!$request->expectsJson()) {
            return route('web.login');
        } else {
            return route('api.unauthenticated');
        }
    }

    protected function unauthenticated($request, array $guards) {
        $response = [
            'success' => false,
            'data' => [],
            'message' => "Você não esta conectado.",
            'code' => 401
        ];
        
        echo json_encode($response);
        die;
        throw new AuthenticationException(
        'Acesso não autorizado', $guards, $this->redirectTo($request)
        );
    }

}
