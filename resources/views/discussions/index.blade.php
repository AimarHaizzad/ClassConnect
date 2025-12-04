@extends('layouts.app')

@section('title', 'Discussions')

@section('content')
    <div style="margin-bottom: 20px;">
        <form action="{{ route('subjects.clear') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" style="background: none; border: none; color: #795E2E; text-decoration: none; cursor: pointer; padding: 0; font-size: inherit;">
                ← Back to Select Subject
            </button>
        </form>
    </div>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h1 style="color: #333; margin: 0 0 8px 0;">Discussions</h1>
            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="color: #795E2E; font-weight: 600; font-size: 16px;">📚 {{ $subject->name }}</span>
                <a href="{{ route('subjects.select') }}" style="color: #666; font-size: 14px; text-decoration: none;">
                    (Change Subject)
                </a>
            </div>
        </div>
        <a href="{{ route('discussions.create') }}" style="background: #795E2E; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: background 0.3s;">
            + New Discussion
        </a>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if($discussions->count() > 0)
        <div style="display: flex; flex-direction: column; gap: 20px;">
            @foreach($discussions as $discussion)
                <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: box-shadow 0.3s;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                        <div style="flex: 1;">
                            <h2 style="color: #333; margin: 0 0 8px 0; font-size: 20px;">
                                <a href="{{ route('discussions.show', $discussion) }}" style="color: #795E2E; text-decoration: none;">
                                    {{ $discussion->title }}
                                </a>
                            </h2>
                            <p style="color: #666; margin: 0 0 12px 0; line-height: 1.6;">
                                {{ Str::limit($discussion->content, 150) }}
                            </p>
                            @if($discussion->image)
                                <div style="margin: 12px 0;">
                                    <img src="{{ asset('storage/' . $discussion->image) }}" alt="Discussion Image" style="max-width: 200px; max-height: 150px; border-radius: 8px; object-fit: cover;">
                                </div>
                            @endif
                            <div style="display: flex; gap: 20px; color: #999; font-size: 14px; margin-bottom: 12px;">
                                <span>👤 {{ $discussion->user->name ?? 'Anonymous' }}</span>
                                <span>💬 {{ $discussion->comments->count() }} {{ Str::plural('comment', $discussion->comments->count()) }}</span>
                                <span>📅 {{ $discussion->created_at->diffForHumans() }}</span>
                            </div>
                            @php
                                $currentUserId = auth()->id() ?? \App\Models\User::first()->id ?? 1;
                            @endphp
                            @if($discussion->user_id == $currentUserId)
                                <div style="display: flex; gap: 8px; margin-top: 12px;">
                                    <a href="{{ route('discussions.edit', $discussion) }}" style="background: #795E2E; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('discussions.destroy', $discussion) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this discussion? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: #dc3545; color: white; padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer;">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 30px;">
            {{ $discussions->links() }}
        </div>
    @else
        <div style="background: white; padding: 40px; border-radius: 12px; text-align: center;">
            <p style="color: #666; font-size: 18px; margin-bottom: 20px;">No discussions yet. Be the first to start one!</p>
            <a href="{{ route('discussions.create') }}" style="background: #795E2E; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                Create First Discussion
            </a>
        </div>
    @endif
@endsection
