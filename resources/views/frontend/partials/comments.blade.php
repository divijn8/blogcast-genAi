
@foreach ($comments as $comment)
    <div class="blog-post-comment {{ $comment->parent_id ? 'blog-post-comment-reply' : '' }}">
        <img src="{{ $comment->user->user_profile }}" class="img-circle" alt="image">
        <span class="blog-post-comment-name">{{ $comment->user->name }}</span>
        {{ $comment->created_at->format('M. d Y, h:i A') }}
        <a href="#" class="pull-right text-gray reply-button" data-comment-id="{{ $comment->id }}"><i class="fa fa-comment"></i> Reply</a>

        <p>{{ $comment->content }}</p>

        {{-- Reply form (initially hidden, shown when "Reply" is clicked) --}}
        <div class="reply-form mt-2" id="reply-form-{{ $comment->id }}" style="display: none;">
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="post_id" value="{{ $comment->post_id }}">
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <textarea name="content" class="form-control mb-2" rows="2" placeholder="Write a reply..." required></textarea>
                <button type="submit" class="btn btn-sm btn-primary">Reply</button>
            </form>
        </div>

        {{-- Nested replies --}}
        @if ($comment->replies->count())
            @include('frontend.partials.comments', ['comments' => $comment->replies])
        @endif
    </div>
@endforeach
