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

                            <!-- Loading message -->
                            <span id="loadingMessage" class="text-warning text-sm" style="display: none;">Magic is happening ....Please wait....</span>
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

                        <input type="submit" class="btn btn-outline-primary" value="Create Post">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('page-level-scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <script>
        $('.select2').select2();
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
        function generateArticleFromAI(evt) {
            const loadingMessage = "Magic is happening... Please wait...";
            const title = document.getElementById('title').value;
            const excerpt = document.getElementById('excerpt').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            document.querySelector('trix-editor').editor.setSelectedRange([0, 0]); // Place cursor at the beginning
            document.querySelector('trix-editor').editor.insertString(loadingMessage);

            // if(!title || !excerpt) {
            //     alert("You need to fill title and excerpt for AI to generate the content.");
            //     return;
            // }

            let userToken="{{auth()->user()->user_token}}";
            fetch(`/api/posts/${userToken}/generate-ai`, {
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
@endsection
