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
        .form-control-studio { border: 2px solid #edf2f7; border-radius: 10px; padding: 12px 15px; transition: all 0.3s ease; font-size: 0.95rem; }
        .form-control-studio:focus { border-color: var(--primary-glow); box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1); outline: none; }
        .upload-zone { border: 2px dashed #cbd5e0; border-radius: 12px; padding: 2rem; text-align: center; cursor: pointer; transition: 0.3s; background: var(--bg-soft); }
        .upload-zone:hover { border-color: var(--primary-glow); background: #fff; }
        .script-timeline { position: relative; padding-left: 20px; }
        .script-timeline::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 2px; background: #edf2f7; }
        .script-bubble { position: relative; background: #fff; border: 1px solid #edf2f7; border-radius: 12px; padding: 15px; margin-bottom: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .script-bubble::before { content: ''; position: absolute; left: -24px; top: 15px; width: 10px; height: 10px; border-radius: 50%; background: var(--primary-glow); border: 2px solid #fff; }
        .speaker-tag { font-size: 0.7rem; font-weight: 900; color: var(--primary-glow); text-transform: uppercase; margin-bottom: 5px; display: block; }
        .sticky-summary { position: sticky; top: 20px; }
        .btn-glow { box-shadow: 0 4px 14px 0 rgba(78, 115, 223, 0.39); border-radius: 10px; font-weight: 700; padding: 12px 25px; }
        /* Chat styling for script preview */
        .chat-bubble-host { background-color: #e3f2fd; border-left: 4px solid #4e73df; padding: 10px; margin-bottom: 10px; border-radius: 8px; }
        .chat-bubble-guest { background-color: #f3f4f6; border-left: 4px solid #1cc88a; padding: 10px; margin-bottom: 10px; border-radius: 8px; }
        .ai-option-card {
            border-radius: 12px;
            background: #fff;
            border: 1px solid #edf2f7;
            padding: 16px;
            transition: all 0.25s ease;
            cursor: pointer;
            height: 100%;
        }

        .ai-option-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .ai-option-card.selected {
            border: 2px solid #4e73df;
            box-shadow: 0 0 0 3px rgba(78,115,223,0.15);
        }

        .ai-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            background: #e8f0fe;
            color: #4e73df;
        }

        .ai-outline {
            font-size: 0.8rem;
            color: #444;
        }

        .ai-outline ul {
            padding-left: 16px;
            margin-bottom: 0;
        }

        .ai-outline li {
            font-size: 0.8rem;
            margin-bottom: 4px;
            color: #444;
            line-height: 1.4;
        }
        .ai-outline {
            background: #f8f9fc;
            border: 1px dashed #d1d3e2;
            padding: 10px;
            border-radius: 8px;
        }

                /* === CARD COLOR THEMES === */
                .card-theme-green   { border-top: 4px solid #1cc88a; }
                .card-theme-purple  { border-top: 4px solid #6f42c1; }
                .card-theme-blue    { border-top: 4px solid #4e73df; }

        /* Optional: badge color match */
        .badge-green   { background: #e6fffa; color: #1cc88a; }
        .badge-purple  { background: #f3ebff; color: #6f42c1; }
        .badge-blue    { background: #e8f0fe; color: #4e73df; }
    </style>
@endsection

@section('main-content')
<div class="container-fluid pb-5">

    {{-- MAIN FORM START --}}
    <form action="{{ route('admin.podcasts.store') }}" method="POST" enctype="multipart/form-data" id="podcastForm">
        @csrf

        {{-- HIDDEN INPUT: This is where the AI Audio URL will be stored --}}
        <input type="hidden" name="generated_audio_path" id="generated_audio_path">

        <div class="row">
            {{-- LEFT COLUMN --}}
            <div class="col-lg-7">
                <div class="studio-card">
                    <div class="studio-header">
                        <h4 class="mb-0 font-weight-bold text-dark">New Podcast Episode</h4>
                    </div>

                    <div class="card-body p-4">
                        <span class="section-title">General Information</span>
                        <div class="form-group mb-4">
                            <label class="small font-weight-bold">Episode Title</label>
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
                                    <p class="small text-muted mb-0">Upload Manual Audio (Optional)</p>
                                    <input type="file" name="audio_file" id="audio_file" hidden accept="audio/mp3,audio/wav">
                                    <div id="audio-name" class="small text-primary font-weight-bold mt-2"></div>
                                </div>
                            </div>
                        </div>

                        {{-- AI Player Container --}}
                        <div class="row" id="ai-audio-player-container" style="display:none;">
                            <div class="col-12 mb-4">
                                <div class="p-3 border rounded bg-light text-center">
                                    <label class="font-weight-bold text-success mb-2">
                                        <i class="fas fa-check-circle mr-1"></i> AI Audio Generated Successfully!
                                    </label>
                                    <audio id="ai-audio-player" controls class="w-100 mt-2"></audio>
                                    <small class="text-muted d-block mt-2">Click "Publish Episode" to save this audio.</small>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="col-lg-5">
                <div class="sticky-summary">
                    <div class="studio-card shadow-lg">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary btn-block btn-glow mb-3">
                                <i class="fas fa-paper-plane mr-2"></i> PUBLISH EPISODE
                            </button>
                            <button type="button" name="status" value="draft" class="btn btn-light btn-block font-weight-bold text-muted mb-4">
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
                            </div>
                            {{-- Script JSON Input --}}
                            <input type="hidden" name="script_json" id="script_json_input">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- MAIN FORM END --}}
</div>

{{-- AI MODAL --}}
<div class="modal fade" id="aiPodcastModal" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body p-5">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>

                {{-- VIEW 1: INPUT --}}
                <div id="ai-input-view" class="text-center">
                    <div class="mb-4">
                        <span class="fa-stack fa-2x">
                            <i class="fas fa-circle fa-stack-2x text-primary-soft"></i>
                            <i class="fas fa-microphone fa-stack-1x text-primary"></i>
                        </span>
                    </div>
                    <h3 class="font-weight-bold">AI Podcast Architect</h3>
                    <p class="text-muted">Enter a topic and I'll create a 2-person conversation (Host & Expert).</p>

                    <div class="mt-4">
                        <input type="text" id="ai_topic" class="form-control-studio w-100 mb-3" placeholder="Topic: e.g. Why Bitcoin is rising?">

                        <select id="ai_speakers" class="form-control-studio w-100 mb-4">
                            <option value="2">2 Speakers (Female Host & Male Expert)</option>
                            <option value="3">3 Speakers (Host, Guest & Expert)</option>
                        </select>

                        <button id="btn-generate-script" class="btn btn-primary btn-block btn-lg btn-glow">
                            Create Masterpiece <i class="fas fa-chevron-right ml-2"></i>
                        </button>
                    </div>
                </div>

                {{-- VIEW 2: LOADING --}}
                <div id="ai-loading" class="text-center py-5" style="display:none;">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
                    <h4 class="mt-4 font-weight-bold" id="loading-text">Analyzing & Drafting...</h4>
                </div>

                {{-- VIEW 3: RESULTS --}}
                <div id="ai-results-view" style="display:none;">

                    {{-- Sub-view: Concept Options --}}
                    <div id="ai-options-view" style="display:none;">
                        <h5 class="text-center font-weight-bold mb-4">Choose a Direction</h5>
                        <div class="row" id="options-container"></div>
                    </div>

                    {{-- Sub-view: Script Preview --}}
                    <div id="script-preview-container" style="display:none;">
                        <h5 class="font-weight-bold mb-3">Proposed Script</h5>
                        <div id="script-preview-list" class="mb-4 p-3 bg-light rounded" style="max-height: 350px; overflow-y: auto;"></div>

                        <button class="btn btn-success btn-block btn-lg btn-glow" id="use-this-script">
                            <i class="fas fa-robot mr-2"></i> Generate Audio (Host: Female, Guest: Male)
                        </button>
                        {{-- <button class="btn btn-link btn-block text-muted" onclick="$('#ai-results-view').hide(); $('#ai-options-view').show();">
                            Back to Concepts
                        </button> --}}
                    </div>

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
    $('.select2').select2({ width: '100%', placeholder: 'Select options' });

    $(document).ready(function() {
        // Modal Trigger
        $('#btn-ai-podcast-agent').on('click', function() {
            $('#aiPodcastModal').modal('show');
            $('#ai-input-view').show();
            $('#ai-loading').hide();
            $('#ai-results-view').hide();
            $('#ai-options-view').hide();
            $('#script-preview-container').hide();
            $('#script-preview-list').empty();
        });

        // Thumbnail Preview
        $('#thumbnail').on('change', function(e) {
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = (event) => {
                    $('#thumb-preview').attr('src', event.target.result).fadeIn();
                    $('.upload-zone i.fa-image').hide(); $('.upload-zone p').hide();
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Audio Name Preview
        $('#audio_file').on('change', function(e) {
            if (this.files && this.files[0]) {
                $('#audio-name').html(`<i class="fas fa-check-circle text-success"></i> ${this.files[0].name}`);
            }
        });

        // --- STEP 1: ANALYZE PODCAST ---
        $('#btn-generate-script').on('click', function() {
            let topic = $('#ai_topic').val();
            let speakers = $('#ai_speakers').val();

            if(!topic) { alert("Please enter a topic."); return; }

            $('#ai-input-view').hide();
            $('#ai-loading').show().find('h4').text('Designing Podcast Concepts...');

            $.post("{{ route('admin.ai.podcast.analyze') }}", {
                _token: "{{ csrf_token() }}",
                topic: topic,
                speakers: speakers
            }, function(res) {
                // Check if options exist
                if (!res.options || !Array.isArray(res.options)) {
                    // Try to handle direct array return
                    if(Array.isArray(res)) res.options = res;
                    else {
                        alert("AI could not generate concepts. Try again.");
                        $('#ai-loading').hide(); $('#ai-input-view').show();
                        return;
                    }
                }

                $('#ai-loading').hide();
                $('#ai-results-view').show();
                $('#ai-options-view').show();

                // ✅ Sort by difficulty
                const order = {
                    'Beginner': 1,
                    'Intermediate': 2,
                    'Expert': 3
                };

                res.options.sort((a, b) => {
                    let aVal = order[a.difficulty] || 99;
                    let bVal = order[b.difficulty] || 99;
                    return aVal - bVal;
                });

                let cardsHtml = '';
                const themes = ['green', 'purple', 'blue'];

                res.options.forEach((opt, index) => {

                    let theme = themes[index % themes.length]; // rotate colors
                    let outlinePoints = (opt.structure_outline || '').split('\n');

                    let outlineHtml = '<ul class="mb-0 pl-3">';
                    outlinePoints.forEach(point => {
                        if(point.trim()){
                            outlineHtml += `<li>${point}</li>`;
                        }
                    });
                    outlineHtml += '</ul>';

                    // ✅ Card HTML
                    cardsHtml += `
                    <div class="col-md-4 mb-4">
                        <div class="ai-option-card option-card card-theme-${theme}"
                            onclick="selectOption(this, ${JSON.stringify(opt).replace(/"/g, '&quot;')})">

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="ai-badge badge-${theme}">${opt.difficulty || 'General'}</span>
                            </div>

                            <h6 class="font-weight-bold mb-2">${opt.title}</h6>

                            <p class="small text-muted mb-2">
                                ${opt.tone || 'Conversational'}
                            </p>

                            <div class="ai-outline">
                                ${outlineHtml}
                            </div>
                        </div>
                    </div>`;
                });

                // ✅ Render once
                $('#options-container').html(cardsHtml);
            }).fail(function(xhr) {
                alert("Server Error: Check console.");
                $('#ai-loading').hide(); $('#ai-input-view').show();
            });
        });

        // --- STEP 2: GENERATE SCRIPT ---
        window.selectOption = function(el, optionData) {
            $('.option-card').removeClass('selected');
            $(el).addClass('selected');

            $('#ai-options-view').hide();
            $('#ai-loading').show().find('h4').text('Writing Script (Host: Female, Expert: Male)...');

            // Auto-fill Title
            $('#title').val(optionData.title);

            // ✅ NEW: Auto-fill description
            let shortDesc = optionData.description
                || optionData.summary
                || optionData.short_description
                || optionData.title;

            $('#description').val(shortDesc);

            $.post("{{ route('admin.ai.podcast.script') }}", {
                _token: "{{ csrf_token() }}",
                title: optionData.title,
                structure_outline: optionData.structure_outline,
                speakers: $('#ai_speakers').val()
            }, function(res) {
                let scriptData = res.script || res;
                if (!Array.isArray(scriptData)) { alert("Invalid script format."); return; }

                $('#ai-loading').hide();
                $('#ai-results-view').show();
                $('#script-preview-container').show();

                let previewHtml = '';
                scriptData.forEach(line => {
                    let bubbleClass = line.speaker === 'Host' ? 'chat-bubble-host' : 'chat-bubble-guest';
                    let icon = line.speaker === 'Host' ? '<i class="fas fa-female mr-1"></i>' : '<i class="fas fa-male mr-1"></i>';
                    previewHtml += `
                        <div class="${bubbleClass}">
                            <strong class="d-block small text-muted">${icon} ${line.speaker}</strong>
                            <span>${line.text}</span>
                        </div>
                    `;
                });
                $('#script-preview-list').html(previewHtml);
                window.currentScript = scriptData;
            });
        };

        function fillStudioUI(script) {
            $('#script-container').empty();
            script.forEach(line => {
                $('#script-container').append(`
                    <div class="script-bubble">
                        <span class="speaker-tag">${line.speaker}</span>
                        <p class="mb-0 small text-dark">${line.text}</p>
                    </div>
                `);
            });
            $('#script_json_input').val(JSON.stringify(script));
            $('#no-script-text').hide();
        }

        // --- STEP 3: GENERATE AUDIO (THE FIX IS HERE) ---
        $('#use-this-script').on('click', function() {
            fillStudioUI(window.currentScript);

            let btn = $(this);
            let originalText = btn.html();
            btn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Generating Audio (Piper)...').prop('disabled', true);

            $.post("{{ route('admin.ai.podcast.audio') }}", {
                _token: "{{ csrf_token() }}",
                script: window.currentScript
            }, function(res) {

                // --- CRITICAL FIX START ---
                if(res) {
                    // 1. Force the URL into the hidden input
                    // Note: 'res' should be the URL string directly if returned via asset()
                    // or res.audio_url if returned as JSON.
                    let finalUrl = (typeof res === 'object' && res.audio_url) ? res.audio_url : res;

                    $('#generated_audio_path').val(finalUrl);

                    // 2. Play Audio
                    $('#ai-audio-player').attr('src', finalUrl);
                    $('#ai-audio-player-container').slideDown();

                    // 3. UI Updates
                    $('#audio-name').html(`<i class="fas fa-check-circle text-success"></i> Audio Ready for Publishing`);
                    $('#aiPodcastModal').modal('hide');

                    alert('Audio Generated Successfully! Click "PUBLISH EPISODE" to save it.');
                } else {
                    alert('Generation completed but no URL returned.');
                }
                // --- CRITICAL FIX END ---

            })
            .fail(function(xhr) {
                let errorMsg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : "Unknown error";
                // If it's the detailed exception from the backend
                if(xhr.responseJSON && xhr.responseJSON.message) errorMsg = xhr.responseJSON.message;

                alert('Audio generation failed: ' + errorMsg);
            })
            .always(function() {
                btn.html(originalText).prop('disabled', false);
            });
        });

        // Datepicker init
        flatpickr("#published_at", {
            enableTime: true,
            time_24hr: false,

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
