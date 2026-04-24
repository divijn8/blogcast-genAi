@extends('frontend.layouts.app')

@php
    $postUrl = urlencode(route('frontend.show', $post->slug));
    $postTitle = urlencode($post->title);
@endphp


@section('main-content')
    <div class="container" style="max-width: 850px; margin: auto;">

        {{-- TITLE --}}
        <h1 style="font-weight:700; font-size:34px; margin-bottom:10px;">
            {{ $post->title }}
        </h1>

        {{-- META BAR --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:10px;">

            <div style="display:flex; align-items:center; gap:10px;">
                <img src="{{ $post->author->user_profile }}"
                    style="width:40px; height:40px; border-radius:50%;">

                <div>
                    <div style="font-weight:600;">{{ $post->author->name }}</div>
                    <div style="font-size:12px; color:#777;">
                        {{ $post->created_at->format('F j, Y') }} • {{ $post->view_count }} views
                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div style="display:flex; align-items:center; gap:15px;">

                {{-- SHARE --}}
                <div style="display:flex; gap:10px;">
                    <a href="https://twitter.com/intent/tweet?url={{ $postUrl }}&text={{ $postTitle }}" target="_blank">
                        <i class="fa fa-twitter"></i>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $postUrl }}" target="_blank">
                        <i class="fa fa-linkedin"></i>
                    </a>
                </div>

                {{-- REPORT (MODERN BUTTON) --}}
                <button onclick="openReportModal('post', {{ $post->id }})"
                    style="border:none; background:#f5f5f5; padding:6px 12px; border-radius:6px; font-size:13px;">
                    🚩 Report
                </button>

            </div>
        </div>

        {{-- IMAGE --}}
        <img src="{{ asset($post->thumbnail_path) }}"
            style="width:100%; border-radius:10px; margin-bottom:25px;">

        {{-- CONTENT --}}
        <div style="font-size:16px; line-height:1.8; color:#333;">
            {!! $post->body !!}
        </div>

        {{-- TAGS --}}
        <div style="margin-top:30px;">
            @foreach($post->tags as $tag)
                <a href="{{ route('frontend.showByTag', $tag->name) }}"
                style="background:#eee; padding:6px 10px; border-radius:20px; font-size:12px; margin-right:5px;">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>

        {{-- DIVIDER --}}
        <hr style="margin:40px 0;">

        {{-- COMMENTS --}}
        <h4 style="margin-bottom:20px;">
            💬 {{ $post->comments->whereNotNull('approved_by')->count() }} Comments
        </h4>

        @include('frontend.partials.comments', [
            'comments' => $post->comments->whereNotNull('approved_by')->whereNull('parent_id')
        ])

        {{-- COMMENT FORM --}}
        <form action="{{ route('comments.store') }}" method="POST" style="margin-top:20px;">
            @csrf

            @guest
                <div class="row">
                    <input type="text" name="guest_name" class="form-control mb-2" placeholder="Name">
                    <input type="email" name="guest_email" class="form-control mb-2" placeholder="Email">
                </div>
            @endguest

            <input type="hidden" name="commentable_type" value="post">
            <input type="hidden" name="commentable_id" value="{{ $post->id }}">

            <textarea name="comment" class="form-control mb-2" rows="4"
                placeholder="Write a comment..." required></textarea>

            <button class="btn btn-dark">Post Comment</button>
        </form>

    </div>



        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.reply-button').forEach(button => {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        const commentId = this.dataset.commentId;
                        const form = document.getElementById(`reply-form-${commentId}`);
                        form.style.display = form.style.display === 'none' ? 'block' : 'none';
                    });
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.toggle-replies-link').forEach(link => {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        const target = document.querySelector(this.dataset.target);
                        if (target.style.display === 'none') {
                            target.style.display = 'block';
                            this.textContent = 'Hide replies';
                        } else {
                            target.style.display = 'none';
                            this.textContent = this.getAttribute('data-original');
                        }
                    });
                    link.setAttribute('data-original', link.textContent);
                });
            });

        </script>

        <script>
            function openReportModal(type, id) {
                resetReportModal();
                const modal = document.getElementById('reportModal');
                modal.style.display = 'flex';

                document.getElementById('report_type').value = type;
                document.getElementById('report_id').value = id;
            }

            function closeReportModal() {
                const modal = document.getElementById('reportModal');
                modal.style.display = 'none';

                resetReportModal(); // 🔥 important
            }

            document.getElementById('reportModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeReportModal();
                }
            });

            function resetReportModal() {
                document.getElementById('report_reason').value = 'spam';
                document.getElementById('report_description').value = '';
                document.getElementById('report_type').value = '';
                document.getElementById('report_id').value = '';
            }

            function submitReport() {
                let type = document.getElementById('report_type').value;
                let id = document.getElementById('report_id').value;
                let reason = document.getElementById('report_reason').value;
                let description = document.getElementById('report_description').value;

                fetch('/report', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        type: type,
                        id: id,
                        reason: reason,
                        description: description
                    })
                })
                .then(res => res.json())
                .then(data => {
                    showReportToast("Thanks! We'll review this content.");
                        resetReportModal();   // clear inputs
                        closeReportModal();   // close modal
                    })
                .catch(err => {
                    alert("Error submitting report");
                    console.error(err);
                });
            }
            </script>
            <script>
                function showReportToast(message) {
                    const toast = document.getElementById('report-toast');
                    const msg = document.getElementById('report-toast-msg');

                    msg.innerText = message;
                    toast.style.display = 'block';
                    toast.style.opacity = '1';
                    toast.style.transform = 'translateX(0)';

                    // auto hide
                    setTimeout(() => {
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateX(100%)';

                        setTimeout(() => {
                            toast.style.display = 'none';
                        }, 300);
                    }, 2500);
                }
                </script>


@endsection
