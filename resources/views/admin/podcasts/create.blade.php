@extends('admin.layouts.app')
@section('page-level-styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        :root { --primary-glow: #4e73df; --bg-soft: #f8f9fc; }
        body { background-color: #f3f4f6; }

        .studio-card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: #fff; margin-bottom: 2rem; }
        .studio-header { padding: 1.5rem; border-bottom: 1px solid #edf2f7; display: flex; align-items: center; justify-content: space-between; }
        .section-title { font-size: 0.9rem; font-weight: 800; color: #4a5568; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1.5rem; display: block; }

        /* Floating Labels & Modern Inputs */
        .form-control-studio { border: 2px solid #edf2f7; border-radius: 10px; padding: 12px 15px; transition: all 0.3s ease; font-size: 0.95rem; }
        .form-control-studio:focus { border-color: var(--primary-glow); box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1); outline: none; }

        /* File Upload Area */
        .upload-zone { border: 2px dashed #cbd5e0; border-radius: 12px; padding: 2rem; text-align: center; cursor: pointer; transition: 0.3s; background: var(--bg-soft); }
        .upload-zone:hover { border-color: var(--primary-glow); background: #fff; }

        /* Script UI */
        .script-timeline { position: relative; padding-left: 20px; }
        .script-timeline::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 2px; background: #edf2f7; }
        .script-bubble { position: relative; background: #fff; border: 1px solid #edf2f7; border-radius: 12px; padding: 15px; margin-bottom: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .script-bubble::before { content: ''; position: absolute; left: -24px; top: 15px; width: 10px; height: 10px; border-radius: 50%; background: var(--primary-glow); border: 2px solid #fff; }
        .speaker-tag { font-size: 0.7rem; font-weight: 900; color: var(--primary-glow); text-transform: uppercase; margin-bottom: 5px; display: block; }

        .sticky-summary { position: sticky; top: 20px; }
        .btn-glow { box-shadow: 0 4px 14px 0 rgba(78, 115, 223, 0.39); border-radius: 10px; font-weight: 700; padding: 12px 25px; }
    </style>
@endsection

@section('main-content')
<div class="container-fluid pb-5">
    <form action="{{ route('admin.podcasts.store') }}" method="POST" enctype="multipart/form-data" id="podcastForm">
        @csrf

        <div class="row">
            {{-- LEFT COLUMN: Details --}}
            <div class="col-lg-8">
                <div class="studio-card">
                    <div class="studio-header">
                        <h4 class="mb-0 font-weight-bold text-dark">New Podcast</h4>
                    </div>

                    <div class="card-body p-4">
                        <span class="section-title">General Information</span>
                        <div class="form-group mb-4">
                            <label class="small font-weight-bold">Podcast Title</label>
                            <input type="text" name="title" id="title" class="form-control-studio w-100" placeholder="e.g. The Future of AI in 2026">
                        </div>

                        <div class="form-group mb-4">
                            <label class="small font-weight-bold">Short Description</label>
                            <textarea name="description" id="description" class="form-control-studio w-100" rows="3" placeholder="Describe what this episode covers..."></textarea>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="small font-weight-bold">Category</label>
                                <select name="category_id" class="form-control select2">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="small font-weight-bold">Tags</label>
                                <select name="tags[]" class="form-control select2" multiple>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="small font-weight-bold">Publishing Date</label>
                                <input type="text" id="published_at" name="published_at" class="form-control-studio w-100 bg-white" placeholder="Enter Publish Date">
                            </div>
                        </div>
                        <span class="section-title">Media Assets</span>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="upload-zone" onclick="document.getElementById('thumbnail').click();">
                                    <i class="fas fa-image fa-2x text-muted mb-2"></i>
                                    <p class="small text-muted mb-0">Upload Thumbnail</p>
                                    <input type="file" name="thumbnail" id="thumbnail" accept="image/*" hidden>
                                    <img id="thumb-preview" class="img-fluid rounded mt-2" style="display:none; max-height: 100px;" >
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="upload-zone" onclick="document.getElementById('audio_file').click();">
                                    <i class="fas fa-music fa-2x text-muted mb-2"></i>
                                    <p class="small text-muted mb-0">Upload Audio File (MP3)</p>
                                    <input type="file" name="audio_file" id="audio_file" hidden accept="audio/mp3">
                                    <div id="audio-name" class="small text-primary font-weight-bold mt-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: Actions & Script Preview --}}
            <div class="col-lg-4">
                <div class="sticky-summary">
                    <div class="studio-card shadow-lg">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary btn-block btn-glow mb-3">
                                <i class="fas fa-paper-plane mr-2"></i> PUBLISH EPISODE
                            </button>
                            <button type="button" class="btn btn-light btn-block font-weight-bold text-muted mb-4">
                                SAVE AS DRAFT
                            </button>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="section-title mb-0">Live Script</span>
                                <button type="button" id="btn-ai-podcast-agent" class="btn btn-sm btn-outline-primary border-0">
                                    <i class="fas fa-magic mr-1"></i> AI Generate
                                </button>
                            </div>

                            <div id="script-container" class="script-timeline" style="max-height: 500px; overflow-y: auto;">
                                <div class="text-center py-4 text-muted" id="no-script-text">
                                    <i class="fas fa-microphone-slash fa-2x mb-2"></i>
                                    <p class="small">No script has been generated yet.</p>
                                </div>
                                {{-- Bubbles will be injected here --}}
                            </div>
                            <input type="hidden" name="script_json" id="script_json_input">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- MODAL RE-DESIGN --}}
<div class="modal fade" id="aiPodcastModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body p-5">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <div id="ai-input-view" class="text-center">
                    <div class="mb-4">
                        <span class="fa-stack fa-2x">
                            <i class="fas fa-circle fa-stack-2x text-primary-soft"></i>
                            <i class="fas fa-microphone fa-stack-1x text-primary"></i>
                        </span>
                    </div>
                    <h3 class="font-weight-bold">AI Podcast Architect</h3>
                    <p class="text-muted">Enter a topic and I'll create a professional conversational script.</p>

                    <div class="mt-4">
                        <input type="text" id="ai_topic" class="form-control-studio w-100 mb-3" placeholder="Topic: e.g. Why Bitcoin is rising?">
                        <select id="ai_speakers" class="form-control-studio w-100 mb-4">
                            <option value="2">2 Speakers (Host & Expert)</option>
                            <option value="3">3 Speakers (Host & 2 Guests)</option>
                        </select>
                        <button id="btn-generate-script" class="btn btn-primary btn-block btn-lg btn-glow">
                            Create Masterpiece <i class="fas fa-chevron-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <div id="ai-loading" class="text-center py-5" style="display:none;">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
                    <h4 class="mt-4 font-weight-bold">Analyzing & Drafting...</h4>
                </div>

                <div id="ai-results-view" style="display:none;">
                    <h5 class="font-weight-bold mb-3">Proposed Script</h5>
                    <div id="script-preview-list" class="mb-4 p-3 bg-light rounded" style="max-height: 350px; overflow-y: auto;"></div>
                    <button class="btn btn-success btn-block btn-lg btn-glow" id="use-this-script">Apply to Studio</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-level-scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $('.select2').select2({
        width: '100%',
        placeholder: 'Select options'
    });

    $(document).ready(function() {
    // 1. MODAL TRIGGER
    $('#btn-ai-podcast-agent').on('click', function() {
        $('#aiPodcastModal').modal('show');
    });

    // 2. THUMBNAIL PREVIEW (FIXED)
    $('#thumbnail').on('change', function(e) {
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = (event) => {
                $('#thumb-preview').attr('src', event.target.result).fadeIn();
                $('.upload-zone i.fa-image').hide(); // Hide icon when image is there
                $('.upload-zone p').hide(); // Hide text
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // 3. AUDIO SELECTION FEEDBACK
    $('#audio_file').on('change', function(e) {
        if (this.files && this.files[0]) {
            let fileName = this.files[0].name;
            $('#audio-name').html(`<i class="fas fa-check-circle text-success"></i> ${fileName}`);
        }
    });

    // 4. AI SCRIPT GENERATION
    $('#btn-generate-script').on('click', function() {
        let topic = $('#ai_topic').val();
        let speakers = $('#ai_speakers').val();

        if(!topic) {
            alert('Please enter a topic or URL');
            return;
        }

        // UI State: Loading
        $('#ai-input-view').hide();
        $('#ai-loading').show();

        $.ajax({
            url: "{{ route('admin.podcasts.index') }}", // Ensure this route handles POST for AI
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                topic: topic,
                speakers: speakers
            },
            success: function(res) {
                $('#ai-loading').hide();
                $('#ai-results-view').show();

                let previewHtml = "";
                res.script.forEach(line => {
                    previewHtml += `
                        <div class="p-2 border-bottom mb-2">
                            <strong class="text-primary small">${line.speaker.toUpperCase()}</strong>
                            <p class="mb-0 small">${line.text}</p>
                        </div>`;
                });
                $('#script-preview-list').html(previewHtml);

                // Use This Script Button Logic
                $('#use-this-script').off('click').on('click', function() {
                    // Fill Title & Description
                    $('#title').val(res.title);
                    $('#description').val(res.description);

                    // Clear and Fill Studio Timeline
                    $('#script-container').empty();
                    res.script.forEach(line => {
                        $('#script-container').append(`
                            <div class="script-bubble">
                                <span class="speaker-tag">${line.speaker}</span>
                                <p class="mb-0 small text-dark">${line.text}</p>
                            </div>
                        `);
                    });

                    // Set hidden input for form submission
                    $('#script_json_input').val(JSON.stringify(res.script));

                    // Reset UI
                    $('#aiPodcastModal').modal('hide');
                    $('#no-script-text').hide();

                    // Reset modal for next use
                    setTimeout(() => {
                        $('#ai-results-view').hide();
                        $('#ai-input-view').show();
                    }, 500);
                });
            },
            error: function() {
                alert('Generation failed. Please try again.');
                $('#ai-loading').hide();
                $('#ai-input-view').show();
            }
        });
    });

    // 5. SELECT2 & FLAT PICKR RE-INIT


        flatpickr("#published_at", {
            enableTime: true,
            time_24hr: true,

            defaultDate: null,
            minDate: "today",

            altInput: true,
            altFormat: "F j, Y H:i",
            dateFormat: "Y-m-d H:i",

            onOpen: function(selectedDates, dateStr, instance) {
                const now = new Date();

                // Disable past time for today
                if (instance.selectedDates.length) {
                    const selected = instance.selectedDates[0];
                    if (selected.toDateString() === now.toDateString()) {
                        instance.set('minTime', now);
                    } else {
                        instance.set('minTime', '00:00');
                    }
                } else {
                    instance.set('minTime', now);
                }
            }
        });
});
</script>
@endsection
