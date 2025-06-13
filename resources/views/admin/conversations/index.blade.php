@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Customer Conversations</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Conversations</h6>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($conversations->isEmpty())
                <div class="text-center p-4">
                    <p>No conversations found.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Subject</th>
                                <th>Last Update</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($conversations as $conversation)
                                <tr class="{{ !$conversation->is_read_by_admin ? 'table-warning' : '' }}">
                                    <td>{{ $conversation->id }}</td>
                                    <td>{{ $conversation->user->name }}</td>
                                    <td>{{ $conversation->title }}</td>
                                    <td>{{ $conversation->updated_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if(!$conversation->is_read_by_admin)
                                            <span class="badge badge-warning">New Message</span>
                                        @else
                                            <span class="badge badge-success">Read</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.conversations.show', $conversation) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $conversations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection