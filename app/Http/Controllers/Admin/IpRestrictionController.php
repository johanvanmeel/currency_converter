<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AllowedIp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controller for managing allowed IP addresses.
 */
class IpRestrictionController extends Controller
{
    /**
     * Displays a listing of the allowed IP addresses.
     *
     * @return JsonResponse
     *   The JSON response.
     */
    public function index(): JsonResponse
    {
        return response()->json(AllowedIp::all());
    }

    /**
     * Stores a newly created allowed IP address in storage.
     *
     * @param Request $request
     *   The incoming request.
     *
     * @return JsonResponse
     *   The JSON response.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ip_address' => 'required|string',
            'description' => 'nullable|string|max:255',
        ]);

        $allowedIp = AllowedIp::create($validated);

        return response()->json([
            'message' => 'IP address added successfully.',
            'allowed_ip' => $allowedIp,
        ]);
    }

    /**
     * Removes the specified allowed IP address from storage.
     *
     * @param AllowedIp $allowedIp
     *  The allowed IP model instance.
     *
     * @return JsonResponse
     *  The JSON response.
     */
    public function destroy(AllowedIp $allowedIp): JsonResponse
    {
        $allowedIp->delete();

        return response()->json(['message' => 'IP address removed successfully.']);
    }
}
