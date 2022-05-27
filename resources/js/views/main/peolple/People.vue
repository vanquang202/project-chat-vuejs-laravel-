<template>
    <div style="padding-left:10%" class="col-sm-6">
        <div style="max-height: 300px ; overflow: auto">
            <div>
                <h3>Mọi người</h3>
                <input v-model="srPeoPle" type="text" class="form-control" placeholder="Tìm kiếm ...">
                <button  @click.prevent="searchPeople" class="btn btn-info " style="width:100%">Tìm kiếm </button>
            </div>
            <div>
                <div class="row">

                    <div style="margin-top: 20px ; cursor: pointer" v-for="(you,index) in userDeloyFriend" :key="index" @click.self="viewProfile(you.user.id)" class="col-sm-12 row ">
                        <div class="col-sm-2">
                            <img  v-if="you.user.image !== 'default.png'" width="100%" :src="uri+'images/'+you.user.image" alt="">
                             <img v-else style="width:40px ; height : 40px ; border-radius : 50%"  :src="'https://ui-avatars.com/api/?background=random&name=' + you.user.name" alt="">
                        </div>
                        <div class="col-sm-4">
                            {{ you.user.name }}
                        </div>
                        <div class="col-sm-6">
                            <button @click.prevent="clickAddFriend(you.user.id)" v-if="you.status == 0" class="btn btn-info">Kết bạn</button>
                            <button @click.prevent v-if="you.status == 1" class="btn btn-outline-success"> Đã gửi kết bạn  </button>
                            <button @click.prevent="clickRemoveAddFriend(you.user.id)" v-if="you.status == 1" class="btn btn-danger"> X </button>
                        </div>
                        <br>
                        <hr>
                    </div>

                </div>
            </div>
        </div>

        <div style="max-height: 300px ; overflow: auto ; margin-top : 20px">
            <div><h3>Lời mời kết bạn </h3></div>
            <div>
                <div class="row">

                    <div v-if="userDeloyMe[0] !== null">

                        <div  style="margin-top: 20px " v-for="(you,index) in userDeloyMe" :key="index" class="col-sm-12 row ">
                            <div class="col-sm-2">
                                <img  v-if="you.image !== 'default.png'" width="100%" :src="uri+'images/'+ (you.image)   " alt="">
                                <img v-else style="width:40px ; height : 40px ; border-radius : 50%"  :src="'https://ui-avatars.com/api/?background=random&name=' + you.name" alt="">
                            </div>
                            <div class="col-sm-4">
                                {{ you.name   }}
                            </div>
                            <div class="col-sm-6">
                                <button @click="addFriendMe(you.id )" class="btn btn-primary">Chấp nhận</button>
                                <button @click="removeFriendMe(you.id  )" class="btn btn-danger">Hủy</button>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "People",
    data(){
        return {
            userDeloyFriend : [],
            userDeloyMe : [    ],
            srPeoPle : '',
            countFriend : 0,
        }
    },
    created(){
        if(this.countFriend == 0){
            this.getUsers();
            this.getUsersDeloyFriend();
            this.countFriend ++;
        };

        const that = this ;
        Echo.channel('notifi-'+id_me)
            .listen('NotificationC', function () {
                console.log(this.$route);
                if(this.$route !== undefined) {
                    that.getUsers();
                    that.getUsersDeloyFriend();
                }
        })
    },
    methods: {
        viewProfile(id){
            this.$router.push({ name: "ProfileViewYou" , params: { id : id}});
        },
        clickAddFriend(id){
            this.addFriend(id);
        },
        clickRemoveAddFriend(id){
            this.addFriend(id);
        },
        //
        addFriendMe(id){
            this.addFriendSendMe(id);
        },
        removeFriendMe(id) {
            this.removeFriendSendMe(id);
        },
         async searchPeople(){
          if(this.srPeoPle.trim() == ''){
              alert('Nhập tên bạn cần tìm kiếm ');
          }else{
              let formData = new FormData();
              formData.append('name' , this.srPeoPle);
              return await axios.post(this.uri + 'api/all-user-deloy-friend' , formData)
                                    .then(res => {
                                        this.userDeloyFriend = res.data.data;
                                    })
          }

        },
        async addFriend(id){
            let formData = new FormData();
            formData.append('id' , id)
            return await  axios.post(this.uri + 'api/add-user-deloy-friend',formData)
                .then(res => {
                    this.getUsers();
                    this.getUsersDeloyFriend();
                })
        },
        async getUsers(){
            return await axios.get(this.uri + 'api/all-user-deloy-friend')
                .then(res => {
                    this.userDeloyFriend = res.data.data;

                })
                .catch(err => {

                })
        },
        async getUsersDeloyFriend(){
            return await  axios.get(this.uri + 'api/all-user-deloy-me')
                .then(res => {

                    if(res.data.data.length > 0){
                        this.userDeloyMe = res.data.data ;
                    }else{
                        this.userDeloyMe = [];
                    }

                })
                .catch(err => {

                })
        },
        async addFriendSendMe(id){
            let formData = new FormData();
            formData.append('id',id);
            return await axios.post(this.uri+'api/add-user-deloy-me',formData)
                .then(res => {
                    this.getUsers();
                    this.getUsersDeloyFriend();
                })
        },
        async removeFriendSendMe(id){
            let formData = new FormData();
            formData.append('id',id);
            return await axios.post(this.uri+'api/remove-user-deloy-me',formData)
                .then(res => {

                })
        }

    }
}
</script>

<style scoped>

</style>
