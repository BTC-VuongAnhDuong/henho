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
                <li><a href="{{url('/admin?view=User')}}"><i class="mdi mdi-account-multiple-plus"></i>Người dùng</a></li>
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