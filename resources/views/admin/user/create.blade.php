@extends('layouts.admin')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<h4 class="m-b-20 header-title">Create New User</h4>

			@include('layouts.admin.notice')

			<form enctype='multipart/form-data' method="POST" action="{{url('admin?controller=User&task=store')}}">
				{{ csrf_field() }}
				<div class="col-sm-6">
					<div class="form-group">
						<label>Name <span>*</span></label>
						<input type="text" class="form-control" name="data[name]" required />
					</div>
					<div class="form-group">
						<label>Email <span>*</span></label>
						<input type="email" class="form-control" name="data[email]" required />
					</div>
					<div class="form-group">
						<label>Password <span>*</span></label>
						<input type="password" class="form-control" name="data[password]" required/>
					</div>
					<div class="form-group">
						<label>Mobile</label>
						<input type="number" class="form-control" name="data[mobile]" required />
					</div>
					@if($currentUser->group->key == config('auth.usergroup.agency'))
						@php
						$group = \App\UserGroup::getGroupByKey(config('auth.usergroup.agency_employee'));
						@endphp
						<input type="hidden" class="form-control" name="data[group_id]" value="{{ $group->id }}" />
						<input type="hidden" class="form-control" name="data[parent_id]" value="{{ $currentUser->id }}" />

						<div class="form-group">
							<label>Nhân viên quán</label>

							@php
							$agencyList = \App\Agency::getListAgencyByUserId($currentUser->id);
							@endphp

							<select name="agency_employee[agency_id]" class="form-control" required>
								@foreach($agencyList AS $value)
								<option value="{{ $value->id }}">{{ $value->name }}</option>
								@endforeach
							</select>


						</div>
					@else

					<div class="form-group">
						<label>Group ID</label>
						{{--<input type="number" class="form-control" name="data[group_id]" />--}}
						<?php BookproHtml::select_user_groups('data[group_id]') ?>
					</div>

					@endif

					<div class="form-group">
						<label>Longitude</label>
						<input type="text" class="form-control" name="data[longitude]" />
					</div>
					<div class="form-group">
						<label>Latitude</label>
						<input type="text" class="form-control" name="data[latitude]" />
					</div>
					<div class="form-group">
						<label>Credit</label>
						<input type="text" class="form-control" name="data[credit]" />
					</div>
					<div class="form-group">
						<label>Chiều cao</label>
						<input type="number" step="0.01" class="form-control" name="data[height]" />
					</div>
					<div class="form-group">
						<label>Cân nặng</label>
						<input type="number" step="0.01" class="form-control" name="data[weight]" />
					</div>
					<div class="form-group">
						<label>Học vấn</label>

						<select name="data[education]" class="form-control">

							@foreach(App\Education::all() AS $value)

								<option value="{{$value->id}}">{{$value->name}}</option>

							@endforeach

						</select>

					</div>

					<div class="form-group">
						<label>Tỉnh</label>

						<select name="data[province_id]" class="form-control">
							<option value="">Chọn tỉnh</option>
							@foreach($province AS $value)
								<option value="{{$value->matp}}">{{$value->name}}</option>
							@endforeach
						</select>

					</div>

				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label>Provider</label>
						<input type="text" class="form-control" name="data[provider]" />
					</div>
					<div class="form-group">
						<label>Provider ID</label>
						<input type="text" class="form-control" name="data[provider_id]" />
					</div>
					<div class="form-group">
						<label>Admin Role</label>
						<select name="data[is_admin]" class="form-control">  
							<option value="0">No</option>
							<option value="1">Yes</option>
						</select>
					</div>

					<div class="form-group">
						<label>Gender</label>
						<select name="data[gender]" class="form-control">  
							<option value="M">Male</option>
							<option value="F">Female</option>
						</select>
					</div>

					<div class="form-group">
						<label>Verify</label>
						<select name="is_verify" class="form-control">
							<option value="1">Yes</option>
							<option value="0">No</option>
						</select>
					</div>

					<div class="form-group">
						<label>Nghề nghiệp</label>
						{{\App\Job::select_job('data[job]')}}
					</div>

					<div class="form-group">
						<label>Sở thích</label>

						@foreach(\App\Hobby::all() AS $value)

							<div class="checkbox checkbox-info">
								<input id="checkbox{{$value->id}}" value="{{$value->id}}" type="checkbox" name="favourite[]">
								<label for="checkbox{{$value->id}}">{{$value->name}}</label>
							</div>

						@endforeach

					</div>

					<div class="form-group">
						<label>Avatar</label>
						<input type="file" class="form-control" name="avatar" />
					</div>

				</div>

				<div class="col-sm-12">
					<button type="submit" class="btn btn-primary">
						@lang('admin.SUBMIT')
					</button>
				</div>

			</form>
		</div>
	</div>
</div>
@endsection


