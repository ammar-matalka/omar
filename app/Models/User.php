<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $profile_image
 * @property-read \App\Models\Cart|null $cart
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wishlist[] $wishlist
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Coupon[] $coupons
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Testimonial[] $testimonials
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Conversation[] $conversations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $messages
 * @method bool isAdmin()
 * @method bool isCustomer()
 * @method bool hasVerifiedEmail()
 * @method bool hasInWishlist($productId)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'profile_image',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get the user's orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the user's cart
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Get the user's conversations
     */
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    /**
     * Get the user's testimonials
     */
    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }
    
    /**
     * Get the user's wishlist
     */
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the user's coupons
     */
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    /**
     * Get the user's active coupons
     */
    public function activeCoupons()
    {
        return $this->hasMany(Coupon::class)
            ->where('is_used', false)
            ->where('valid_until', '>=', now());
    }

    /**
     * Get the user's sent messages
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is customer
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Check if email is verified
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Check if a product is in the user's wishlist
     */
    public function hasInWishlist($productId): bool
    {
        return $this->wishlist()->where('product_id', $productId)->exists();
    }

    /**
     * Get cart items count
     */
    public function getCartItemsCountAttribute(): int
    {
        return $this->cart ? $this->cart->cartItems->sum('quantity') : 0;
    }

    /**
     * Get wishlist items count
     */
    public function getWishlistItemsCountAttribute(): int
    {
        return $this->wishlist()->count();
    }

    /**
     * Get total orders count
     */
    public function getTotalOrdersAttribute(): int
    {
        return $this->orders()->count();
    }

    /**
     * Get total spent amount
     */
    public function getTotalSpentAttribute(): float
    {
        return $this->orders()->where('status', 'delivered')->sum('total_amount');
    }

    /**
     * Get user's full address
     */
    public function getFullAddressAttribute(): string
    {
        return $this->address ?: 'No address provided';
    }

    /**
     * Get user's profile image URL
     */
    public function getProfileImageUrlAttribute(): string
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        
        // Default avatar based on user's initials
        $initials = strtoupper(substr($this->name, 0, 1));
        return "https://ui-avatars.com/api/?name={$initials}&background=random&size=150";
    }

    /**
     * Get user's initials
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        
        return substr($initials, 0, 2); // Max 2 letters
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope for admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope for customer users
     */
    public function scopeCustomers($query)
    {
        return $query->where('role', 'customer');
    }

    /**
     * Scope for active users (with orders)
     */
    public function scopeActive($query)
    {
        return $query->whereHas('orders');
    }

    /**
     * Scope for users with recent activity
     */
    public function scopeRecentlyActive($query, $days = 30)
    {
        return $query->where('updated_at', '>=', now()->subDays($days));
    }
}