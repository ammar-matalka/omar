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
        grid-template-columns: 300px 1fr;
        gap: var(--space-xl);
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