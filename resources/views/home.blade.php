@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Cập nhật thông tin cá nhân</div>
                <div class="card-body">
                    <form method="POST" action="{{ url('api/user/update_info') }}">
                        <input type="hidden" name="_token" value="3HjK6xDYqESVxpxNetlRjZlykEPqd5Jy4NyR45hj">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Họ tên đầy đủ</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control " name="name" value="" required autocomplete="name" autofocus>

                                                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Số điện thoại</label>
                            <div class="col-md-6">
                                <input id="phone" type="phone" class="form-control " name="email" value="" required autocomplete="phone">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Giới tính</label>
                            <div class="col-md-6">
                                
                                <?php foreach(\App\Glossary\UserGender::getAll() as $group){?>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="gender" value="{{ $group['value'] }}" required >
                                        <label class="form-check-label" >{{ $group['display'] }}</label>
                                    </div>
                                <?php }?>
                                
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Chọn đối tượng</label>
                            <div class="col-md-6">
                                
                                <?php foreach(\App\Glossary\UserGender::getAll() as $group){?>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="gender" value="{{ $group['value'] }}" required >
                                        <label class="form-check-label" >{{ $group['display'] }}</label>
                                    </div>
                                <?php }?>
                                
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Cập nhật
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
