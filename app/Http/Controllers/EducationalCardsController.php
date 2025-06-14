<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\EducationalCard;
use App\Models\EducationalCardOrder;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EducationalCardsController extends Controller
{
    /**
     * Display the main educational cards page with everything
     */
    public function index()
    {
        $platforms = Platform::where('is_active', true)
            ->with(['grades' => function($query) {
                $query->where('is_active', true)
                    ->orderBy('grade_number')
                    ->with(['subjects' => function($query) {
                        $query->where('is_active', true)
                            ->orderBy('name')
                            ->with(['educationalCards' => function($query) {
                                $query->where('is_active', true)
                                    ->orderBy('title');
                            }]);
                    }]);
            }])
            ->orderBy('name')
            ->get();

        return view('educational-cards.index', compact('platforms'));
    }
    
    /**
     * Show individual educational card details
     */
    public function showCard(EducationalCard $card)
    {
        if (!$card->is_active) {
            abort(404);
        }
        
        $card->load(['subject.grade.platform', 'images']);
        
        // Get related cards from same subject
        $relatedCards = EducationalCard::where('subject_id', $card->subject_id)
                                      ->where('id', '!=', $card->id)
                                      ->where('is_active', true)
                                      ->take(4)
                                      ->get();
        
        return view('educational-cards.show', compact('card', 'relatedCards'));
    }
    
    /**
     * Submit educational card order
     */
    public function submitOrder(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:10',
            'generation' => 'required|string|max:50',
            'subject' => 'required|string|max:100',
            'teacher' => 'required|string|max:100',
            'semester' => 'required|in:first,second,full_year',
            'platform' => 'required|string|max:100',
            'notebook_type' => 'required|in:digital,physical,both',
            'quantity' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Calculate total amount (you can set your own pricing logic)
            $basePrice = 25.00; // Base price per card
            $typeMultiplier = [
                'digital' => 1.0,
                'physical' => 1.2,
                'both' => 1.5
            ];
            
            $totalAmount = $basePrice * $validated['quantity'] * $typeMultiplier[$validated['notebook_type']];

            // Create the order
            $order = EducationalCardOrder::create([
                'user_id' => Auth::id(),
                'academic_year' => $validated['academic_year'],
                'generation' => $validated['generation'],
                'subject' => $validated['subject'],
                'teacher' => $validated['teacher'],
                'semester' => $validated['semester'],
                'platform' => $validated['platform'],
                'notebook_type' => $validated['notebook_type'],
                'quantity' => $validated['quantity'],
                'notes' => $validated['notes'],
                'total_amount' => $totalAmount,
                'status' => 'pending'
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Order submitted successfully!'),
                    'order_id' => $order->id,
                    'total_amount' => $order->formatted_total
                ]);
            }

            return redirect()->route('educational-cards.index')
                ->with('success', __('Order submitted successfully! Order #:id', ['id' => $order->id]));

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error submitting order. Please try again.')
                ], 500);
            }

            return back()->with('error', __('Error submitting order. Please try again.'));
        }
    }
    
    /**
     * Add educational card to cart
     */
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'card_id' => 'required|exists:educational_cards,id',
            'quantity' => 'required|integer|min:1|max:10'
        ]);
        
        $card = EducationalCard::findOrFail($validated['card_id']);
        
        if (!$card->is_active) {
            return back()->with('error', __('This educational card is not available.'));
        }
        
        // Check stock
        if ($card->stock < $validated['quantity']) {
            return back()->with('error', __('Not enough stock available.'));
        }
        
        // Get or create cart using private method
        $cart = $this->getOrCreateCart();
        
        // Check if card is already in cart
        $cartItem = $cart->cartItems()->where('educational_card_id', $card->id)->first();
        
        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $validated['quantity'];
            if ($newQuantity > $card->stock) {
                return back()->with('error', __('Not enough stock available.'));
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cart->cartItems()->create([
                'educational_card_id' => $card->id,
                'quantity' => $validated['quantity'],
                'type' => 'educational_card'
            ]);
        }
        
        return back()->with('success', __('Educational card added to cart successfully!'));
    }
    
    /**
     * Get or create user's cart
     */
    private function getOrCreateCart()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $cart = $user->cart;
        
        if (!$cart) {
            $cart = Cart::create(['user_id' => $user->id]);
        }
        
        return $cart->load('cartItems');
    }
    
    /**
     * Search educational cards
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return redirect()->route('educational-cards.index');
        }
        
        $cards = EducationalCard::with(['subject.grade.platform', 'images'])
                               ->where('is_active', true)
                               ->where(function($q) use ($query) {
                                   $q->where('title', 'LIKE', "%{$query}%")
                                     ->orWhere('description', 'LIKE', "%{$query}%");
                               })
                               ->paginate(12);
                               
        return view('educational-cards.search', compact('cards', 'query'));
    }
}