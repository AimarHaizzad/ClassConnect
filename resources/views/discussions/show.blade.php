@extends('layouts.app')

@section('title', $discussion->title)

@section('content')
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('discussions.index') }}" style="color: #795E2E; text-decoration: none; margin-bottom: 8px; display: inline-block;">
                ← Back to Discussions
            </a>
            <div style="color: #666; font-size: 14px;">
                Subject: <strong style="color: #795E2E;">{{ $discussion->subject->name }}</strong>
            </div>
        </div>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Discussion Post -->
        <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                <div style="flex: 1;">
                    <h1 style="color: #333; margin: 0 0 12px 0; font-size: 28px;">{{ $discussion->title }}</h1>
                    <div style="display: flex; gap: 20px; color: #999; font-size: 14px; margin-bottom: 20px;">
                        <span>👤 {{ $discussion->user->name ?? 'Anonymous' }}</span>
                        <span>📅 {{ $discussion->created_at->format('F d, Y \a\t g:i A') }}</span>
                    </div>
                </div>
                @php
                    $currentUserId = auth()->id() ?? \App\Models\User::first()->id ?? 1;
                @endphp
                @if($discussion->user_id == $currentUserId)
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('discussions.edit', $discussion) }}" style="background: #795E2E; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 500;">
                            ✏️ Edit
                        </a>
                        <form action="{{ route('discussions.destroy', $discussion) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this discussion? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer;">
                                🗑️ Delete
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            <div style="color: #333; line-height: 1.8; font-size: 16px;">
                {!! nl2br(e($discussion->content)) !!}
            </div>
            @if($discussion->image)
                <div style="margin-top: 20px;">
                    <img src="{{ asset('storage/' . $discussion->image) }}" alt="Discussion Image" style="max-width: 100%; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                </div>
            @endif
        </div>

        <!-- Comments Section -->
        <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 30px;">
            <h2 style="color: #333; margin: 0 0 24px 0; font-size: 22px;">
                Comments ({{ $discussion->comments->count() }})
            </h2>

            <!-- Add Comment Form -->
            @if($errors->any())
                <div style="background: #fff3cd; border: 2px solid #ffc107; color: #856404; padding: 16px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <span style="font-size: 24px;">⚠️</span>
                        <strong style="font-size: 16px;">Content Warning</strong>
                    </div>
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li style="margin-bottom: 4px;">{{ $error }}</li>
                        @endforeach
                    </ul>
                    <p style="margin: 12px 0 0 0; font-size: 14px; font-style: italic;">
                        Please review your comment and remove any inappropriate language before submitting.
                    </p>
                </div>
            @endif

            <form action="{{ route('comments.store') }}" method="POST" style="margin-bottom: 30px;">
                @csrf
                <input type="hidden" name="discussion_id" value="{{ $discussion->id }}">
                <div style="margin-bottom: 12px;">
                    <textarea 
                        name="content" 
                        rows="4"
                        required
                        style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 16px; resize: vertical;"
                        placeholder="Write your comment here...">{{ old('content') }}</textarea>
                </div>
                <button 
                    type="submit"
                    style="background: #795E2E; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;"
                >
                    Post Comment
                </button>
            </form>

            <!-- Comments List -->
            @if($discussion->comments->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    @foreach($discussion->comments as $comment)
                        <div style="padding: 20px; background: #f9f9f9; border-radius: 8px; border-left: 4px solid #795E2E;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                                <div>
                                    <strong style="color: #795E2E; font-size: 16px;">
                                        {{ $comment->user->name ?? 'Anonymous' }}
                                    </strong>
                                    <span style="color: #999; font-size: 14px; margin-left: 12px;">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            <div style="color: #333; line-height: 1.6;">
                                {!! nl2br(e($comment->content)) !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: #999; text-align: center; padding: 20px;">
                    No comments yet. Be the first to comment!
                </p>
            @endif
        </div>
    </div>
@endsection

