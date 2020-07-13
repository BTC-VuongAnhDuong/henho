
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