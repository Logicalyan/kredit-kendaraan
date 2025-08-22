<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait ApiResponses
{
    /**
     * Standard Success Response
     */
    protected function success($data, string $message = 'Success', int $status = 200)
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data
        ], $status);
    }

    /**
     * Standard Error Response
     */
    protected function error(string $message, int $status = 400, array $errors = [])
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'errors'  => $errors
        ], $status);
    }

    /**
     * Paginated, Filtered, and Sorted Response
     */
    protected function paginated(
        Builder $query,
        Request $request,
        array $allowedFilters = [],
        array $allowedSorts = [],
        array $allowedRelations = [],
        array $searchable = [],
        array $filterMeta = [] // ✅ pass dropdown options here
    ) {
        // 🔹 Direct filters
        foreach ($allowedFilters as $field) {
            if ($request->has("filters.$field")) {
                $value = $request->input("filters.$field");

                if (is_array($value)) {
                    $query->whereIn($field, $value);
                } else {
                    $query->where($field, $value);
                }
            }
        }

        // 🔹 Relational filters
        foreach ($allowedRelations as $relation => $fields) {
            foreach ($fields as $field) {
                if ($request->has("filters.$field")) {
                    $value = $request->input("filters.$field");

                    $query->whereHas($relation, function ($q) use ($field, $value) {
                        if (is_array($value)) {
                            $q->whereIn($field, $value);
                        } else {
                            $q->where($field, $value);
                        }
                    });
                }
            }
        }

        // 🔹 Global search
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm, $searchable) {
                foreach ($searchable as $field) {
                    if (str_contains($field, '.')) {
                        // relation search (e.g. roles.name)
                        [$relation, $column] = explode('.', $field);
                        $q->orWhereHas($relation, fn ($sub) =>
                            $sub->where($column, 'like', "%{$searchTerm}%")
                        );
                    } else {
                        $q->orWhere($field, 'like', "%{$searchTerm}%");
                    }
                }
            });
        }

        // 🔹 Sorting
        if ($request->has('sort')) {
            $sorts = explode(',', $request->input('sort'));
            foreach ($sorts as $sort) {
                $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
                $field = ltrim($sort, '-');
                if (in_array($field, $allowedSorts)) {
                    $query->orderBy($field, $direction);
                }
            }
        }

        // 🔹 Pagination
        $perPage = $request->input('per_page', 10);
        $pagination = $query->paginate($perPage)->appends($request->query());

        return response()->json([
            'status' => 'success',
            'message' => 'Data fetched successfully',
            'data'   => $pagination->items(),
            'meta'   => [
                'total'        => $pagination->total(),
                'per_page'     => $pagination->perPage(),
                'current_page' => $pagination->currentPage(),
                'last_page'    => $pagination->lastPage(),
            ],
            'filters' => $filterMeta,
            'search'  => $request->input('search')
        ]);
    }
}
