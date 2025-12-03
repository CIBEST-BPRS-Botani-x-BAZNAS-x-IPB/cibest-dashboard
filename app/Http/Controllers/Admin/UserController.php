<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the pending verification users.
     */
    public function index()
    {
        // Only allow admin users to access this page
        if (Auth::user()->user_role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $pendingUsers = User::pendingVerification()->get();
        $verifiedUsers = User::verified()->get();
        $rejectedUsers = User::rejected()->get();

        return Inertia::render('admin/users/index', [
            'pendingUsers' => $pendingUsers,
            'verifiedUsers' => $verifiedUsers,
            'rejectedUsers' => $rejectedUsers,
        ]);
    }

    /**
     * Approve a user's registration.
     */
    public function approve(Request $request, User $user)
    {
        // Only allow admin users to perform this action
        if (Auth::user()->user_role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $user->update([
            'admin_verification_status' => 'verified',
            'admin_verified_at' => now(),
            'admin_verified_by' => Auth::id(),
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User approved successfully.');
    }

    /**
     * Reject a user's registration.
     */
    public function reject(Request $request, User $user)
    {
        // Only allow admin users to perform this action
        if (Auth::user()->user_role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $user->update([
            'admin_verification_status' => 'rejected',
            'admin_verified_at' => now(),
            'admin_verified_by' => Auth::id(),
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User rejected successfully.');
    }

    /**
     * Show user details for admin review.
     */
    public function show(User $user)
    {
        // Only allow admin users to access this page
        if (Auth::user()->user_role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return Inertia::render('admin/users/show', [
            'user' => $user
        ]);
    }
}
