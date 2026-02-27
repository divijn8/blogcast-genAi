@extends('frontend.layouts.app')

@section('main-content')
<style>
    /* --- Custom Flexbox Utilities (Since theme is BS3) --- */
    .d-flex { display: flex; }
    .align-items-center { align-items: center; }
    .justify-content-center { justify-content: center; }
    .justify-content-between { justify-content: space-between; }
    .flex-column { flex-direction: column; }

    /* --- Podcast Styling --- */
    .podcast-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        border-radius: 8px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    .podcast-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url('https://www.transparenttextures.com/patterns/cubes.png');
        opacity: 0.1;
    }
    .podcast-thumb-large {
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        max-width: 100%;
        border: 4px solid rgba(255,255,255,0.2);
        display: block;
        margin: 0 auto;
    }
    .audio-player-wrapper {
        background: #fff;
        border-radius: 50px;
        padding: 10px 20px;
        margin-top: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .audio-player-wrapper audio {
        width: 100%;
        outline: none;
        margin-top: 5px;
    }

    /* --- Chat Transcript --- */
    .chat-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 20px;
    }
    .chat-bubble {
        max-width: 85%;
        padding: 15px 20px;
        border-radius: 20px;
        position: relative;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 15px;
        clear: both;
    }
    /* Host (Sarah) */
    .chat-left {
        align-self: flex-start;
        float: left; /* Fallback */
        background-color: #f1f3f5;
        color: #333;
        border-bottom-left-radius: 2px;
    }
    .chat-left .speaker-name {
        color: #4e73df; font-weight: bold; display: block;
        font-size: 11px; margin-bottom: 5px; text-transform: uppercase;
    }

    /* Guest (Michael) */
    .chat-right {
        align-self: flex-end;
        float: right; /* Fallback */
        background-color: #e3f2fd;
        color: #0d47a1;
        border-bottom-right-radius: 2px;
        text-align: right;
    }
    .chat-right .speaker-name {
        color: #0277bd; font-weight: bold; display: block;
        font-size: 11px; margin-bottom: 5px; text-transform: uppercase;
    }

    .section-heading {
        font-size: 18px;
        font-weight: 800;
        text-transform: uppercase;
        border-bottom: 2px solid #764ba2;
        display: inline-block;
        margin-bottom: 20px;
        padding-bottom: 5px;
        color: #444;
    }

    /* Clearfix for floats */
    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }
</style>

{{-- HERO AREA --}}
<div class="podcast-hero">
    <div class="row d-flex align-items-center">
        <div class="col-md-4 text-center">
            @if($podcast->thumbnail)
                <img src="{{ asset($podcast->thumbnail_path) }}" class="podcast-thumb-large img-responsive" alt="Podcast Thumbnail"
                     onerror="this.src='https://placehold.co/400x400?text=No+Image';">
            @else
                <img src="https://placehold.co/400x400?text=Podcast" class="podcast-thumb-large img-responsive" alt="Default Thumbnail">
            @endif
        </div>
        <div class="col-md-8">
            <span class="label label-primary" style="font-size: 12px; padding: 5px 10px; border-radius: 15px;">
                {{ $podcast->category->name ?? 'Podcast' }}
            </span>
            <h2 style="color: white; font-weight: 800; margin-top: 15px;">{{ $podcast->title }}</h2>

            <div style="opacity: 0.8; font-size: 13px; margin-top: 10px;">
                <i class="fa fa-calendar"></i> {{ $podcast->created_at->format('M d, Y') }}
                &nbsp;|&nbsp;
                <i class="fa fa-user"></i> {{ $podcast->author->name ?? 'Host' }}
            </div>

            {{-- AUDIO PLAYER --}}
            @if($podcast->audio_path)
            <div class="audio-player-wrapper">
                <audio controls>
                    <source src="{{ asset('storage/' . $podcast->audio_path) }}" type="audio/wav">
                    <source src="{{ asset('storage/' . $podcast->audio_path) }}" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            </div>
            @else
                <div class="alert alert-warning" style="margin-top: 20px; color: #333;">Audio is processing.</div>
            @endif
        </div>
    </div>
</div>

{{-- DESCRIPTION --}}
<div class="row">
    <div class="col-md-12">
        <h4 class="section-heading">About This Episode</h4>
        <p style="font-size: 16px; line-height: 1.8; color: #555;">
            {{ $podcast->description }}
        </p>

        @if($podcast->tags->count() > 0)
            <div style="margin-top: 20px;">
                @foreach($podcast->tags as $tag)
                    <span class="label label-default" style="margin-right: 5px;">#{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif
    </div>
</div>

<hr style="margin: 40px 0;">

{{-- TRANSCRIPT --}}
@if(!empty($podcast->script_json) && is_array($podcast->script_json))
<div class="row">
    <div class="col-md-12">
        <h4 class="section-heading">Transcript</h4>

        <div class="chat-container clearfix">
            @foreach($podcast->script_json as $line)
                @php
                    $speaker = strtolower($line['speaker'] ?? '');
                    // Check if speaker is 'host' OR 'sarah' (based on your prompt)
                    $isHost = ($speaker === 'host' || $speaker === 'sarah');
                @endphp

                @if($isHost)
                    {{-- HOST BUBBLE --}}
                    <div class="chat-bubble chat-left">
                        <span class="speaker-name"><i class="fa fa-microphone"></i> {{ $line['speaker'] }}</span>
                        {{ $line['text'] }}
                    </div>
                @else
                    {{-- GUEST BUBBLE --}}
                    <div class="chat-bubble chat-right">
                        <span class="speaker-name">{{ $line['speaker'] }} <i class="fa fa-user"></i></span>
                        {{ $line['text'] }}
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="text-center" style="margin-top: 50px; margin-bottom: 50px;">
    <a href="{{ route('frontend.podcasts.index') }}" class="btn btn-default btn-lg">
        <i class="fa fa-arrow-left"></i> Back to Podcasts
    </a>
</div>

@endsection
