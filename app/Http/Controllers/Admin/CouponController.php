<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    /**
     * Display a listing of the coupons.
     */
    public function index()
    {
        $activeCoupons = Coupon::with('user')
            ->where('is_used', false)
            ->where('valid_until', '>=', now())
            ->latest()
            ->get();
            
        $usedCoupons = Coupon::with(['user', 'order'])
            ->where('is_used', true)
            ->latest()
            ->get();
            
        $expiredCoupons = Coupon::with('user')
            ->where('is_used', false)
            ->where('valid_until', '<', now())
            ->latest()
            ->get();
            
        return view('admin.coupons.index', compact(
            'activeCoupons',
            'usedCoupons',
            'expiredCoupons'
        ));
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create()
    {
        // Fix: Change 'user' to 'customer' to match your database
        $users = User::where('role', 'customer')->get();
        return view('admin.coupons.create', compact('users'));
    }

    /**
     * Store a newly created coupon in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id', // Make user_id nullable for general coupons
            'amount' => 'required|numeric|min:1',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'valid_months' => 'required|integer|min:1',
            'code' => 'nullable|string|unique:coupons,code',
        ]);
        
        // Convert to integers to avoid Carbon issues
        $validMonths = (int) $validated['valid_months'];
        $amount = (float) $validated['amount'];
        $minPurchaseAmount = isset($validated['min_purchase_amount']) ? (float) $validated['min_purchase_amount'] : 0;
        
        // Generate a unique coupon code if not provided
        if (empty($validated['code'])) {
            $code = strtoupper(Str::random(8));
            
            // Ensure code is unique
            while (Coupon::where('code', $code)->exists()) {
                $code = strtoupper(Str::random(8));
            }
        } else {
            $code = $validated['code'];
        }
        
        Coupon::create([
            'code' => $code,
            'amount' => $amount,
            'min_purchase_amount' => $minPurchaseAmount,
            'valid_from' => now(),
            'valid_until' => now()->addMonths($validMonths),
            'is_used' => false,
            'user_id' => $validated['user_id'] ?? null, // Allow null for general coupons
            'order_id' => null,
        ]);
        
        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    /**
     * Display the specified coupon.
     */
    public function show(Coupon $coupon)
    {
        $coupon->load(['user', 'order']);
        return view('admin.coupons.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit(Coupon $coupon)
    {
        // Don't allow editing used coupons
        if ($coupon->is_used) {
            return redirect()->route('admin.coupons.show', $coupon)
                ->with('error', 'Used coupons cannot be edited.');
        }
        
        // Fix: Change 'user' to 'customer'
        $users = User::where('role', 'customer')->get();
        return view('admin.coupons.edit', compact('coupon', 'users'));
    }

    /**
     * Update the specified coupon in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        // Don't allow updating used coupons
        if ($coupon->is_used) {
            return redirect()->route('admin.coupons.show', $coupon)
                ->with('error', 'Used coupons cannot be updated.');
        }
        
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id', // Make nullable
            'amount' => 'required|numeric|min:1',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'valid_until' => 'required|date|after:today',
            'code' => 'required|string|unique:coupons,code,'.$coupon->id,
        ]);
        
        $coupon->update([
            'code' => $validated['code'],
            'amount' => $validated['amount'],
            'min_purchase_amount' => $validated['min_purchase_amount'] ?? 0,
            'valid_until' => $validated['valid_until'],
            'user_id' => $validated['user_id'] ?? null,
        ]);
        
        return redirect()->route('admin.coupons.show', $coupon)
            ->with('success', 'Coupon updated successfully.');
    }

    /**
     * Remove the specified coupon from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        
        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }
    
    /**
     * Generate multiple coupons at once.
     */
    public function generateMultiple()
    {
        // Fix: Change 'user' to 'customer'
        $users = User::where('role', 'customer')->get();
        return view('admin.coupons.generate', compact('users'));
    }
    
    /**
     * Store multiple coupons.
     */
    public function storeMultiple(Request $request)
    {
        // Check if it's general coupons
        $isGeneral = $request->has('generate_for_all') && $request->generate_for_all == '1';
        
        $rules = [
            'amount' => 'required|numeric|min:1',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'valid_months' => 'required|integer|min:1',
            'quantity_per_user' => 'required|integer|min:1|max:100',
        ];
        
        // Only require user_ids if not generating general coupons
        if (!$isGeneral) {
            $rules['user_ids'] = 'required|array';
            $rules['user_ids.*'] = 'exists:users,id';
        }
        
        $validated = $request->validate($rules);
        
        // Convert to proper types to avoid Carbon issues
        $validMonths = (int) $validated['valid_months'];
        $amount = (float) $validated['amount'];
        $minPurchaseAmount = isset($validated['min_purchase_amount']) ? (float) $validated['min_purchase_amount'] : 0;
        $quantityPerUser = (int) $validated['quantity_per_user'];
        
        DB::beginTransaction();
        
        try {
            $couponsCreated = 0;
            
            // Check if this is for general coupons (all users)
            if ($isGeneral) {
                // Generate general coupons (not tied to specific users)
                for ($i = 0; $i < $quantityPerUser; $i++) {
                    $code = strtoupper(Str::random(8));
                    
                    while (Coupon::where('code', $code)->exists()) {
                        $code = strtoupper(Str::random(8));
                    }
                    
                    Coupon::create([
                        'code' => $code,
                        'amount' => $amount,
                        'min_purchase_amount' => $minPurchaseAmount,
                        'valid_from' => now(),
                        'valid_until' => now()->addMonths($validMonths),
                        'is_used' => false,
                        'user_id' => null, // General coupon
                        'order_id' => null,
                    ]);
                    
                    $couponsCreated++;
                }
            } else {
                // Generate coupons for specific users
                foreach ($validated['user_ids'] as $userId) {
                    for ($i = 0; $i < $quantityPerUser; $i++) {
                        $code = strtoupper(Str::random(8));
                        
                        while (Coupon::where('code', $code)->exists()) {
                            $code = strtoupper(Str::random(8));
                        }
                        
                        Coupon::create([
                            'code' => $code,
                            'amount' => $amount,
                            'min_purchase_amount' => $minPurchaseAmount,
                            'valid_from' => now(),
                            'valid_until' => now()->addMonths($validMonths),
                            'is_used' => false,
                            'user_id' => $userId,
                            'order_id' => null,
                        ]);
                        
                        $couponsCreated++;
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.coupons.index')
                ->with('success', "$couponsCreated coupons created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to create coupons: ' . $e->getMessage());
        }
    }
}