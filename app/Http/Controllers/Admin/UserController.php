<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        // Date filter
        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        // Sorting
        $sortField = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');

        $validSortFields = ['name', 'email', 'created_at', 'last_login_at'];
        if (!in_array($sortField, $validSortFields)) {
            $sortField = 'name';
        }

        if ($sortField === 'last_login_at') {
            $query->orderByRaw("last_login_at IS NULL, last_login_at {$sortOrder}");
        } else {
            $query->orderBy($sortField, $sortOrder);
        }

        $users = $query->withCount(['orders', 'testimonials'])
                      ->paginate(15)
                      ->appends($request->query());

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email_verified' => 'boolean',
            'send_welcome_email' => 'boolean',
        ]);

        // Handle avatar upload
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'avatar' => $avatarPath,
            'email_verified_at' => $request->boolean('email_verified') ? now() : null,
        ]);

        // Send welcome email if requested
        if ($request->boolean('send_welcome_email')) {
            // You can implement email sending logic here
            // Mail::to($user)->send(new WelcomeEmail($user));
        }

        return redirect()->route('admin.users.index')
            ->with('success', __('User created successfully.'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['orders', 'testimonials', 'wishlist']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:user,admin',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
            'email_verified' => 'boolean',
            'remove_avatar' => 'boolean',
        ]);

        // Prevent users from changing their own role
        if ($user->id === auth()->id()) {
            unset($validated['role']);
        }

        // Handle avatar upload/removal
        if ($request->boolean('remove_avatar') && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $validated['avatar'] = null;
        } elseif ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        } else {
            // Keep existing avatar
            unset($validated['avatar']);
        }

        // Handle password update
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle email verification
        if ($request->boolean('email_verified') && !$user->email_verified_at) {
            $validated['email_verified_at'] = now();
        } elseif (!$request->boolean('email_verified') && $user->email_verified_at) {
            $validated['email_verified_at'] = null;
        }

        // Remove email_verified from validated data as we handle it separately
        unset($validated['email_verified'], $validated['remove_avatar']);

        // Update user
        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', __('User updated successfully.'));
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent users from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', __('You cannot delete your own account.'));
        }

        // Delete avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Delete user
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', __('User deleted successfully.'));
    }

    /**
     * Toggle user status (activate/deactivate)
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => __('You cannot change your own status.')
            ], 403);
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => __('User status updated successfully.'),
            'is_active' => $user->is_active
        ]);
    }

    /**
     * Verify user email
     */
    public function verifyEmail(User $user)
    {
        if ($user->email_verified_at) {
            return redirect()->back()
                ->with('info', __('Email is already verified.'));
        }

        $user->update([
            'email_verified_at' => now()
        ]);

        return redirect()->back()
            ->with('success', __('Email verified successfully.'));
    }

    /**
     * Send password reset email
     */
    public function sendPasswordReset(User $user)
    {
        // Generate password reset token and send email
        // This is a placeholder - implement actual password reset logic
        
        return redirect()->back()
            ->with('success', __('Password reset email sent successfully.'));
    }

    /**
     * Get user statistics for dashboard
     */
    public function getStatistics()
    {
        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)
                                         ->whereYear('created_at', now()->year)
                                         ->count(),
            'active_users_last_30_days' => User::where('last_login_at', '>=', now()->subDays(30))->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Export users data
     */
    public function export(Request $request)
    {
        // This is a placeholder for export functionality
        // You can implement CSV, Excel, or PDF export here
        
        $users = User::all();
        
        // Example: Generate CSV
        $filename = 'users_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Role', 'Email Verified', 'Created At', 'Last Login']);
            
            // CSV data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone,
                    $user->role ?? 'user',
                    $user->email_verified_at ? 'Yes' : 'No',
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}