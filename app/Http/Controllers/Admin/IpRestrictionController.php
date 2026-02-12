<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AllowedIp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller for managing allowed IP addresses.
 */
class IpRestrictionController extends Controller
{
    /**
     * Displays a listing of the allowed IP addresses.
     *
     * @return View
     *   The allowed IPs index view.
     */
    public function index(): View
    {
        $ips = AllowedIp::all();
        return view('admin.ips.index', compact('ips'));
    }

    /**
     * Show the form for creating a new allowed IP address.
     *
     * @return View
     *   The allowed IP creation view.
     */
    public function create(): View
    {
        return view('admin.ips.create');
    }

    /**
     * Stores a newly created allowed IP address in storage.
     *
     * @param Request $request
     *   The incoming request.
     *
     * @return RedirectResponse
     *   A redirect to the allowed IPs index.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ip_address' => 'required|ip|unique:allowed_ips,ip_address',
            'description' => 'nullable|string|max:255',
        ]);

        AllowedIp::create($validated);

        return redirect()->route('admin.ips.index')->with('status', 'ip-created');
    }

    /**
     * Show the form for editing the specified allowed IP address.
     *
     * @param AllowedIp $ip
     *   The allowed IP to edit.
     *
     * @return View
     *   The allowed IP edit view.
     */
    public function edit(AllowedIp $ip): View
    {
        return view('admin.ips.edit', compact('ip'));
    }

    /**
     * Update the specified allowed IP address in storage.
     *
     * @param Request $request
     *   The incoming request.
     * @param AllowedIp $ip
     *   The allowed IP to update.
     *
     * @return RedirectResponse
     *   A redirect to the allowed IPs index.
     */
    public function update(Request $request, AllowedIp $ip): RedirectResponse
    {
        $validated = $request->validate([
            'ip_address' => 'required|ip|unique:allowed_ips,ip_address,' . $ip->id,
            'description' => 'nullable|string|max:255',
        ]);

        $ip->update($validated);

        return redirect()->route('admin.ips.index')->with('status', 'ip-updated');
    }

    /**
     * Removes the specified allowed IP address from storage.
     *
     * @param AllowedIp $ip
     *  The allowed IP model instance.
     *
     * @return RedirectResponse
     *  A redirect to the allowed IPs index.
     */
    public function destroy(AllowedIp $ip): RedirectResponse
    {
        $ip->delete();

        return redirect()->route('admin.ips.index')->with('status', 'ip-deleted');
    }
}
