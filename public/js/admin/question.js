
const app = new Vue({
    el: '#app',
    data() {
        return {
            items: [],
            loading: true,
            new: false,
            edit: false,
            currentItem: {},
            error: false
        }
      },
    mounted: function() {
        fetch(url + "admin?controller=Question&task=getItems", {
          method: 'get'
        })
          .then((response) => {
            return response.json()
          })
          .then((jsonData) => {
            this.items = jsonData;
          })
      },
    methods: {
        addNew(){
            this.currentItem = {
                title:'',
                description: '',
                notes: '',
                type: '',
                id: '',
                answers: []
            };
            this.new = true;
        },
        detail(id){
            for (i in this.items){
                if(this.items[i].id==id){
                    this.currentItem = this.items[i];
                    break;
                }
            }
            this.edit= true;
        },
        save(e){
            e.preventDefault();
            self = this;
            axios({
                method: 'post',
                url: url+'admin?controller=Question&task=store',
                data: this.currentItem,
                responseType: 'json'
            })
            .then(function(response){
                if(response.data.status){
                    if(self.currentItem.id != ''){
                        //update
                        for (i in self.items){
                            if(self.items[i].id==id){
                                self.items[i] = self.currentItem;
                                break;
                            }
                        }
                    }else{
                        //insert
                        self.items.push(response.data.data);
                        self.edit=false;
                        self.new=false;
                    }
                }else{
                    alert(response.data.message);
                }
            })
            .catch(function(error) {
                console.log(error);
                alert('Error');
                self.error = 'Error';
            })
        },
        deleteItem(id){
            if(confirm('Bạn có chắc chắn muốn xóa?')){
                fetch(url+'admin?controller=Question&task=destroy&id='+id, {
                    method: 'get'
                  })
                .then((response) => {
                    return response.json()
                })
                .then((jsonData) => {
                    console.log(this.items);
                    if(jsonData.status){
                        for (i in this.items){
                            if(this.items[i].id==id){
                                this.items.splice(i, 1);
                                break;
                            }
                        }
                    }else{
                        alert('delete failed');
                    }
                }).catch(function(error){
                    alert('Delete failed');
                })
            }
        },
        bulkDelete(e){

        },
        showAddPopup(e){
            return this.edit || this.new;
        },
        closeAddPopup(){
            this.edit=false;
            this.new=false;
        },
        addAnswer(){
            this.currentItem.answers.push({content: '',point:''});
        },
        deleteAnswer(index){
            this.currentItem.answers.splice(index,1);
        }
    }
});
