@extends('layouts.admin')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<h4 class="m-b-20 header-title">Form Elements</h4>

			@include('layouts.admin.notice')

			<form enctype='multipart/form-data' method="POST" action="{{url('admin?controller=User&task=update&id='.$item->id)}}">
				{{ csrf_field() }}
				<input name="_method" type="hidden" value="PATCH">
				<div class="col-sm-6">
					<div class="row">
						<div class="container">
							<div class="col-md-3 user-details">
								<button class="btn btn-defau`lt waves-effect waves-light avatar-button" id="custom-html-alert" index="{{ $item->id }}" type="button">
									<img src="{{$item->avatar}}" class="thumb-md img-circle">
								</button>
							</div>
							<div class="col-md-4">
								<div><b>{{$item->name}}</b></div>
							</div>
							<div class="col-md-5">
								<a href="{{url('admin?controller=User&task=resetUserPassword&id='.$item->id)}}">
									<button type="button" class="btn btn-danger">Reset User Password</button>
								</a>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Name <span>*</span></label>
						<input type="text" class="form-control" name="data[name]" value="{{$item->name}}" required />
					</div>
					<div class="form-group">
						<label>Email <span>*</span></label>
						<input type="email" class="form-control" name="data[email]" value="{{$item->email}}" required />
					</div>
					<div class="form-group">
						<label>Mobile</label>
						<input type="text" class="form-control" name="data[phone]" value="{{$item->mobile}}" required />
					</div>
					<div class="form-group">
						<label>@lang('Group')</label>
                        <?= HtmlHelper::select(\App\Glossary\UserType::getAll(),'data[type]','class="form-control"','value','display',$item->type,'','---')?>
					</div>
					<div class="form-group">
						<label>Enabled or Disabled</label>
						<select name="is_enabled" class="form-control">
							<option value="1">Enabled</option>
							<option value="0">Disabled</option>
						</select>
					</div>
					<!-- <div class="form-group">
						<label>Longitude</label>
						<input type="text" class="form-control" name="data[longitude]" value="{{$item->longitude}}"/>
					</div>
					<div class="form-group">
						<label>Latitude</label>
						<input type="text" class="form-control" name="data[latitude]" value="{{$item->latitude}}"/>
					</div> -->
					<div class="form-group">
						<label>Chiều cao</label>
						<input type="number" step="0.01" value="{{$item->height}}" class="form-control" name="data[height]" />
					</div>
					<div class="form-group">
						<label>Cân nặng</label>
						<input type="number" step="0.01" value="{{$item->weight}}" class="form-control" name="data[weight]" />
					</div>

					<div class="form-group">
						<label>Tỉnh</label>
						<?= HtmlHelper::select($province,'data[provincial]','class="form-control"','matp','name',$item->provincial,'','---')?>
					</div>
				</div>
				<div class="col-sm-6">

					<div class="form-group">
						<label>Gender</label>
						<?= HtmlHelper::select(\App\Glossary\UserGender::getAll(),'data[provincial]','class="form-control"','value','display',$item->gender)?>
					</div>

					<!-- <div class="form-group">
						<label>Verify</label>
						<select name="data[is_verify]" class="form-control">
							<option value="1" @if($item->is_verify == 1)selected @endif;>Yes</option>
							<option value="0" @if($item->is_verify == 0)selected @endif;>No</option>
						</select>
					</div> -->
					<!-- <div class="form-group">
						<label>Avatar</label>
						<input type="file" class="form-control" name="data[avatar]" />
					</div> -->
				</div>
				<div class="col-sm-12">
					<button type="submit" class="btn btn-primary">@lang('Save')</button>
					<a href="{{url('admin?view=User')}}">
						<button type="button" class="btn btn-primary">@lang('Back')</button>
					</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection