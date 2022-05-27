<template>
    <div>
    <!--        -->
        <div class="row l-b-m-u">
            <div class="col-sm-3 p-4 height-full "   id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button
                    v-if="gruops.length > 0"
                    v-for="(gruop , index) in gruops"
                    :key="gruop.code_room"
                    :class="{ 'nav-link' : true , 'active' : (flagBtn == gruop.code_room ) ,'btn ' : true}"
                    @click="clickRedirectRoom(gruop.code_room,gruop.id)"
                    style="width:100% !important;"
                    >
                    {{ gruop.name_room }}
                    <br>
                    <small v-if="gruop.statusChat == 0" style="color:black"><strong style=""> * {{ gruop.name_room }} </strong> : {{ gruop.contentChatEnd}}</small>
                    <small v-else style="color:#ccc"><span> {{ gruop.name_room }} </span> : {{ gruop.contentChatEnd }}</small>
                </button>
                <button
                        v-if="users[0] != null"
                        v-for="(user , index) in users"
                        :key="index"
                        :class="{ 'nav-link' : true , 'active' : (flagBtn == user.id ) ,'btn ' : true}"
                        @click="clickRedirect(user.id)"
                        style="width:100% !important;"
                        aria-selected="true">
                        <img v-if="user.image !== 'default.png'" style="width:40px ; height : 40px ; border-radius : 50%"  :src="uri+'images/'+user.image" alt="">
                        <img v-else style="width:40px ; height : 40px ; border-radius : 50%"  :src="'https://ui-avatars.com/api/?background=random&name=' + user.name" alt="">
                        {{ user.name }} <strong style="color:orangered ; font-weight:bold" v-if="user.flagSeen == 0">🔵</strong>
                </button>
            </div>
            <div class="col-sm-9 tab-content" id="v-pills-tabContent">
                <router-view></router-view>
            </div>
        </div>
    <!--        -->
    </div>
</template>

<script>
export default {
    name: "Main",
    data(){
      return {
          flagBtn: 0,
          users: [],
          gruops: [],
          count: 0,
      }
    },
    created (){
        if(this.count == 0){
            this.getData();
            this.count ++;
        }
        const that = this ;
        Echo.channel('notifi-'+i_g)
            .listen('NotificationC', function () {
                that.getData();
            })
        Echo.channel('notifi-'+id_me)
            .listen('NotificationC', function () {
                that.getData();
            })
    },
    methods:{
        async  getData()
        {
            return await axios.get(this.uri+'api/user')
                .then(res => {
                    this.users = res.data.data;
                    this.gruops = res.data.room_gruop;

                })
        },
        clickRedirect(id)
        {
            this.flagBtn = id ;
            this.$router.push({ name: "ChatUser" , params: { id : id }}).catch(()=>{});
        },
        clickRedirectRoom(code,id)
        {
            this.flagBtn = code ;
            this.$router.push({ name: "ChatRoom" , params: { code : code }  , query : { id : id } }).catch(()=>{});
        }

    }
}
</script>

<style scoped>
    .active{
        color: black ;
        font-weight: bold ;
        background-color: #ccc;
    }
    .l-b-m-u{
        max-height:500px ;
        overflow: auto ;
    }
</style>
