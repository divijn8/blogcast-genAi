@extends('admin.layouts.app')

@section('page-level-styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">

    <style>
        .is-invalid+.select2 > .selection > .select2-selection.select2-selection--multiple {
            border: solid 1px red;
        }
    </style>
@endsection


@section('main-content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Post</h1>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text"
                                   class="form-control @error('title')is-invalid @enderror"
                                   name="title"
                                   value="{{old('title', $post->title)}}"
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
                            >{{old('excerpt', $post->excerpt)}}</textarea>
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
                                   value="{{old('body', $post->body)}}"
                            />
                            @error('body')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label d-block">Thumbnail</label>

                            <div class="row align-items-center">
                                <div class="col-md-4 col-lg-3 mb-3 mb-md-0">
                                    <div class="image-preview-box">
                                        <img id="thumbnailPreview"
                                            src="{{ $post->thumbnail ? asset('storage/'.$post->thumbnail) : '' }}"
                                            alt="Thumbnail">

                                        <span id="noImageText" {{ $post->thumbnail ? 'style=display:none' : '' }}>
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
                                    <option value="{{ $category->id }}" {{ $category->id == old('category_id', $post->category_id) ? 'selected' : '' }}>{{ $category->name }}</option>
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
                                    <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                            @error('tags')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <input type="submit" class="btn btn-outline-primary" value="Update Post">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="loader" id="loader" style="display: none;">
        <div class="">Regenerating Magic With AI</div>
        <div class="spinner"></div>
    </div>

    <!-- AI Validation Modal -->
    <div class="modal fade" id="aiErrorModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Missing Information</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body text-center">
                    <p id="aiModalMessage" class="mb-0"></p>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>
                </div>

            </div>
        </div>
    </div>

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

@endsection


@section('page-level-scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <script>
        $('.select2').select2();
    </script>
    <script>
        function showAIModal(message) {
            document.getElementById('loader').style.display = 'none';
            document.getElementById('aiModalMessage').innerText = message;
            $('#aiErrorModal').modal('show');
        }
    </script>

    <script>
        function generateArticleFromAI() {

            const title = document.getElementById('title').value.trim();
            const excerpt = document.getElementById('excerpt').value.trim();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const loader = document.getElementById('loader');

            if (!title || !excerpt) {
                showAIModal("You need to fill Title and Excerpt before AI can generate content.");
                return;
            }

            const trixEditor = document.querySelector('trix-editor');
            trixEditor.editor.loadHTML('');

            loader.style.display = 'flex';

            let userToken = "{{ auth()->user()->user_token }}";

            fetch(`/api/posts/${userToken}/generate-ai`, {
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
                trixEditor.editor.insertHTML(data.content);
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
        document.addEventListener("DOMContentLoaded", function () {

            const fileInput = document.getElementById('thumbnail');
            const previewImg = document.getElementById('thumbnailPreview');
            const noImageText = document.getElementById('noImageText');

            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];

                if (!file) return;

                // Validate image type
                if (!file.type.startsWith('image/')) {
                    alert("Please select a valid image file.");
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(event) {
                    previewImg.src = event.target.result; // 🔥 local image replaces server image
                    previewImg.style.display = "block";
                    noImageText.style.display = "none";
                };

                reader.readAsDataURL(file);
            });

        });
    </script>

@endsection
