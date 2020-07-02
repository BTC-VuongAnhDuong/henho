<?php
use App\Glossary\UserType;
?>
<div class="scrollbar-wrapper">
    <div>
        <button type="button" class="button-menu-mobile btn-mobile-view visible-xs visible-sm">
            <i class="mdi mdi-close"></i>
        </button>
        <!-- User Detail box -->
        <div class="user-details">
            <div class="pull-left">
                <img src="assets/images/users/avatar-1.jpg" alt="" class="thumb-md img-circle">
            </div>
            <div class="user-info">
                <a href="{{URL::to('/admin?view=Profile')}}">{{$currentUser->name}}</a>
                <p class="text-muted m-0">{{UserType::getDisplay($currentUser->type)}}</p>
            </div>
        </div>
        <!--- End User Detail box -->

        <!-- Left Menu Start -->

        <ul class="metisMenu nav" id="side-menu">
            <li>
                <a href="{{url('/admin')}}"><i class="ti-home"></i> @lang('Dashboard') </a>
            </li>

            @can(config('auth.action.LIST_USERS'))

            <li>
                <a href="javascript: void(0);" aria-expanded="true">
                    <i class="mdi mdi-account-multiple-plus"></i> @lang('admin.ACCOUNT')
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav-second-level nav" aria-expanded="true">
                    <li><a href="{{url('/admin?view=User')}}">
                            <i class="ti-person"></i>@lang('admin.LIST')</a>
                    </li>
                    @can(config('auth.action.LIST_USER_GROUP'))
                    <li>
                        <a href="{{url('/admin?view=UserGroup')}}"><i class="ti-person"></i>@lang('admin.ACCOUNT_GROUP')</a>
                    </li>
                    @endcan
                    @can(config('auth.action.LIST_JOBS'))
                    <li><a href="{{url('/admin?view=Job')}}"><i class="ti-person"></i>@lang('admin.JOB')</a></li>
                    @endcan
                    @can(config('auth.action.LIST_PROVINCE_GROUP'))
                    <li>
                        <a href="{{url('/admin?view=ProvinceGroup')}}"><i
                                    class="ti-person"></i>@lang('admin.PROVINCE_GROUP')</a>
                    </li>
                    @endcan
                    @can(config('auth.action.LIST_HOBBY'))
                    <li><a href="{{url('/admin?view=Hobby')}}"><i class="mdi-account"></i>@lang('admin.HOBBY')</a></li>
                    @endcan
                    @can(config('auth.action.LIST_EDUCATION'))
                    <li><a href="{{url('/admin?view=Education')}}"><i class="ti-person"></i>@lang('admin.EDUCATION')</a></li>
                    @endcan
                </ul>
            </li>

            @endcan

            <li><a href="{{url('/admin?view=UserMatching')}}"><i class="mdi mdi-account"></i>Matching</a></li>
            <li><a href="{{url('/admin?view=Question')}}"><i class="ti-book"></i>Câu hỏi</a></li>

            @can([config('auth.action.CONFIG_GENERAL')])
            <li>
                <a href="{{url('/admin?view=Configuration')}}"><i class="mdi mdi-settings"></i> @lang('admin.SETTING')
                    <span class="fa arrow"></span></a>
                <ul class="nav-second-level nav" aria-expanded="true">
                    <li><a href="{{url('/admin?view=Configuration&option=general')}}"><i
                                    class="ti-person"></i>@lang('admin.GENERAL_CONFIG')</a></li>
                </ul>
            </li>
            @endcan

        </ul>



    </div>
</div><!--Scrollbar wrapper-->