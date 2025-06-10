<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\EducationalCard;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationalCardsController extends Controller
{
    /**
     * Display the main educational cards page with platforms
     */
    public function index()
    {
        $platforms = Platform::where('is_active', true)->orderBy('name')->get();
        return view('educational-cards.index', compact('platforms'));
    }
    
    /**
     * Show grades for selected platform
     */
    public function showGrades(Platform $platform)
    {
        $grades = Grade::where('platform_id', $platform->id)
                      ->where('is_active', true)
                      ->orderBy('grade_number')
                      ->get();
                      
        return view('educational-cards.grades', compact('platform', 'grades'));
    }
    
    /**
     * Show subjects for selected platform and grade
     */
    public function showSubjects(Platform $platform, Grade $grade)
    {
        // Ensure grade belongs to platform
        if ($grade->platform_id !== $platform->id) {
            abort(404);
        }
        
        $subjects = Subject::where('grade_id', $grade->id)
                          ->where('is_active', true)
                          ->orderBy('name')
                          ->get();
                          
        return view('educational-cards.subjects', compact('platform', 'grade', 'subjects'));
    }
    
    /**
     * Show educational cards for selected subject
     */
    public function showCards(Platform $platform, Grade $grade, Subject $subject)
    {
        // Ensure relationships are correct
        if ($grade->platform_id !== $platform->id || $subject->grade_id !== $grade->id) {
            abort(404);
        }
        
        $cards = EducationalCard::where('subject_id', $subject->id)
                               ->where('is_active', true)
                               ->orderBy('title')
                               ->paginate(12);
                               
        return view('educational-cards.cards', compact('platform', 'grade', 'subject', 'cards'));
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