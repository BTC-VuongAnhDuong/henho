@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="m-b-20 header-title">@lang('admin.PRODUCT')</h4>

                @include('layouts.admin.notice')

                <form enctype='multipart/form-data' method="POST" action="{{url('admin?controller=Product&task=update&id='.$item->id)}}">
                    {{ csrf_field() }}
                    <div class="col-sm-12">

                        <div class="form-group">
                            <label>@lang('admin.STORE') <span>*</span></label>
                            <select name="data[agency_id]" class="form-control" required>
                                <option value="">@lang('admin.CHOOSE_AGENCY')</option>
                                @foreach($store AS $value)
                                    <option
                                        @if($item->agency_id == $value->id)
                                        selected
                                        @endif
                                        value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>@lang('admin.PRODUCT_CATEGORY') <span>*</span></label>
                            <select name="data[category_id]" class="form-control" required>
                                <option value="">@lang('admin.PRODUCT_CATEGORY')</option>
                                @foreach($categories AS $value)
                                    <option
                                        @if($item->category_id == $value->id)
                                        selected
                                        @endif
                                        value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>@lang('admin.PRODUCT_NAME') <span>*</span></label>
                            <input type="text" class="form-control" name="data[name]" value="{{$item->name}}" required />
                        </div>

                        <div class="form-group">
                            <label>@lang('admin.CONTENT')<span></span></label>
                            <textarea class="form-control" name="data[content]" id="summary-ckeditor">{{$item->content}}</textarea>
                        </div>

                        <div class="form-group">
                            <label>@lang('admin.PRICE')<span></span></label>
                            <input type="number" step="1" name="data[price]" min="0" class="form-control" value="{{$item->price}}" required>
                        </div>

                        <div class="form-group">
                            <fieldset class="form-group">
                                <a href="javascript:void(0)" class="btn btn-primary" onclick="$('#pro-image').click()">Tải ảnh lên</a>
                                <input type="file" id="pro-image" name="images[]" style="display: none;" class="form-control" multiple>
                            </fieldset>
                            <div class="preview-images-zone">
                                @foreach($images AS $key => $value)
                                <div class="preview-image preview-show-{{$key}}">
                                    <div class="image-cancel" data-no="{{$key}}">x</div>
                                    <div class="image-zone">
                                        <img id="pro-img-{{$key}}" src="{{$value->url}}">
                                    </div>
                                    <input type="hidden" name="old_images[]" value="{{$value->id}}">
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>

                    <input type="hidden" name="data[type]" value="{{$type}}">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary">@lang('admin.SUBMIT')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@section('javascript')
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace( 'summary-ckeditor', {
            filebrowserBrowseUrl: '{{ asset('public/ckfinder/ckfinder.html') }}',
            filebrowserImageBrowseUrl: '{{ asset('public/ckfinder/ckfinder.html?type=Images') }}',
            filebrowserFlashBrowseUrl: '{{ asset('public/ckfinder/ckfinder.html?type=Flash') }}',
            filebrowserUploadUrl: '{{ asset('public/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}',
            filebrowserImageUploadUrl: '{{ asset('public/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') }}',
            filebrowserFlashUploadUrl: '{{ asset('public/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash') }}'
        } );


        jQuery(document).ready(function($) {
            document.getElementById('pro-image').addEventListener('change', readImage, false);

            $(document).on('click', '.image-cancel', function() {
                let no = $(this).data('no');
                $(".preview-image.preview-show-"+no).remove();
            });
        });



        var num = 4;
        function readImage() {
            if (window.File && window.FileList && window.FileReader) {
                var files = event.target.files; //FileList object
                var output = $(".preview-images-zone");

                for (let i = 0; i < files.length; i++) {
                    var file = files[i];
                    console.log(file);
                    if (!file.type.match('image')) continue;

                    var picReader = new FileReader();

                    picReader.addEventListener('load', function (event) {
                        var picFile = event.target;
                        var html =  '<div class="preview-image preview-show-' + num + '">' +
                            '<div class="image-cancel" data-no="' + num + '">x</div>' +
                            '<div class="image-zone">' +
                            '<img id="pro-img-' + num + '" src="' + picFile.result + '">' +
                            '</div>'+
                            '</div>';

                        output.append(html);
                        num = num + 1;
                    });

                    picReader.readAsDataURL(file);
                }
                // $("#pro-image").val('');
            } else {
                console.log('Browser not support');
            }
        }


    </script>
@stop


@endsection
