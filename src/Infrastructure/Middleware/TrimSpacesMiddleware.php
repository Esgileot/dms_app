<?php

declare(strict_types=1);

namespace Infrastructure\Middleware;

use Closure;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request, Response};
use Symfony\Component\HttpFoundation\{BinaryFileResponse, StreamedResponse};

class TrimSpacesMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next
    ): BinaryFileResponse|JsonResponse|RedirectResponse|Response|StreamedResponse {
        if (!in_array($request->method(), ['POST', 'PATCH', 'PUT'], true)) {
            return $next($request);
        }

        $input = $request->all();
        $request->merge($this->process($input));

        return $next($request);
    }

    private function process(array $input): array
    {
        foreach ($input as &$item) {
            if (is_array($item)) {
                $item = $this->process($item);
            }

            if (is_string($item)) {
                $item = preg_replace(['/(?:\r?\n){2,}/', '/( {2,})/'], [PHP_EOL . PHP_EOL, ' '], $item);
            }
        }

        return $input;
    }
}
