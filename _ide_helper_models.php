<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartItem> $cartItems
 * @property-read int|null $cart_items_count
 * @property-read string $formatted_total
 * @property-read array $items_by_type
 * @property-read float $total_weight
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart empty()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart withItemType($type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart withItems()
 */
	class Cart extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $cart_id
 * @property int $product_id
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cart $cart
 * @property-read mixed $item_image
 * @property-read mixed $item_name
 * @property-read mixed $item_price
 * @property-read mixed $subtotal
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereUpdatedAt($value)
 */
	class CartItem extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $image_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $title
 * @property bool $is_read_by_user
 * @property bool $is_read_by_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $status
 * @property-read \App\Models\Message|null $lastMessage
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation unreadByAdmin()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation unreadByUser()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereIsReadByAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereIsReadByUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereUserId($value)
 */
	class Conversation extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $code
 * @property string $amount
 * @property string $min_purchase_amount
 * @property \Illuminate\Support\Carbon|null $valid_from
 * @property \Illuminate\Support\Carbon|null $valid_until
 * @property bool $is_used
 * @property int|null $user_id
 * @property int|null $order_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereIsUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereMinPurchaseAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereValidFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereValidUntil($value)
 */
	class Coupon extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \App\Models\Generation|null $generation
 * @property-read mixed $card_info
 * @property-read mixed $days_until_expiration
 * @property-read mixed $is_expired
 * @property-read mixed $is_usable
 * @property-read mixed $status_class
 * @property-read mixed $status_display
 * @property-read mixed $time_until_expiration
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\OrderItem|null $orderItem
 * @property-read \App\Models\EducationalPackage|null $package
 * @property-read \App\Models\Platform|null $platform
 * @property-read \App\Models\User|null $purchaser
 * @property-read \App\Models\Subject|null $subject
 * @property-read \App\Models\Teacher|null $teacher
 * @property-read \App\Models\User|null $usedByStudent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalCard active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalCard byGeneration($generationId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalCard byPlatform($platformId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalCard bySubject($subjectId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalCard byTeacher($teacherId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalCard cancelled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalCard expired()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalCard query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalCard used()
 */
	class EducationalCard extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \App\Models\Generation|null $generation
 * @property-read mixed $actual_available
 * @property-read mixed $product_info
 * @property-read mixed $stock_status
 * @property-read mixed $stock_status_class
 * @property-read mixed $total_quantity
 * @property-read \App\Models\EducationalPackage|null $package
 * @property-read \App\Models\Platform|null $platform
 * @property-read \App\Models\Subject|null $subject
 * @property-read \App\Models\Teacher|null $teacher
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalInventory available()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalInventory byGeneration($generationId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalInventory byPackage($packageId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalInventory byPlatform($platformId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalInventory bySubject($subjectId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalInventory byTeacher($teacherId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalInventory forSelection($generationId, $subjectId, $teacherId, $platformId, $packageId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalInventory lowStock($threshold = 10)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalInventory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalInventory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalInventory outOfStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalInventory query()
 */
	class EducationalInventory extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalCard> $educationalCards
 * @property-read int|null $educational_cards_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalInventory> $educationalInventory
 * @property-read int|null $educational_inventory_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalPricing> $educationalPricing
 * @property-read int|null $educational_pricing_count
 * @property-read mixed $duration_display
 * @property-read mixed $full_info
 * @property-read mixed $is_digital
 * @property-read mixed $lessons_display
 * @property-read mixed $package_type
 * @property-read mixed $pages_display
 * @property-read mixed $requires_shipping
 * @property-read mixed $weight_display
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \App\Models\Platform|null $platform
 * @property-read \App\Models\EducationalProductType|null $productType
 * @property-read \App\Models\Subject|null $subject
 * @property-read \App\Models\Teacher|null $teacher
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPackage active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPackage byPlatform($platformId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPackage byProductType($productTypeId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPackage digital()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPackage physical()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPackage query()
 */
	class EducationalPackage extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \App\Models\Generation|null $generation
 * @property-read mixed $formatted_price
 * @property-read mixed $formatted_shipping_cost
 * @property-read mixed $formatted_total_cost
 * @property-read mixed $is_digital
 * @property-read mixed $product_info
 * @property-read mixed $shipping_cost
 * @property-read mixed $total_cost
 * @property-read \App\Models\EducationalPackage|null $package
 * @property-read \App\Models\Platform|null $platform
 * @property-read \App\Models\ShippingRegion|null $region
 * @property-read \App\Models\Subject|null $subject
 * @property-read \App\Models\Teacher|null $teacher
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPricing active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPricing byGeneration($generationId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPricing byPackage($packageId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPricing byPlatform($platformId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPricing byRegion($regionId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPricing bySubject($subjectId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPricing byTeacher($teacherId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPricing digital()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPricing forSelection($generationId, $subjectId, $teacherId, $platformId, $packageId, $regionId = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPricing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPricing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPricing physical()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalPricing query()
 */
	class EducationalPricing extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalPackage> $activeEducationalPackages
 * @property-read int|null $active_educational_packages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalPackage> $educationalPackages
 * @property-read int|null $educational_packages_count
 * @property-read mixed $display_type
 * @property-read mixed $full_description
 * @property-read mixed $shipping_status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalProductType digital()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalProductType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalProductType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalProductType noShipping()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalProductType physical()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalProductType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalProductType requiresShipping()
 */
	class EducationalProductType extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \App\Models\User|null $customer
 * @property-read mixed $days_since_order
 * @property-read mixed $estimated_delivery
 * @property-read mixed $status_class
 * @property-read mixed $status_display
 * @property-read mixed $timeline
 * @property-read mixed $tracking_url
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\OrderItem|null $orderItem
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalShipment byStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalShipment delivered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalShipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalShipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalShipment preparing()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalShipment printed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalShipment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalShipment returned()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalShipment shipped()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalShipment withTracking()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EducationalShipment withoutTracking()
 */
	class EducationalShipment extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subject> $activeSubjects
 * @property-read int|null $active_subjects_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalCard> $educationalCards
 * @property-read int|null $educational_cards_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalInventory> $educationalInventory
 * @property-read int|null $educational_inventory_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalPricing> $educationalPricing
 * @property-read int|null $educational_pricing_count
 * @property-read mixed $display_name
 * @property-read mixed $grade_level
 * @property-read mixed $student_age
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subject> $subjects
 * @property-read int|null $subjects_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation byYearRange($startYear, $endYear)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation query()
 */
	class Generation extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $conversation_id
 * @property int $user_id
 * @property string $message
 * @property int $is_from_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Conversation $conversation
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereConversationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereIsFromAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUserId($value)
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property numeric $total_amount
 * @property numeric $discount_amount
 * @property array<array-key, mixed>|null $coupon_details
 * @property string $status
 * @property string $payment_method
 * @property string $shipping_address
 * @property string $billing_address
 * @property string $phone_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Coupon|null $appliedCoupon
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Coupon> $coupons
 * @property-read int|null $coupons_count
 * @property-read mixed $coupon_code
 * @property-read mixed $coupon_discount
 * @property-read mixed $formatted_status
 * @property-read mixed $order_summary
 * @property-read mixed $original_amount
 * @property-read mixed $total_quantity
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \App\Models\Testimonial|null $testimonial
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBillingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCouponDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property int|null $product_id
 * @property int $quantity
 * @property numeric $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalCard> $educationalCards
 * @property-read int|null $educational_cards_count
 * @property-read \App\Models\EducationalShipment|null $educationalShipment
 * @property-read \App\Models\Generation|null $generation
 * @property-read mixed $display_image
 * @property-read mixed $display_name
 * @property-read mixed $educational_cards
 * @property-read mixed $educational_delivery_status
 * @property-read mixed $educational_details
 * @property-read mixed $educational_display_image
 * @property-read mixed $educational_display_name
 * @property-read mixed $educational_shipment_status
 * @property-read string $formatted_price
 * @property-read string $formatted_shipping_cost
 * @property-read string $formatted_total
 * @property-read mixed $item_type
 * @property-read mixed $shipping_info
 * @property-read mixed $total
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\EducationalPackage|null $package
 * @property-read \App\Models\Platform|null $platform
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\ShippingRegion|null $region
 * @property-read \App\Models\Subject|null $subject
 * @property-read \App\Models\Teacher|null $teacher
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem digitalEducational()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem educational()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem physicalEducational()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem regular()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUpdatedAt($value)
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalPackage> $activeEducationalPackages
 * @property-read int|null $active_educational_packages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalCard> $educationalCards
 * @property-read int|null $educational_cards_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalInventory> $educationalInventory
 * @property-read int|null $educational_inventory_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalPackage> $educationalPackages
 * @property-read int|null $educational_packages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalPricing> $educationalPricing
 * @property-read int|null $educational_pricing_count
 * @property-read \App\Models\Generation|null $generation
 * @property-read mixed $active_packages_count
 * @property-read mixed $formatted_website_url
 * @property-read mixed $full_info
 * @property-read mixed $packages_count
 * @property-read mixed $teaching_chain
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \App\Models\Subject|null $subject
 * @property-read \App\Models\Teacher|null $teacher
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Platform active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Platform byTeacher($teacherId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Platform newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Platform newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Platform query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Platform withActivePackages()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Platform withPackages()
 */
	class Platform extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property numeric $price
 * @property bool $featured
 * @property int $stock
 * @property string|null $image
 * @property bool $is_active
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartItem> $cartItems
 * @property-read int|null $cart_items_count
 * @property-read \App\Models\Category $category
 * @property-read mixed $main_image
 * @property-read mixed $main_image_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductImage> $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \App\Models\ProductImage|null $primaryImage
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $wishlistUsers
 * @property-read int|null $wishlist_users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property string $image_path
 * @property int $is_primary
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereUpdatedAt($value)
 */
	class ProductImage extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalPricing> $educationalPricing
 * @property-read int|null $educational_pricing_count
 * @property-read mixed $formatted_shipping_cost
 * @property-read mixed $full_info
 * @property-read mixed $orders_count
 * @property-read mixed $shipping_status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingRegion active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingRegion freeShipping()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingRegion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingRegion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingRegion paidShipping()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingRegion query()
 */
	class ShippingRegion extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $activeTeachers
 * @property-read int|null $active_teachers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalCard> $educationalCards
 * @property-read int|null $educational_cards_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalInventory> $educationalInventory
 * @property-read int|null $educational_inventory_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalPricing> $educationalPricing
 * @property-read int|null $educational_pricing_count
 * @property-read \App\Models\Generation|null $generation
 * @property-read mixed $full_name
 * @property-read int|null $teachers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $teachers
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject byGeneration($generationId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject withActiveTeachers()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject withTeachers()
 */
	class Subject extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Platform> $activePlatforms
 * @property-read int|null $active_platforms_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalCard> $educationalCards
 * @property-read int|null $educational_cards_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalInventory> $educationalInventory
 * @property-read int|null $educational_inventory_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EducationalPricing> $educationalPricing
 * @property-read int|null $educational_pricing_count
 * @property-read \App\Models\Generation|null $generation
 * @property-read mixed $full_info
 * @property-read mixed $image_url
 * @property-read int|null $platforms_count
 * @property-read mixed $teaching_info
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Platform> $platforms
 * @property-read \App\Models\Subject|null $subject
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher bySubject($subjectId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher withActivePlatforms()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teacher withPlatforms()
 */
	class Teacher extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $order_id
 * @property string $comment
 * @property int $rating
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereUserId($value)
 */
	class Testimonial extends \Eloquent {}
}

namespace App\Models{
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
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $email_verification_token
 * @property string|null $email_verification_sent_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_admin
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Coupon> $activeCoupons
 * @property-read int|null $active_coupons_count
 * @property-read int|null $conversations_count
 * @property-read int|null $coupons_count
 * @property-read int $cart_items_count
 * @property-read string $full_address
 * @property-read string $initials
 * @property-read string $profile_image_url
 * @property-read int $total_orders
 * @property-read float $total_spent
 * @property-read int $wishlist_items_count
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read int|null $orders_count
 * @property-read int|null $testimonials_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read int|null $wishlist_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User admins()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User customers()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User recentlyActive($days = 30)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerificationSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerificationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfileImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereUserId($value)
 */
	class Wishlist extends \Eloquent {}
}

