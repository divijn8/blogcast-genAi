@php $level = $level ?? 0; @endphp
@php
    $depthClass = ($level > 0) ? 'depth-1' : 'depth-0';
@endphp

@foreach ($comments as $comment)
    <div class="blog-post-comment {{ $depthClass }} {{ $comment->parent_id ? 'blog-post-comment-reply' : '' }}">
    <img src="{{ $comment->user->user_profile ?? $comment->guest_profile }}"
        class="img-circle mr-2"
        alt="image"
        width="50"
        height="40">
        <span class="blog-post-comment-name">{{ $comment->guest_name ?? $comment->user->name }}</span>
        {{ $comment->created_at->format('M. d Y, h:i A') }}
        <a href="#" class="pull-right text-gray reply-button" data-comment-id="{{ $comment->id }}">
            <i class="fa fa-comment"></i> Reply
        </a>

        <p>{{ $comment->content }}</p>

        {{-- Reply form --}}
        <div class="reply-form mt-2" id="reply-form-{{ $comment->id }}" style="display: none;">
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf

                <input type="hidden" name="commentable_type"
                    value="{{ $comment->commentable instanceof App\Models\Post ? 'post' : 'podcast' }}">

                <input type="hidden" name="commentable_id"
                    value="{{ $comment->commentable->id }}">

                <input type="hidden" name="parent_id" value="{{ $comment->id }}">

                @guest
                    <div class="row" style="margin: 10px">
                        <input type="text" name="guest_name" class="col-md-6 blog-leave-comment-input" placeholder="name">
                        <input type="email" name="guest_email" class="col-md-6 blog-leave-comment-input" placeholder="email">
                    </div>
                @endguest

                <textarea name="comment"
                        class="form-control mb-2"
                        rows="2"
                        placeholder="Write a reply..."
                        required></textarea>

                <button type="submit" class="button button-pasific button-sm center-block mb25">
                    Reply
                </button>
            </form>
        </div>

        {{-- Nested replies --}}
        @if ($level < 1 && $comment->replies->count())
            @include('frontend.partials.comments', [
                'comments' => $comment->replies,
                'level' => $level + 1
            ])
        @elseif ($comment->replies->count())
            {{-- View more replies toggle --}}
            <div class="view-more-replies mt-2">
                <a href="#" class="toggle-replies-link" data-target="#replies-{{ $comment->id }}">
                    View {{ $comment->replies->count() }} more {{ Str::plural('reply', $comment->replies->count()) }}
                </a>
                <div class="replies-hidden mt-2" id="replies-{{ $comment->id }}" style="display: none;">
                    @include('frontend.partials.comments', [
                        'comments' => $comment->replies,
                        'level' => 99 // Prevent further nesting UI
                    ])
                </div>
            </div>
        @endif
    </div>
@endforeach
