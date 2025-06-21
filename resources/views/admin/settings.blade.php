@extends('admin.layout.index')

@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{$action}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="form-body">
                    <h3 class="box-title">Settings</h3>
                    <hr class="m-t-0 m-b-40">

                    <div id="accordion" class="p-5">
                        @foreach ($settings as $slug => $items)
                            <div class="card">
                                <div class="card-header" id="headingOne-{{$slug}}">
                                <h5 class="mb-0">
                                    <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse-{{$slug}}" aria-expanded="true" aria-controls="collapse-{{$slug}}">
                                        {{strtoupper(str_replace('-',' ',$slug))}}
                                    </button>
                                </h5>
                                </div>

                                <div id="collapse-{{$slug}}" class="collapse" aria-labelledby="headingOne-{{$slug}}" data-parent="#accordion">
                                    <div class="card-body">
                                        @foreach ($items as $setting)
                                            <div class="col-md-12">
                                                <div class="form-group @error($setting->key) has-error @enderror">
                                                    <label class="control-label">{{$setting->title}}</label>
                                                        @switch($setting->type)
                                                            @case('text')
                                                                <textarea name="{{$setting->key}}" id="{{$setting->key}}">{{$setting->value}}</textarea>
                                                                @break
                                                            @case('string')
                                                                <input type="text" name="{{$setting->key}}" id="{{$setting->key}}" class="form-control" value="{{$setting->value}}">
                                                                @break
                                                            @case('url')
                                                                <input type="url" name="{{$setting->key}}" id="{{$setting->key}}" class="form-control" value="{{$setting->value}}">
                                                                @break
                                                            @case('date')
                                                                <input type="date" name="{{$setting->key}}" id="{{$setting->key}}" value="{{$setting->value}}" class="form-control">
                                                                @break
                                                            @case('checkbox')
                                                                <input type="checkbox" data-color="#13dafe" name="{{$setting->key}}" id="{{$setting->key}}" class="js-switch" {{$setting->value == 'on' ? 'checked' : ''}}>
                                                                @break
                                                            @case('image')
                                                                <input type="file" name="{{$setting->key}}" id="{{$setting->key}}" class="form-control">
                                                                <div class="col-sm-12">
                                                                    <img src="{{$setting->value_path}}" alt="img" height="80">
                                                                </div>
                                                                @break

                                                            @default

                                                        @endswitch
                                                        @error($setting->name)
                                                            <span class="help-block"> {{$message}} </span>
                                                        @enderror
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                      </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css">
@endpush

@push('scripts')
    <script type="importmap">
        {
            "imports": {
                "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.js",
                "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.2.0/"
            }
        }
    </script>

    <script type="module">
        import {
            ClassicEditor,
            Alignment,
            Autoformat,
            Bold,
            CKBox,
            Code,
            Italic,
            Strikethrough,
            Subscript,
            Superscript,
            Underline,
            BlockQuote,
            CloudServices,
            CodeBlock,
            Essentials,
            FindAndReplace,
            Font,
            Heading,
            Highlight,
            HorizontalLine,
            GeneralHtmlSupport,
            AutoImage,
            Image,
            ImageCaption,
            ImageInsert,
            ImageResize,
            ImageStyle,
            ImageToolbar,
            ImageUpload,
            Base64UploadAdapter,
            PictureEditing,
            Indent,
            IndentBlock,
            TextPartLanguage,
            AutoLink,
            Link,
            LinkImage,
            List,
            ListProperties,
            TodoList,
            MediaEmbed,
            Mention,
            PageBreak,
            Paragraph,
            PasteFromOffice,
            RemoveFormat,
            SpecialCharacters,
            SpecialCharactersEssentials,
            Style,
            Table,
            TableCaption,
            TableCellProperties,
            TableColumnResize,
            TableProperties,
            TableToolbar,
            TextTransformation,
            WordCount,
        } from 'ckeditor5';

        $(document).ready(e=>{
                @foreach($settings->flatten()->values()->where('type','text') as $item)
                    ClassicEditor
                        .create( document.querySelector( '#{{$item->key}}' ), {
                            plugins: [
                                Alignment,
                                Autoformat,
                                AutoImage,
                                AutoLink,
                                BlockQuote,
                                Bold,
                                CloudServices,
                                Code,
                                CodeBlock,
                                Essentials,
                                FindAndReplace,
                                Font,
                                GeneralHtmlSupport,
                                Heading,
                                Highlight,
                                HorizontalLine,
                                Image,
                                ImageCaption,
                                ImageInsert,
                                ImageResize,
                                ImageStyle,
                                ImageToolbar,
                                ImageUpload,
                                Base64UploadAdapter,
                                Indent,
                                IndentBlock,
                                Italic,
                                Link,
                                LinkImage,
                                List,
                                ListProperties,
                                MediaEmbed,
                                Mention,
                                PageBreak,
                                Paragraph,
                                PasteFromOffice,
                                PictureEditing,
                                RemoveFormat,
                                SpecialCharacters,
                                SpecialCharactersEssentials,
                                Strikethrough,
                                Style,
                                Subscript,
                                Superscript,
                                Table,
                                TableCaption,
                                TableCellProperties,
                                TableColumnResize,
                                TableProperties,
                                TableToolbar,
                                TextPartLanguage,
                                TextTransformation,
                                TodoList,
                                Underline,
                                WordCount,
                            ],
                            toolbar: [
                                'undo',
                                'redo',
                                '|',
                                'importWord',
                                'exportWord',
                                'exportPdf',
                                '|',
                                'formatPainter',
                                'caseChange',
                                'findAndReplace',
                                'selectAll',
                                'wproofreader',
                                '|',
                                'insertTemplate',
                                'tableOfContents',
                                '|',

                                // --- "Insertables" ----------------------------------------------------------------------------

                                'link',
                                'insertImage',
                                'ckbox',
                                'insertTable',
                                'blockQuote',
                                'mediaEmbed',
                                'codeBlock',
                                'pageBreak',
                                'horizontalLine',
                                'specialCharacters',
                                '-',

                                // --- Block-level formatting -------------------------------------------------------------------
                                'heading',
                                'style',
                                '|',

                                // --- Basic styles, font and inline formatting -------------------------------------------------------
                                'bold',
                                'italic',
                                'underline',
                                'strikethrough',
                                {
                                    label: 'Basic styles',
                                    icon: 'text',
                                    items: [
                                        'fontSize',
                                        'fontFamily',
                                        'fontColor',
                                        'fontBackgroundColor',
                                        'highlight',
                                        'superscript',
                                        'subscript',
                                        'code',
                                        '|',
                                        'textPartLanguage',
                                        '|',
                                    ],
                                },
                                'removeFormat',
                                '|',

                                // --- Text alignment ---------------------------------------------------------------------------
                                'alignment',
                                '|',

                                // --- Lists and indentation --------------------------------------------------------------------
                                'bulletedList',
                                'numberedList',
                                'multilevelList',
                                'todoList',
                                '|',
                                'outdent',
                                'indent',
                            ]
                        } )
                        .then( editor => {
                            window.editor = editor;
                        } )
                        .catch( error => {
                            console.error( error );
                        } );
                @endforeach
            })
    </script>

    <script>
        window.onload = function() {
            if ( window.location.protocol === "file:" ) {
                alert( "This sample requires an HTTP server. Please serve this file with a web server." );
            }
        };
    </script>
@endpush
