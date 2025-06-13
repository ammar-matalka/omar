@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="mb-4 section-title" style="color: #a18664;"><i class="fas fa-plus me-2"></i>New Conversation</h1>
    <div class="card shadow-sm">
        <div class="card-header text-white" style="background-color: #a18664;">
            <h5 class="mb-0"><i class="fas fa-envelope me-2"></i>Start a Conversation</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('user.conversations.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Subject</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex">
                    <button type="submit" class="btn me-2" style="background-color: #a18664; color: white;">
                        <i class="fas fa-paper-plane me-2"></i> Send Message
                    </button>
                    <a href="{{ route('user.conversations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #c1a276;
        box-shadow: 0 0 0 0.25rem rgba(161, 134, 100, 0.25);
    }
    
    .btn:hover {
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .3rem 3rem rgba(161, 134, 100, .175) !important;
    }
</style>
@endsection