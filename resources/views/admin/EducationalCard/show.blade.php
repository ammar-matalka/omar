@extends('layouts.admin')

@section('title', __('Educational Card Details'))
@section('page-title', __('Educational Card Details'))

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">{{ __('Dashboard') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('admin.educational-cards.index') }}" class="breadcrumb-link">{{ __('Educational Cards') }}</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-right"></i>
        {{ $card->title }}
    </div>
@endsection

@push('styles')
<style>
    .card-show-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .card-header {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: var(--space-xl);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .card-hero {
        display: grid;
        grid-template-columns: 400px 1fr;
        gap: var(--space-2xl);
        padding: var(--space-2xl);
        align-items: start;
    }
    
    .card-image-section {
        position: sticky;
        top: var(--space-lg);
    }
    
    .card-images {
        display: flex;
        flex-direction: column;
        gap: var(--space-md);
    }
    
    .primary-image-container {
        position: relative;
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
        box-shadow: var(--shadow-md);
        aspect-ratio: 4/3;
    }
    
    .primary-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .no-primary-image {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
    }
    
    .card-status {
        position: absolute;
        top: var(--space-sm);
        right: var(--space-sm);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-active {
        background: var(--success-500);
        color: white;
    }
    
    .status-inactive {
        background: var(--error-500);
        color: white;
    }
    
    .card-type-badge {
        position: absolute;
        top: var(--space-sm);
        left: var(--space-sm);
        padding: var(--space-xs) var(--space-sm);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        background: var(--admin-primary-500);
        color: white;
    }
    
    .thumbnail-images {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(60px, 1fr));
        gap: var(--space-sm);
    }
    
    .thumbnail-item {
        aspect-ratio: 1;
        border-radius: var(--radius-md);
        overflow: hidden;
        border: 2px solid var(--admin-secondary-200);
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .thumbnail-item:hover {
        border-color: var(--admin-primary-500);
        transform: scale(1.05);
    }
    
    .thumbnail-item.active {
        border-color: var(--admin-primary-500);
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
    }
    
    .thumbnail-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .card-info {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }
    
    .card-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--admin-secondary-900);
        margin: 0;
        line-height: 1.2;
    }
    
    .card-title-ar {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--admin-secondary-700);
        margin: var(--space-sm) 0 0 0;
        direction: rtl;
        font-style: italic;
    }
    
    .card-hierarchy {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        flex-wrap: wrap;
    }
    
    .hierarchy-item {
        background: var(--admin-secondary-100);
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        text-decoration: none;
        color: var(--admin-secondary-700);
        transition: all var(--transition-fast);
        font-weight: 500;
    }
    
    .hierarchy-item:hover {
        background: var(--admin-primary-100);
        color: var(--admin-primary-700);
        transform: translateY(-1px);
    }
    
    .hierarchy-separator {
        color: var(--admin-secondary-400);
        font-size: 1.2rem;
    }
    
    .difficulty-badge {
        display: inline-block;
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .difficulty-easy {
        background: var(--success-100);
        color: var(--success-700);
        border: 1px solid var(--success-200);
    }
    
    .difficulty-medium {
        background: var(--warning-100);
        color: var(--warning-700);
        border: 1px solid var(--warning-200);
    }
    
    .difficulty-hard {
        background: var(--error-100);
        color: var(--error-700);
        border: 1px solid var(--error-200);
    }
    
    .card-description {
        font-size: 1rem;
        line-height: 1.6;
        color: var(--admin-secondary-700);
        background: var(--admin-secondary-50);
        padding: var(--space-lg);
        border-radius: var(--radius-lg);
        border-left: 4px solid var(--admin-primary-500);
    }
    
    .card-description-ar {
        font-size: 1rem;
        line-height: 1.8;
        color: var(--admin-secondary-700);
        background: var(--admin-secondary-50);
        padding: var(--space-lg);
        border-radius: var(--radius-lg);
        border-right: 4px solid var(--admin-primary-500);
        direction: rtl;
        font-style: italic;
    }
    
    .card-actions {
        display: flex;
        gap: var(--space-md);
        padding: var(--space-xl);
        background: var(--admin-secondary-50);
        border-top: 1px solid var(--admin-secondary-200);
    }
    
    .action-btn {
        padding: var(--space-md) var(--space-lg);
        border: none;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all var(--transition-fast);
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .action-btn.edit {
        background: var(--warning-500);
        color: white;
    }
    
    .action-btn.edit:hover {
        background: var(--warning-600);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .action-btn.delete {
        background: var(--error-500);
        color: white;
    }
    
    .action-btn.delete:hover {
        background: var(--error-600);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .action-btn.back {
        background: var(--admin-secondary-500);
        color: white;
    }
    
    .action-btn.back:hover {
        background: var(--admin-secondary-600);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .card-details {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: var(--space-xl);
        border: 1px solid var(--admin-secondary-200);
    }
    
    .details-header {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        color: white;
        padding: var(--space-lg) var(--space-xl);
    }
    
    .details-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-xl);
        padding: var(--space-xl);
    }
    
    .detail-item {
        text-align: center;
        padding: var(--space-lg);
        border-radius: var(--radius-lg);
        background: var(--admin-secondary-50);
        border: 1px solid var(--admin-secondary-200);
        transition: all var(--transition-fast);
    }
    
    .detail-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .detail-icon {
        font-size: 2rem;
        margin-bottom: var(--space-sm);
        color: var(--admin-primary-500);
    }
    
    .detail-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        margin-bottom: var(--space-xs);
    }
    
    .detail-label {
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
    }
    
    .timestamps-section {
        background: white;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-200);
    }
    
    .timestamps-header {
        background: linear-gradient(135deg, var(--admin-secondary-500), var(--admin-secondary-600));
        color: white;
        padding: var(--space-lg) var(--space-xl);
    }
    
    .timestamps-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-xl);
        padding: var(--space-xl);
    }
    
    .timestamp-item {
        text-align: center;
    }
    
    .timestamp-value {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--admin-secondary-800);
        margin-bottom: var(--space-xs);
    }
    
    .timestamp-label {
        font-size: 0.875rem;
        color: var(--admin-secondary-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    @media (max-width: 768px) {
        .card-show-container {
            margin: 0;
        }
        
        .card-hero {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .card-image-section {
            position: static;
        }
        
        .details-grid {
            grid-template-columns: 1fr;
        }
        
        .timestamps-grid {
            grid-template-columns: 1fr;
        }
        
        .card-actions {
            flex-direction: column;
        }
        
        .card-hierarchy {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="card-show-container">
    <!-- Card Header -->
    <div class="card-header fade-in">
        <div class="card-hero">
            <!-- Image Section -->
            <div class="card-image-section">
                <div class="card-images">
                    <div class="primary-image-container">
                        @if($card->images && $card->images->where('is_primary', true)->first())
                            @php $primaryImage = $card->images->where('is_primary', true)->first(); @endphp
                            <img 
                                id="primaryImage" 
                                src="{{ Storage::url($primaryImage->image_path) }}" 
                                alt="{{ $card->title }}" 
                                class="primary-image"
                            >
                        @elseif($card->images && $card->images->first())
                            <img 
                                id="primaryImage" 
                                src="{{ Storage::url($card->images->first()->image_path) }}" 
                                alt="{{ $card->title }}" 
                                class="primary-image"
                            >
                        @else
                            <div class="no-primary-image">
                                <i class="fas fa-id-card"></i>
                            </div>
                        @endif
                        
                        <div class="card-status status-{{ $card->is_active ? 'active' : 'inactive' }}">
                            {{ $card->is_active ? __('Active') : __('Inactive') }}
                        </div>
                        
                        <div class="card-type-badge">
                            {{ ucfirst($card->card_type) }}
                        </div>
                    </div>
                    
                    @if($card->images && $card->images->count() > 1)
                        <div class="thumbnail-images">
                            @foreach($card->images->sortBy('sort_order') as $index => $image)
                                <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}" onclick="changeImage('{{ Storage::url($image->image_path) }}', this)">
                                    <img src="{{ Storage::url($image->image_path) }}" alt="{{ $card->title }}" class="thumbnail-image">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Info Section -->
            <div class="card-info">
                <div>
                    <h1 class="card-title">{{ $card->title }}</h1>
                    @if($card->title_ar)
                        <div class="card-title-ar">{{ $card->title_ar }}</div>
                    @endif
                </div>
                
                <div class="card-hierarchy">
                    <a href="{{ route('admin.platforms.show', $card->subject->grade->platform) }}" class="hierarchy-item">
                        <i class="fas fa-desktop"></i>
                        {{ $card->subject->grade->platform->name }}
                    </a>
                    
                    <span class="hierarchy-separator">›</span>
                    
                    <a href="{{ route('admin.grades.show', $card->subject->grade) }}" class="hierarchy-item">
                        <i class="fas fa-graduation-cap"></i>
                        {{ $card->subject->grade->name }}
                    </a>
                    
                    <span class="hierarchy-separator">›</span>
                    
                    <a href="{{ route('admin.subjects.show', $card->subject) }}" class="hierarchy-item">
                        <i class="fas fa-book"></i>
                        {{ $card->subject->name }}
                    </a>
                </div>
                
                <div>
                    <div class="difficulty-badge difficulty-{{ $card->difficulty_level }}">
                        <i class="fas fa-signal"></i>
                        {{ ucfirst($card->difficulty_level) }} {{ __('Level') }}
                    </div>
                </div>
                
                @if($card->description)
                    <div class="card-description">
                        <strong>{{ __('Description') }}:</strong><br>
                        {{ $card->description }}
                    </div>
                @endif
                
                @if($card->description_ar)
                    <div class="card-description-ar">
                        <strong>{{ __('Arabic Description') }}:</strong><br>
                        {{ $card->description_ar }}
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card-actions">
            <a href="{{ route('admin.educational-cards.index') }}" class="action-btn back">
                <i class="fas fa-arrow-left"></i>
                {{ __('Back to Cards') }}
            </a>
            
            <div style="margin-left: auto; display: flex; gap: var(--space-md);">
                <a href="{{ route('admin.educational-cards.edit', $card) }}" class="action-btn edit">
                    <i class="fas fa-edit"></i>
                    {{ __('Edit Card') }}
                </a>
                
                <button class="action-btn delete" onclick="deleteCard('{{ $card->id }}')">
                    <i class="fas fa-trash"></i>
                    {{ __('Delete Card') }}
                </button>
            </div>
        </div>
    </div>
    
    <!-- Card Details -->
    <div class="card-details fade-in">
        <div class="details-header">
            <h2 class="details-title">
                <i class="fas fa-info-circle"></i>
                {{ __('Card Details') }}
            </h2>
        </div>
        
        <div class="details-grid">
            <div class="detail-item">
                <div class="detail-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="detail-value">${{ number_format($card->price, 2) }}</div>
                <div class="detail-label">{{ __('Price') }}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="detail-value">{{ $card->stock }}</div>
                <div class="detail-label">{{ __('Stock Quantity') }}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="detail-value">{{ $card->orders_count ?? 0 }}</div>
                <div class="detail-label">{{ __('Total Orders') }}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-icon">
                    <i class="fas fa-images"></i>
                </div>
                <div class="detail-value">{{ $card->images ? $card->images->count() : 0 }}</div>
                <div class="detail-label">{{ __('Images') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Timestamps -->
    <div class="timestamps-section fade-in">
        <div class="timestamps-header">
            <h2 class="details-title">
                <i class="fas fa-clock"></i>
                {{ __('Timeline') }}
            </h2>
        </div>
        
        <div class="timestamps-grid">
            <div class="timestamp-item">
                <div class="timestamp-value">
                    {{ $card->created_at->format('M d, Y \a\t g:i A') }}
                </div>
                <div class="timestamp-label">{{ __('Created') }}</div>
            </div>
            
            <div class="timestamp-item">
                <div class="timestamp-value">
                    {{ $card->updated_at->format('M d, Y \a\t g:i A') }}
                </div>
                <div class="timestamp-label">{{ __('Last Modified') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: var(--space-xl); border-radius: var(--radius-xl); max-width: 400px; width: 90%; text-align: center;">
        <div style="color: var(--error-500); font-size: 3rem; margin-bottom: var(--space-lg);">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 style="margin-bottom: var(--space-md); color: var(--admin-secondary-900);">{{ __('Delete Educational Card') }}</h3>
        <p style="margin-bottom: var(--space-xl); color: var(--admin-secondary-600);">
            {{ __('Are you sure you want to delete this educational card? This action cannot be undone.') }}
        </p>
        <div style="display: flex; gap: var(--space-md); justify-content: center;">
            <button onclick="closeDeleteModal()" class="btn btn-secondary">{{ __('Cancel') }}</button>
            <button onclick="confirmDelete()" class="btn btn-danger">{{ __('Delete') }}</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let cardToDelete = null;
    
    // Change primary image
    function changeImage(imageSrc, thumbnailElement) {
        const primaryImage = document.getElementById('primaryImage');
        if (primaryImage) {
            primaryImage.src = imageSrc;
        }
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnail-item').forEach(item => {
            item.classList.remove('active');
        });
        thumbnailElement.classList.add('active');
    }
    
    // Delete card functions
    function deleteCard(cardId) {
        cardToDelete = cardId;
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        cardToDelete = null;
    }
    
    function confirmDelete() {
        if (cardToDelete) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/educational-cards/${cardToDelete}`;
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '{{ csrf_token() }}';
            
            form.appendChild(methodInput);
            form.appendChild(tokenInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
    
    // Initialize animations
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.fade-in').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });
    });
</script>
@endpush