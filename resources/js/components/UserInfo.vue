<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Cập nhật thông tin cá nhân</div>

                    <div class="card-body">
                        <form method="POST" id="user-data">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Họ tên đầy đủ</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control " name="name" v-model="user.name" value="" required autocomplete="name" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Số điện thoại</label>
                                <div class="col-md-6">
                                    <input id="phone" type="phone" class="form-control " name="phone" v-model="user.phone" value="" required autocomplete="phone">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Giới tính</label>
                                <div class="col-md-6">
                                    <div class="form-check" v-for="group in gender">
                                        <input type="radio" class="form-check-input" name="gender" v-model="user.gender" :value="group.value" required >
                                        <label class="form-check-label" >{{group.display}}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Chọn đối tượng</label>
                                <div class="col-md-6">
                                    <div class="form-check" v-for="group in gender">
                                        <input type="radio" class="form-check-input" name="gender_target" v-model="user.gender_target" :value="group.value" required>
                                        <label class="form-check-label" >{{group.display}}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Trạng thái</label>
                                <div class="col-md-6">
                                    <div class="form-check" v-for="state in single_state">
                                        <input type="radio" class="form-check-input" name="single_state" v-model="user.single_state" :value="state.value" required >
                                        <label class="form-check-label" >{{state.display}}</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="height" class="col-md-4 col-form-label text-md-right">Chiều cao(cm)</label>
                                <div class="col-md-6">
                                    <input id="height" type="number" class="form-control " name="height" v-model="user.height" value="" required autocomplete="height">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="weight" class="col-md-4 col-form-label text-md-right">Cân nặng(kg)</label>
                                <div class="col-md-6">
                                    <input id="weight" type="number" class="form-control " name="weight" v-model="user.weight" value="" required autocomplete="weight">
                                </div>
                            </div>


                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="button" @click="update()" class="btn btn-primary">Cập nhật</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-header">Cập nhật câu hỏi <router-link :to="{ name: 'question' }" class="btn btn-primary pull-right">Đến</router-link></div>

                </div>
            </div>
        </div>
    </div>
</template>



<script>
    export default {
        data() {
            return {
                current: 0,
                user: {},
                gender: gender,
                single_state: single_state,
                error: false
            }
        },
        methods: {
            update(){
                self = this;
                axios({
                    method: 'post',
                    url: url+'api/user/update',
                    data: self.user,
                    responseType: 'json'
                })
                .then(function(response){
                    if(response.data.status){
                        self.$router.replace('/question');
                    }else{
                        alert(response.data.message);
                    }
                })
                .catch(function(error) {
                    console.log(error);
                    alert('Có lỗi xảy ra!');
                })
            }            
        },
        mounted() {
            fetch(url + "api/user", {
            method: 'get'
            })
            .then((response) => {
                return response.json()
            })
            .then((jsonData) => {
                this.user = jsonData;
            })
        }
    }
</script>
