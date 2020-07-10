@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <h4 class="m-t-0">@lang('Users')</h4>

                <hr />

                @include('layouts.admin.notice')

                <form name="filterUser" action="{{url('admin?view=User')}}" method="GET">

                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="user_type">Loại User</label>
                                <?= HtmlHelper::select(\App\Glossary\UserType::getAll(),'filter[group_id]','class="form-control"','value','display',isset($filter['group_id']) ? $filter['group_id'] : '','','---')?>
                            </div>
                        </div>


                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Ngày đăng ký</label>
                                <div>
                                    <div class="input-daterange input-group" id="date-range">
                                        <input type="text" class="form-control" name="filter[start]" value="<?= isset($filter['start'])?$filter['start']:null ?>">
                                        <span class="input-group-addon b-0">Tới</span>
                                        <input type="text" class="form-control" name="filter[end]" value="<?= isset($filter['end'])?$filter['end']:null ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tuổi</label>
                                <div>
                                    <div class="input-group" name="age" id="age">
                                        <input type="text" class="form-control" name="filter[age_from]" placeholder="15" value="<?= isset($filter['age_from'])?$filter['age_from']:null ?>">
                                        <span class="input-group-addon b-0">Tới</span>
                                        <input type="text" class="form-control" name="filter[age_to]" placeholder="35" value="<?= isset($filter['age_to'])?$filter['age_to']:null ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="user_type">@lang('Gender')</label>
                                <select class="form-control" id="gender" name="filter[gender]">
                                    <option value="">@lang('Gender')</option>
                                    <option value="M" @if(isset($filter['gender']) && $filter['gender'] == 'M') selected @endif>Nam</option>
                                    <option value="F" @if(isset($filter['gender']) && $filter['gender'] == 'F') selected @endif>Nữ</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-filter-outline"></i> Lọc</button>
                            <a href="{{url('/admin?view=User')}}">
                                <button type="button" class="btn btn-primary">
                                    <i class="mdi mdi-notification-clear-all"></i> Reset
                                </button>
                            </a>
                        </div>
                    </div>

                    <input type="hidden" name="view" value="User">

                </form>

                <div style="height: 20px;"></div>

                <div class="table-responsive">

                    <div class="row">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">
                            <div id="datatable-responsive_filter" class="dataTables_filter">
                                <a href="<?= url('/admin?controller=User&task=create') ?>" type="button"
                                   class="btn btn-primary">
                                    @lang('admin.ADD')
                                </a>
                                <button type="button" onclick="javascrip:alert('Đang nâng cấp ...')"
                                        class="btn btn-primary">@lang('admin.DELETE')
                                </button>
                            </div>

                        </div>
                    </div>

                    <table class="table table-hover mails m-0 table table-actions-bar">
                        <thead>
                        <tr>
                            <th style="min-width: 95px;">
                                <div class="checkbox checkbox-primary checkbox-single m-r-15">
                                    <input id="action-checkbox" type="checkbox">
                                    <label for="action-checkbox"></label>
                                </div>
                            </th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Address</th>
                            <th>Option</th>
                        </tr>
                        </thead>

                        <tbody>


                        @foreach($items AS $item)

                            <tr>
                                <td>
                                    <div class="checkbox checkbox-primary m-r-15">
                                        <input id="checkbox1" type="checkbox">
                                        <label for="checkbox1"></label>
                                    </div>

                                    <img src="{{$item->avatar}}" alt="avatar" title="avatar"
                                         class="img-circle thumb-sm"/>
                                </td>

                                <td>
                                    <a href="{{url('admin?view=User&layout=edit&id='.$item->id)}}">{{$item->name}}</a>
                                </td>

                                <td>
                                    <a href="#" class="text-muted">{{$item->email}}</a>
                                </td>

                                <td>
                                    <b><a href="" class="text-dark"><b>{{$item->phone}}</b></a> </b>
                                </td>

                                <td><?php $tp = array_filter($province,function($p) use ($item){return $item->provincial==$p['matp'];});
                                echo $tp ? reset($tp)['name'] : ''?></td>

                                <td>
                                    <a href="{{url('admin?controller=User&task=block&id='.$item->id)}}">
                                        <button class="btn btn-danger">
                                            @if($item->is_blocked == 1)
                                                Unblock
                                            @else
                                                Block
                                            @endif
                                        </button>
                                    </a>
                                    {{--<a href="{{url('admin?controller=User&task=destroy&id='.$item->id)}}">--}}
                                    {{--<button class="btn btn-sm btn-danger">Delete</button>--}}
                                    {{--</a>--}}
                                </td>

                            </tr>


                        @endforeach


                        </tbody>
                    </table>
                </div>
                {{ $items->links() }}
            </div>
        </div>
    </div>
@endsection


@section('javascript')
    <script type="text/javascript">
        $('.input-daterange').datepicker();
    </script>
@stop
