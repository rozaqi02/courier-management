<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CourierController extends Controller
{
    /**
     * Display a listing of the couriers.
     * Supports pagination, sorting, search, and level filter.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Courier::query();

        // Search by name (partial match)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by level (e.g., ?level=2,3)
        if ($request->filled('level')) {
            $levels = explode(',', $request->level);
            $levels = array_map('intval', $levels);
            $query->whereIn('level', $levels);
        }

        // Sorting: default by name, override by 'sort' param
        $sortBy = $request->get('sort', 'name');
        $allowedSorts = ['name', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'name';
        }
        $query->orderBy($sortBy, 'asc');

        // Pagination
        $perPage = $request->get('per_page', 10);
        $couriers = $query->paginate($perPage);

        return response()->json($couriers);
    }

    /**
     * Store a newly created courier in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|unique:couriers,email|max:255',
            'level'         => 'required|integer|min:1|max:5',
            'vehicle_type'  => 'nullable|in:motorcycle,car,truck',
            'vehicle_plate' => 'nullable|string|max:20',
            'address'       => 'nullable|string',
            'status'        => 'nullable|in:active,inactive',
        ]);

        $courier = Courier::create($validated);

        return response()->json([
            'message' => 'Courier created successfully.',
            'data'    => $courier,
        ], 201);
    }

    /**
     * Display the specified courier.
     */
    public function show(Courier $courier): JsonResponse
    {
        return response()->json($courier);
    }

    /**
     * Update the specified courier in storage.
     */
    public function update(Request $request, Courier $courier): JsonResponse
    {
        $validated = $request->validate([
            'name'          => 'sometimes|required|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|unique:couriers,email,' . $courier->id . '|max:255',
            'level'         => 'sometimes|required|integer|min:1|max:5',
            'vehicle_type'  => 'nullable|in:motorcycle,car,truck',
            'vehicle_plate' => 'nullable|string|max:20',
            'address'       => 'nullable|string',
            'status'        => 'nullable|in:active,inactive',
        ]);

        $courier->update($validated);

        return response()->json([
            'message' => 'Courier updated successfully.',
            'data'    => $courier->fresh(),
        ]);
    }

    /**
     * Remove the specified courier from storage.
     */
    public function destroy(Courier $courier): JsonResponse
    {
        $courier->delete();

        return response()->json([
            'message' => 'Courier deleted successfully.',
        ]);
    }
}
