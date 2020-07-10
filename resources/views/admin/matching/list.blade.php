@extends('layouts.admin')
@section('content')
<script>
</script>
<h4 class="m-t-0">Câu hỏi</h4>

<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <div id="app">
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6">
                        <div id="datatable-responsive_filter" class="dataTables_filter">
                            <a type="button" class="btn btn-primary" v-on:click="addNew">Add</a>
                            <button type="button" v-on:click="bulkDelete" class="btn btn-primary">Delete</button>
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
                        <th>Câu hỏi</th>
                        <th>Loại</th>
                        <th>Chi tiết</th>
                        <th>Mô tả thêm</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-if="showAddPopup()">
                        <td colspan="6">
                            <form v-on:submit="save" method="post">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="title">Nội dung câu hỏi</label>
                                            <input id="title" type="text" class="form-control" v-model="currentItem.title" name="title">
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Chi tiết</label>
                                            <input id="description" type="text" class="form-control" v-model="currentItem.description" name="description">
                                        </div>
                                        <div class="form-group">
                                            <label for="notes">notes</label>
                                            <input id="notes" type="text" class="form-control" v-model="currentItem.notes" name="notes">
                                        </div>
                                        <div class="form-group">
                                            <label for="type">Loại câu hỏi</label>
                                            <select name="type" id="" class="form-control" v-model="currentItem.type">
                                                <option v-for="t in questionType" :label="t.display" :value="t.value" :key="t.value"></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="" class="pull-left">Câu trả lời</label>
                                        <button v-on:click="addAnswer" class="btn btn-primary pull-right" type="button">Thêm câu trả lời</button>
                                        <table class="table table-hover mails m-0">
                                            <tr v-for="(ans,index) in currentItem.answers">
                                                <td>
                                                    <input type="hidden" v-model="ans.id">
                                                    <input type="text" class="form-control" v-model="ans.content" placeholder="Nội dung câu trả lời">
                                                </td>
                                                <td width="30%">
                                                    <input type="number" class="form-control" v-model.number="ans.point" placeholder="Điểm">
                                                </td>
                                                <td width="10%"><button class="btn btn-danger" type="button" v-on:click="deleteAnswer(index)">X</button></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <input type="hidden" name="id" v-model="currentItem.id"/>
                                <center>
                                    <button type="submit" class="btn btn-primary ">Lưu</button>
                                    <button type="button" v-on:click="closeAddPopup" class="btn btn-danger ">Đóng</button>
                                </center>
                                
                            </form>
                        </td>
                    </tr>
                    <tr v-for="item in items">
                        <td>
                            <div class="checkbox checkbox-primary m-r-15">
                                <input id="checkbox1" type="checkbox">
                                <label for="checkbox1"></label>
                            </div>
                        </td>
                        <td>
                            <a v-on:click="detail(item.id)">@{{ item.title }}</a>
                        </td>
                        <td>@{{ item.type }}</td>
                        <td>@{{ item.description }}</td>
                        <td>@{{ item.notes }}</td>
                        <td><a v-on:click="deleteItem(item.id)" class="btn btn-primary">Delete</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>


        </div>
    </div>
</div>


<script src="{{ asset('js/admin/matching.js') }}"></script>

@endsection
