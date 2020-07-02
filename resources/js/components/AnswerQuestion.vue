<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" v-if="items.length">
                    <div class="card-header">{{currentItem.title}}</div>

                    <div class="card-body">
                        <b-list-group>
                            <b-list-group-item
                            v-for="(answer, index) in currentItem.answers"
                            :key="index"
                            @click.prevent="selectAnswer(answer.id)"
                            v-bind:class="getAnswerClass(answer.id)"
                            >
                            {{answer.content}}
                            </b-list-group-item>
                        </b-list-group>
                        <center class="mt-1">
                            <button type="button" @click="sendAnswer()" class="btn btn-primary">Trả lời</button>
                        </center>

                        <nav aria-label="Danh sách câu hỏi">
                            <ul class="pagination mt-2 pb-0 mb-0">
                                <li :class="getpageIndexClass(i)" v-for="i in items.length"><a class="page-link" href="#" @click="jumpToQuestion(i)">{{ i }}</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>



<script>
    export default {
        data() {
            return {
                items: [],
                loading: true,
                index: 0,
                currentItem: {},
                error: false
            }
        },
        methods: {
            next() {
                this.index++;
                this.currentItem = this.items[index];
            },
            selectAnswer(answer_id){
                this.currentItem.answer = answer_id;
            },
            sendAnswer(){
                self = this;
                axios({
                    method: 'post',
                    url: url+'api/question/answer',
                    data: {
                        question_id: self.currentItem.id,
                        answer_id: self.currentItem.answer
                    },
                    responseType: 'json'
                })
                .then(function(response){
                    if(response.data.status){
                        if(self.index == (self.items.length - 1)){
                            //câu hỏi cuối
                            alert('Bạn đã hoàn thành phần câu hỏi');
                            return;
                        }
                        self.index++;
                        console.log(self);
                        self.currentItem = self.items[self.index];
                    }else{
                        alert(response.data.message);
                    }
                })
                .catch(function(error) {
                    console.log(error);
                    alert('Có lỗi xảy ra!');
                })
            },
            getAnswerClass(id){
                let css = 'list-group-item';
                if(id == this.currentItem.answer){
                    css += ' active';
                }
                return css;
            },
            getpageIndexClass(pageNumber){
                let css = "page-item";
                if(this.index==pageNumber-1){
                    css += " active";
                }
                return css;
            },
            jumpToQuestion(index){
                this.index = index-1;
                this.currentItem = this.items[this.index];
            }
        },
        mounted() {
            fetch(url + "api/questions", {
            method: 'get'
            })
            .then((response) => {
                return response.json()
            })
            .then((jsonData) => {
                this.items = jsonData;
                this.currentItem = this.items[this.index];
            })
        }
    }
</script>
