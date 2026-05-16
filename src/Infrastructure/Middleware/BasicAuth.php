<?php

declare(strict_types=1);

namespace Infrastructure\Middleware;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\{RedirectResponse, Request};
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

abstract readonly class BasicAuth
{
    protected string $username;
    protected string $password;

    public function __construct()
    {
        $config = config($this->getConfigPath());

        if (!isset($config['username'], $config['password'])) {
            throw new RuntimeException('Invalid Auth configuration. Class - ' . static::class);
        }

        $this->username = $config['username'];
        $this->password = $config['password'];
    }

    /**
     * @throws BindingResolutionException
     */
    public function handle(Request $request, Closure $next): RedirectResponse|Response
    {
        $username = $request->header('PHP_AUTH_USER');
        $password = $request->header('PHP_AUTH_PW');

        if (!hash_equals($this->username, (string) $username) || !hash_equals($this->password, (string) $password)) {
            return response()->make(
                'Authentication Required',
                Response::HTTP_UNAUTHORIZED,
                [
                    'WWW-Authenticate' => 'Basic',
                ]
            );
        }

        return $next($request);
    }

    abstract protected function getConfigPath(): string;
}
