@extends('layouts.admin')
@section('content')
<script>
</script>
<h4 class="m-t-0">Matching</h4>

<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <div id="app">
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6">
                        <div id="datatable-responsive_filter" class="dataTables_filter">
                            <button v-on:click="refresh" class="btn btn-primary" type="button">@lang('Refresh')</button>
                        </div>
                    </div>
                </div>
                <table class="table table-hover mails m-0 table-actions-bar">
                    <thead>
                    <tr>
                        <th>
                            <div class="checkbox checkbox-primary checkbox-single m-r-15">
                                <input id="action-checkbox" type="checkbox"/>
                                <label for="action-checkbox"></label>
                            </div>
                        </th>
                        <th>Tên</th>
                        <th>Giới tính</th>
                        <th>Ngày sinh</th>
                        <th>Điện thoại</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="item in items">
                        <td>
                            <div class="checkbox checkbox-primary m-r-15">
                                <input id="checkbox1" type="checkbox">
                                <label for="checkbox1"></label>
                            </div>
                        </td>
                        <td>
                            <a>@{{ item.name }}</a>
                        </td>
                        <td>@{{ showGender(item.gender) }}</td>
                        <td>@{{ item.birthday }}</td>
                        <td>@{{ item.phone }}</td>
                        <td>
                            <div class="row mb-1 " v-for="match in item.match">@{{ match.name }} @{{ showGender(match.gender) }} @{{ match.birthday }} 
                                <button type="button" class="btn btn-primary pull-right">Match</button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>


        </div>
    </div>
</div>
<style>
.mb-1{margin-top:1px;}
</style>

<script>
var gender = <?= json_encode(\App\Glossary\UserGender::getAll())?>;

const app = new Vue({
    el: '#app',
    data() {
        return {
            items: [],
            loading: true,
            total: false,
            offset: 0,
            error: false
        }
      },
    mounted: function() {
        this.getItems(0);
        this.getTotal();
      },
    methods: {
        getItems(offset){
            fetch(url + "admin?controller=Matching&task=getItems&offset="+offset, {
                method: 'get'
              })
            .then((response) => {
                return response.json()
            })
            .then((jsonData) => {
                this.items = jsonData;
            });
            
        },
        getTotal(){
            //paging
            fetch(url + "admin?controller=Matching&task=getTotal", {
                method: 'get'
              })
            .then((response) => {
                this.total = response;
            });
        },
        refresh(){
            fetch(url + "admin?controller=Matching&task=refresh", {
                method: 'get'
              })
            .then((response) => {
                return response.json()
            })
            .then((jsonData) => {
                if(jsonData.data > 0){
                    this.getItems(0);
                    this.getTotal();
                }
            })
        },
        showGender(e){
            for(let i in gender){
                if(gender[i].value==e){
                    return gender[i].display;
                }
            }
            return '';
        }
    }
});
</script>
@endsection
