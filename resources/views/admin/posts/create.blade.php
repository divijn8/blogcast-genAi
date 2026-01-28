@extends('admin.layouts.app')

@section('page-level-styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">

    <style>
        .is-invalid+.select2 > .selection > .select2-selection.select2-selection--multiple {
            border: solid 1px red;
        }
    </style>
    <style>
    .loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .spinner {
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

</style>

<style>
    .image-preview-box {
        width: 100%;
        max-width: 420px;
        height: 240px;
        border: 2px dashed #ccc;
        border-radius: 8px;
        background: #f8f9fc;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }

    .image-preview-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .custom-file-upload {
        width: 100%; /* 🔥 makes button big */
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px; /* slightly taller */
        border-radius: 10px;
        border: 2px dashed #4e73df;
        background: #f8f9fc;
        color: #4e73df;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s ease;
        font-size: 16px;
    }

    .custom-file-upload:hover {
        background: #4e73df;
        color: white;
    }

    #noImageText {
        color: #999;
        font-size: 14px;
    }

</style>

<style>
    .ai-structure-card {
        border: none;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .ai-structure-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px rgba(0,0,0,0.1);
    }
    .card-border-blue { border-top: 5px solid #4e73df; }
    .card-border-green { border-top: 5px solid #1cc88a; }
    .card-border-purple { border-top: 5px solid #6f42c1; }

    .ai-card-header {
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .badge-ai {
        font-size: 0.7rem;
        padding: 0.3em 0.6em;
        border-radius: 20px;
        font-weight: 700;
    }
    .badge-blue { background: #e8f0fe; color: #4e73df; }
    .badge-green { background: #e6fffa; color: #1cc88a; }
    .badge-purple { background: #f3ebff; color: #6f42c1; }
    .card-border-cyan { border-top: 5px solid #36b9cc; }
    .badge-cyan { background:#e0f7fa; color:#36b9cc; }

    .ai-outline-box {
        background: #f8f9fc;
        margin: 1rem;
        padding: 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        flex-grow: 1;
        overflow-y: auto;
        border: 1px dashed #d1d3e2;
    }
    .ai-outline-box ul {
        padding-left: 1.2rem;
    }
    .btn-generate {
        border-radius: 0 0 12px 12px;
    }

    /* FORCE FULLSCREEN AI MODAL */
#aiStructureModal.modal {
    padding: 0 !important;
}

#aiStructureModal .modal-dialog {
    width: 100vw;
    max-width: 100vw;
    height: 100vh;
    margin: 0;
}

#aiStructureModal .modal-content {
    height: 100vh;
    border-radius: 0;
    border: none;
    display: flex;
    flex-direction: column;
}

#aiStructureModal .modal-body {
    flex: 1;
    overflow-y: auto;
    padding: 2rem;
}

</style>

@endsection


@section('main-content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Post</h1>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <button type="button" id="btn-ai-agent" class="btn btn-info mb-3">
                            <i class="fas fa-robot"></i> AI Blog Architect
                        </button>
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text"
                                   class="form-control @error('title')is-invalid @enderror"
                                   name="title"
                                   value="{{old('title')}}"
                                   id="title"/>
                            @error('title')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Excerpt</label>
                            <textarea class="form-control @error('excerpt')is-invalid @enderror"
                                      name="excerpt"
                                      id="excerpt"
                            >{{old('excerpt')}}</textarea>
                            @error('excerpt')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="body" class="form-label">Body</label>
                            <trix-toolbar id="my_toolbar">
                                <div class="trix-button-row">
                                  <span class="trix-button-group trix-button-group--text-tools" data-trix-button-group="text-tools">
                                    <button type="button" class="trix-button trix-button--icon trix-button--icon-bold" data-trix-attribute="bold" data-trix-key="b" title="Bold" tabindex="-1">Bold</button>
                                    <button type="button" class="trix-button trix-button--icon trix-button--icon-italic" data-trix-attribute="italic" data-trix-key="i" title="Italics" tabindex="-1">Italics</button>
                                    <button type="button" class="trix-button trix-button--icon trix-button--icon-strike" data-trix-attribute="strike" title="Strikg" tabindex="-1">Strikg</button>
                                    <button type="button" class="trix-button trix-button--icon trix-button--icon-link" data-trix-attribute="href" data-trix-action="link" data-trix-key="k" title="Link" tabindex="-1">Link</button>
                                  </span>

                                    <span class="trix-button-group trix-button-group--block-tools" data-trix-button-group="block-tools">
                                    <button type="button" class="trix-button trix-button--icon trix-button--icon-heading-1" data-trix-attribute="heading1" title="Heading 1" tabindex="-1">Heading 1</button>
                                    <button type="button" class="trix-button trix-button--icon trix-button--icon-quote" data-trix-attribute="quote" title="Quote" tabindex="-1">Quote</button>
                                    <button type="button" class="trix-button trix-button--icon trix-button--icon-code" data-trix-attribute="code" title="Code" tabindex="-1">Code</button>
                                    <button type="button" class="trix-button trix-button--icon trix-button--icon-bullet-list" data-trix-attribute="bullet" title="Bullets" tabindex="-1">${lang.bullets}</button>
                                    <button type="button" class="trix-button trix-button--icon trix-button--icon-number-list" data-trix-attribute="number" title="Numbering" tabindex="-1">Numbering</button>
                                    <button type="button" class="trix-button trix-button--icon trix-button--icon-decrease-nesting-level" data-trix-action="decreaseNestingLevel" title="Outdent" tabindex="-1">Outdent</button>
                                    <button type="button" class="trix-button trix-button--icon trix-button--icon-increase-nesting-level" data-trix-action="increaseNestingLevel" title="Indent" tabindex="-1">Indent</button>
                                    </span>

                                    <span class="trix-button-group trix-button-group--file-tools" data-trix-button-group="file-tools">
                                        <button type="button" class="trix-button trix-button--icon trix-button--icon-attach" data-trix-action="attachFiles" title="Attach Files" tabindex="-1">Attach Files</button>
                                    </span>
                                    <span class="trix-button-group" data-trix-button-group="ai">
                                        <button id="generateArticleFromAI" type="button" class="trix-button" title="Generate Article With AI" tabindex="-1"><i class="fas fa-magic"></i></button>
                                    </span>



                                    <span class="trix-button-group-spacer"></span>

                                    <span class="trix-button-group trix-button-group--history-tools" data-trix-button-group="history-tools">
                                    <button type="button" class="trix-button trix-button--icon trix-button--icon-undo" data-trix-action="undo" data-trix-key="z" title="Undo" tabindex="-1">Undo</button>
                                    <button type="button" class="trix-button trix-button--icon trix-button--icon-redo" data-trix-action="redo" data-trix-key="shift+z" title="Redo" tabindex="-1">Redo</button>
                                    </span>
                                </div>

                                <div class="trix-dialogs" data-trix-dialogs>
                                    <div class="trix-dialog trix-dialog--link" data-trix-dialog="href" data-trix-dialog-attribute="href">
                                        <div class="trix-dialog__link-fields">
                                            <input type="url" name="href" class="trix-input trix-input--dialog" placeholder="${lang.urlPlaceholder}" aria-label="${lang.url}" data-trix-validate-href required data-trix-input>
                                            <div class="trix-button-group">
                                                <input type="button" class="trix-button trix-button--dialog" value="${lang.link}" data-trix-method="setAttribute">
                                                <input type="button" class="trix-button trix-button--dialog" value="${lang.unlink}" data-trix-method="removeAttribute">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </trix-toolbar>

                            <trix-editor input="body" toolbar="my_toolbar"></trix-editor>
                            <input type="hidden"
                                   class="form-control @error('body')is-invalid @enderror"
                                   name="body"
                                   id="body"
                                   value="{{old('body')}}"
                            />
                            @error('body')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror

                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Thumbnail</label>

                            <div class="row align-items-center">
                                <div class="col-md-4 col-lg-3 mb-3 mb-md-0">
                                    <div class="image-preview-box">
                                        <img id="thumbnailPreview"
                                            src=""
                                            style=display:none
                                            alt="Thumbnail">

                                        <span id="noImageText" >
                                            No Image Selected
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-8 col-lg-9">
                                    <label class="custom-file-upload w-100">
                                        <input type="file"
                                            accept="image/*"
                                            class="form-control @error('thumbnail') is-invalid @enderror"
                                            name="thumbnail"
                                            value="{{old('thumbnail')}}"
                                            id="thumbnail"
                                            hidden>
                                        <i class="fas fa-upload me-2"></i>  Choose Thumbnail
                                    </label>

                                    @error('thumbnail')
                                        <span class="text-danger text-sm d-block mt-2">{{ $message }}</span>
                                    @enderror

                                    <small class="text-muted d-block mt-2 text-center">
                                        Upload a new image to replace the existing thumbnail.
                                    </small>
                                </div>

                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-control select2 @error('category_id') is-invalid @enderror"
                                    name="category_id"
                                    id="category_id"
                            >
                                <option value="0" disabled selected>Select Category....</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tag_id" class="form-label">Tags</label>
                            <select class="form-control select2 @error('tags') is-invalid @enderror"
                                    name="tags[]"
                                    id="tag_id"
                                    multiple
                            >
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                            @error('tags')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                                <label for="published_at" class="form-label">Published At</label>
                                <input type="date"
                                    class="form-control @error('published_at') border-danger text-danger
                            @enderror"
                                    placeholder="Enter Published Date" id="published_at" name="published_at"
                                    value="{{ old('published_at') }}">
                                @error('published_at')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                        <!-- Draft checkbox (aligned right) -->
                        <input type="submit" class="btn btn-outline-primary" value="Publish Post">

                        <button type="submit"
                                name="save_as_draft"
                                value="1"
                                class="btn btn-outline-secondary ms-2">
                            Save as Draft
                        </button>


                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Loader HTML -->
<div class="loader" id="loader" style="display: none;">
    <div class="">Generating Magic With AI</div>
    <div class="spinner"></div>
</div>

<div class="modal fade modal-fullscreen" id="aiStructureModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header bg-white border-bottom shadow-sm py-3">
                <div class="container d-flex align-items-center justify-content-between">
                    <h5 class="modal-title text-primary font-weight-bold">
                        <i class="fas fa-robot mr-2"></i> AI Blog Architect
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
            </div>

            <div class="modal-body bg-light">
                <div class="container">

                    <!-- INPUT SECTION -->
                    <div id="ai-input-section" class="row justify-content-center mt-5">
                        <div class="col-lg-7 col-md-9 text-center">

                            <h2 class="font-weight-bold mb-3">What are we writing about?</h2>
                            <p class="text-muted mb-4">
                                The AI will analyze and suggest multiple content structures.
                            </p>

                            <div class="card shadow-lg border-0 rounded-lg text-left p-4">
                                <div class="form-group">
                                    <label class="font-weight-bold small text-uppercase text-muted">
                                        Blog Title
                                    </label>
                                    <input type="text" id="ai_modal_title"
                                           class="form-control form-control-lg bg-light border-0">
                                </div>

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold small text-uppercase text-muted">
                                        Concept / Excerpt
                                    </label>
                                    <textarea id="ai_modal_excerpt"
                                              class="form-control bg-light border-0"
                                              rows="3">
                                    </textarea>
                                </div>

                                <button id="btn-start-research"
                                        class="btn btn-primary btn-block btn-lg font-weight-bold">
                                    Start Research
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- RESULTS SECTION -->
                    <div id="ai-results-section" style="display:none;">

                        <div id="ai-loading" class="text-center py-5">
                            <div class="spinner-border text-primary"
                                 style="width:4rem;height:4rem"></div>
                            <h4 class="mt-4 font-weight-bold">Analyzing Structures…</h4>
                            <p class="text-muted">
                                Evaluating different content approaches.
                            </p>
                        </div>

                        <div id="structure-options" class="row"></div>

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="aiErrorModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Error.
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <p id="aiErrorMessage" class="mb-0"></p>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    OK
                </button>
            </div>

        </div>
    </div>
</div>

@endsection


@section('page-level-scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
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
    function showAIError(message) {
        $('#aiErrorMessage').text(message);
        $('#aiStructureModal').modal('hide');
        $('#aiErrorModal').modal('show');
    }
</script>
<script>
    document.addEventListener('trix-attachment-add', function (evt) {
        const attachment = evt.attachment;
        uploadTrixImage(attachment);
    });

    function uploadTrixImage(attachment) {
        const formData = new FormData();

        formData.append('image', attachment.file);

        fetch("{{ route('posts.uploadImage') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: formData
        })
        .then(response => response.json())
        .then(data=> {
            if(data.success) {
                attachment.setAttributes({
                    url: data.url,
                    href: data.url
                });
            } else {
                console.warn("Image Upload Failed!");
            }
        })
        .catch(() => {
            console.error("Some issue in fetch at server");
        })
    }
</script>

<script>
    document.getElementById('saveDraftBtn').addEventListener('click', function () {
        document.getElementById('draft').value = 1;
        this.closest('form').submit();
    });
</script>

<script>
    function generateArticleFromAI() {

        const title = document.getElementById('title').value.trim();
        const excerpt = document.getElementById('excerpt').value.trim();
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        if (!title || !excerpt) {
            showAIModal("You need to fill Title and Excerpt before AI can generate content.");
            return;
        }

        const loader = document.getElementById('loader');
        loader.style.display = 'flex';

        fetch('/generate-ai', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ title, excerpt })
        })
        .then(res => {
            if (!res.ok) throw new Error('AI error');
            return res.json();
        })
        .then(data => {
            document.querySelector('trix-editor').editor.insertHTML(data.content);
        })
        .catch(() => {
            showAIModal("Something went wrong while generating AI content.");
        })
        .finally(() => {
            loader.style.display = 'none';
        });
    }


    document.getElementById('generateArticleFromAI').addEventListener('click', generateArticleFromAI);
</script>

<script>
    document.getElementById('thumbnail').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(event) {
            const preview = document.getElementById('thumbnailPreview');
            preview.src = event.target.result;
            preview.style.display = "block";
            document.getElementById('noImageText').style.display = "none";
        };
        reader.readAsDataURL(file);
    });
</script>
<script>
    function showAIModal(message) {
        const loader = document.getElementById('loader');
        loader.style.display = 'none';

        document.getElementById('aiModalMessage').innerText = message;
        $('#aiErrorModal').modal('show');
    }

</script>

<script>
    $('#btn-ai-agent').on('click', function () {
        $('#aiStructureModal').modal('show');
        $('#ai-input-section').show();
        $('#ai-results-section').hide();
    });

    $('#btn-start-research').on('click', function () {

        let title = $('#ai_modal_title').val().trim();
        let excerpt = $('#ai_modal_excerpt').val().trim();

        if (!title || !excerpt) {
            showAIError('Title and Excerpt are required.');
            return;
        }

        $('#ai-input-section').hide();
        $('#ai-results-section').show();
        $('#ai-loading').show();
        $('#structure-options').empty();

        $.ajax({
            url: "{{ route('admin.ai.analyze') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                title: title,
                excerpt: excerpt
            },
            success: function (res) {

                $('#ai-loading').hide();

                res.structures.forEach(function (item, index) {

                    let badge = (item.badge || '').toLowerCase();
                    let color = 'blue';

                    if (badge === 'beginner') color = 'green';
                    else if (badge === 'intermediate') color = 'purple';
                    else if (badge === 'advanced') color = 'blue';
                    else if (badge === 'expert') color = 'cyan';

                    let cleanOutline = item.outline
                        .replace(/^#+\s*/gm, '')
                        .trim();

                    let outlineHtml = item.outline
                        .split('\n')
                        .map(line => `<li>${line}</li>`)
                        .join('');

                    let rawJson = JSON.stringify(item, null, 2);

                    let card = `
                        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                            <div class="ai-structure-card card-border-${color}">
                                <div class="ai-card-header">
                                    <h5 class="ai-structure-title">${item.name}</h5>
                                    <span class="badge-ai badge-${color}">
                                        ${item.badge ?? 'AI'}
                                    </span>
                                </div>

                                <div class="ai-outline-box">
                                    <ul class="mt-2">${outlineHtml}</ul>
                                </div>

                                <button class="btn btn-primary btn-generate btn-select-structure"
                                    data-outline="${encodeURIComponent(item.outline)}">
                                    Write This
                                </button>
                            </div>
                        </div>`;


                    $('#structure-options').append(card);
                });
            },
            error: function () {
                $('#ai-loading').hide();
                showAIError('AI analysis failed. Please try again.');
            }
        });
    });

    $(document).on('click', '.btn-select-structure', function () {

        let outline = decodeURIComponent($(this).data('outline'));
        let title = $('#ai_modal_title').val();
        let excerpt = $('#ai_modal_excerpt').val();

        $.ajax({
            url: "{{ route('admin.ai.generate') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                title: title,
                excerpt: excerpt,
                structure_outline: outline
            },
            success: function (res) {
                document.querySelector('trix-editor')
                    .editor.insertHTML(res.body);

                $('#title').val(title);
                $('#excerpt').val(excerpt);
                $('#aiStructureModal').modal('hide');
            },
            error: function () {
                showAIError('AI generation failed.');
            }
        });
    });

</script>
@endsection
