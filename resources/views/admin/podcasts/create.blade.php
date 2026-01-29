@extends('admin.layouts.app')

@section('page-level-styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .loader { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.7); display: flex; justify-content: center; align-items: center; z-index: 9999; }
        .spinner { border: 4px solid rgba(255, 255, 255, 0.3); border-top: 4px solid #3498db; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        .image-preview-box { width: 100%; max-width: 300px; height: 200px; border: 2px dashed #ccc; border-radius: 8px; background: #f8f9fc; display: flex; justify-content: center; align-items: center; overflow: hidden; }
        .image-preview-box img { max-width: 100%; max-height: 100%; object-fit: contain; }

        /* Podcast Specific Styling */
        .script-line { background: #f8f9fc; border-left: 4px solid #4e73df; padding: 10px; margin-bottom: 10px; border-radius: 5px; }
        .speaker-badge { font-weight: bold; color: #4e73df; text-transform: uppercase; font-size: 0.8rem; display: block; margin-bottom: 5px; }
    </style>
@endsection

@section('main-content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Podcast</h1>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.podcasts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <button type="button" id="btn-ai-podcast-agent" class="btn btn-primary mb-4">
                            <i class="fas fa-robot"></i> AI Podcast Architect
                        </button>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Podcast Title</label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g. The Future of AI in Maharashtra">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Description / Summary</label>
                                    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Conversational Script (JSON/Manual)</label>
                                    <div id="script-container" class="border p-3 rounded bg-light" style="min-height: 200px; max-height: 400px; overflow-y: auto;">
                                        <p class="text-muted" id="no-script-text">No script generated yet. Use AI Architect or add lines manually.</p>
                                    </div>
                                    <input type="hidden" name="script_json" id="script_json_input">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="font-weight-bold">Thumbnail</label>
                                    <div class="image-preview-box mb-2">
                                        <img id="thumbnailPreview" src="" style="display:none">
                                        <span id="noImageText">No Image</span>
                                    </div>
                                    <input type="file" name="thumbnail" id="thumbnail" class="form-control-file" accept="image/*">
                                </div>

                                <div class="mb-3">
                                    <label class="font-weight-bold">Audio File (.mp3)</label>
                                    <input type="file" name="audio_file" class="form-control-file" accept="audio/mp3">
                                    <small class="text-muted">If AI generated, it will be uploaded automatically.</small>
                                </div>

                                <div class="mb-3">
                                    <label class="font-weight-bold">Category</label>
                                    <select name="category_id" class="form-control select2">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="font-weight-bold">Published Date</label>
                                    <input type="text" id="published_at" name="published_at" class="form-control">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-success px-5 font-weight-bold">Publish Podcast</button>
                        <button type="submit" name="status" value="draft" class="btn btn-secondary px-4">Save Draft</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="aiPodcastModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document" style="max-width: 95vw;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-microphone-alt"></i> AI Podcast Script Architect</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body bg-light">
                    <div id="ai-input-view" class="text-center py-5">
                        <h3>What should the conversation be about?</h3>
                        <div class="col-md-6 mx-auto mt-4">
                            <input type="text" id="ai_topic" class="form-control form-control-lg mb-3" placeholder="Enter topic or news URL">
                            <select id="ai_speakers" class="form-control mb-3">
                                <option value="2">2 People (Host & Expert)</option>
                                <option value="3">3 People (Host & 2 Guests)</option>
                            </select>
                            <button id="btn-generate-script" class="btn btn-primary btn-lg btn-block">Generate Script</button>
                        </div>
                    </div>

                    <div id="ai-loading" class="text-center py-5" style="display:none;">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
                        <h4 class="mt-3">Writing Script...</h4>
                    </div>

                    <div id="ai-results-view" class="row" style="display:none;">
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div class="loader" id="page-loader" style="display: none;">
        <div class="spinner"></div>
    </div>
@endsection

@section('page-level-scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $('.select2').select2();

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

    </script>
    <script>
        $(document).ready(function() {

            // AI Modal Trigger
            $('#btn-ai-podcast-agent').on('click', function() {
                $('#aiPodcastModal').modal('show');
            });

            // Generate Script Logic
            $('#btn-generate-script').on('click', function() {
                let topic = $('#ai_topic').val();
                if(!topic) return alert('Please enter a topic');

                $('#ai-input-view').hide();
                $('#ai-loading').show();

                $.ajax({
                    url: "{{ route('admin.podcasts.index') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        topic: topic,
                        speakers: $('#ai_speakers').val()
                    },
                    success: function(res) {
                        $('#ai-loading').hide();
                        $('#ai-results-view').show().empty();

                        let scriptHtml = `<div class="col-md-12"><div class="card p-4"><h4>Proposed Script</h4><div class="mt-3">`;
                        res.script.forEach(line => {
                            scriptHtml += `
                                <div class="script-line">
                                    <span class="speaker-badge">${line.speaker}</span>
                                    <p class="mb-0">${line.text}</p>
                                </div>`;
                        });
                        scriptHtml += `</div><button class="btn btn-success mt-3" id="use-this-script">Use This Script</button></div>`;

                        $('#ai-results-view').append(scriptHtml);

                        $('#use-this-script').click(function() {
                            let scriptData = res.script;

                            $('#title').val(res.title);
                            $('#description').val(res.description);

                            $('#script-container').empty();
                            scriptData.forEach(line => {
                                $('#script-container').append(`
                                    <div class="script-line">
                                        <span class="speaker-badge">${line.speaker}</span>
                                        <p class="mb-0">${line.text}</p>
                                    </div>
                                `);
                            });

                            $('#script_json_input').val(JSON.stringify(scriptData));

                            $('#aiPodcastModal').modal('hide');
                            $('#no-script-text').hide();
                        });
                    }
                });
            });

            // Thumbnail Preview
            $('#thumbnail').change(function(e) {
                let reader = new FileReader();
                reader.onload = (event) => {
                    $('#thumbnailPreview').attr('src', event.target.result).show();
                    $('#noImageText').hide();
                }
                reader.readAsDataURL(e.target.files[0]);
            });
        });
    </script>
@endsection
