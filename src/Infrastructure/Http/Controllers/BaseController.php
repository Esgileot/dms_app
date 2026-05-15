<?php

declare(strict_types=1);

namespace Infrastructure\Http\Controllers;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use RuntimeException;

abstract class BaseController
{
    protected function buildPaginatedResponse(
        string $resourceClass,
        AbstractCursorPaginator|AbstractPaginator|CursorPaginator|LengthAwarePaginator|Paginator $paginator,
        ?string $name = null,
    ): JsonResponse {
        if (
            !is_subclass_of($resourceClass, JsonResource::class)
            && !is_subclass_of($resourceClass, ResourceCollection::class)
        ) {
            throw new RuntimeException('Resource class must be a subclass of ' . JsonResource::class);
        }

        $headers = match (true) {
            $paginator instanceof LengthAwarePaginator => [
                'Access-Control-Expose-Headers' => 'Content-Range',
                'Content-Range' => $this->generateContentRangeFromPaginator($paginator, $name),
            ],
            default => []
        };

        return response()->json(
            is_subclass_of($resourceClass, ResourceCollection::class)
            ? new $resourceClass($paginator)
            : $resourceClass::collection($paginator)
        )
            ->withHeaders($headers);
    }

    protected function generateContentRangeFromPaginator(LengthAwarePaginator $paginator, string $key): string
    {
        $firstItem = ($paginator->firstItem() - 1 < 0) ? 0 : ($paginator->firstItem() - 1);
        $lastItem = ($paginator->lastItem() - 1 < 0) ? 0 : ($paginator->lastItem() - 1);

        return "{$key} {$firstItem}-{$lastItem}/{$paginator->total()}";
    }
}
