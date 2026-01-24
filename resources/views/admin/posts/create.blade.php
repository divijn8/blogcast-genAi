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
                            <label for="thumbnail" class="form-label">Thumbnail</label>
                            <input type="file"
                                   accept="image/*"
                                   class="form-control @error('thumbnail')is-invalid @enderror"
                                   name="thumbnail"
                                   value="{{old('thumbnail')}}"
                                   id="thumbnail"/>
                            @error('thumbnail')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
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
        function generateArticleFromAI(evt) {

            const title = document.getElementById('title').value;
            const excerpt = document.getElementById('excerpt').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;


            // if(!title || !excerpt) {
            //     alert("You need to fill title and excerpt for AI to generate the content.");
            //     return;
            // }

            fetch(`/generate-ai`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ title, excerpt})
            })
                .then(res => res.json())
                .then(data => {
                    document.querySelector('trix-editor').editor.insertHTML(data.content);
                })
                .catch(err => console.warn(err));
        }
        document.getElementById('generateArticleFromAI').addEventListener('click', generateArticleFromAI);
    </script>
    <script>
        // Get the loader and the "Generate Article With AI" button
        const loader = document.getElementById('loader');
        const generateArticleButton = document.getElementById('generateArticleFromAI');

        // When the button is clicked, show the loader and simulate content generation
        generateArticleButton.addEventListener('click', function() {
            // Show loader
            loader.style.display = 'flex';

            // Simulate an AI content generation process (you can replace this with an actual AJAX request)
            setTimeout(function() {
                // Simulate AI-generated content being added to the editor
                const editor = document.querySelector('trix-editor');
                editor.editor.setSelectedRange([0, 0]); // Optional: place the cursor at the beginning

                // Hide the loader once the content is ready
                loader.style.display = 'none';
            }, 10000); // Simulate a delay of 3 seconds for the AI content generation
        });
    </script>

@endsection
