@if(!empty(Session::get('success')))
    <div class="alert alert-success alert-dismissible in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <strong>Thành công!</strong> {!! Session::get('success')[0] !!}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <strong>Lỗi!</strong> {{$errors->first()}}
    </div>
@endif