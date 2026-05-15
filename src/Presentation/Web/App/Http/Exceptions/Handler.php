<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Infrastructure\Exceptions\EntityFindException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\{HttpException, MethodNotAllowedHttpException, NotFoundHttpException};
use Throwable;

final class Handler extends ExceptionHandler
{
    protected $dontReport = [
        AuthenticationException::class,
        InvalidSignatureException::class,
        NotFoundHttpException::class,
        MethodNotAllowedHttpException::class,
        ThrottleRequestsException::class,
        ValidationException::class,
    ];

    public function render(mixed $request, Throwable $e): Response
    {
        if ($e instanceof ValidationException) {
            return parent::render($request, $e);
        }

        if ($e instanceof AuthenticationException) {
            return redirect()->route('web.login.page');
        }

        $status = match (true) {
            $e instanceof EntityFindException,
            $e instanceof NotFoundHttpException         => 404,
            $e instanceof MethodNotAllowedHttpException => 405,
            $e instanceof ThrottleRequestsException     => 429,
            $e instanceof HttpException                 => $e->getStatusCode(),
            default                                     => 500,
        };

        if ($status === 500) {
            Log::critical("{$e->getMessage()}\n{$e->getTraceAsString()}");
        }

        return Inertia::render('Error', [
            'status'  => $status,
            'message' => config('app.debug') ? $e->getMessage() : null,
        ])
            ->toResponse($request)
            ->setStatusCode($status);
    }
}
