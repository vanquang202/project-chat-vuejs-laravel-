<template>
    <div class="col-sm-6  row">
        <div v-if="!flagCreate" class="col-sm-12">
            <div class="text-center">
                <h3>Tìm kiếm cuộc trò truyện ... </h3>
            </div>
            <div>
                <div class="row">
                    <div class="col-sm-7">
                        <input v-model="srRoom" type="text" class="form-control " placeholder="Nhập mã code cuộc trò chuyện ... ">
                    </div>
                    <button @click.prevent="searchRoom" class="btn btn-primary col-sm-4" >Tìm kiếm</button>
                </div>

                <div class="row p-4" v-if="dataSearchRoom.name_room" >
                    <p  class="col-sm-6" >  {{ dataSearchRoom.name_room }}  </p>
                    <small class="col-sm-3">{{  count  }} thành viên </small>
                    <div v-if="dataSearchRoom.status == 0 && dataSearchRoom.pass == 1">
                        <input  v-model="passConfirm" type="password" class="form-control" placeholder="Pass">
                    </div>
                    <button v-if="dataSearchRoom.status == 0" @click.prevent="addPeople"  class="btn btn-success col-sm-3 "> Tham gia </button>
                    <p class="alert alert-success" v-else="dataSearchRoom.status == 1" >Đã tham gia</p>
                </div>
            </div>
            <div class="text-center">
                <h3>Hoặc ... </h3>
            </div>
            <div class="text-center m-3">
                <button @click.prevent="createRooms" class="btn btn-success">Tạo cuộc trò truyện mới</button>
            </div>
        </div>
        <div v-if="flagCreate" class="col-sm-12">
            <p style="cursor: pointer" @click="flagCreate = !flagCreate">Close</p>
            <div class="text-center">
                <input v-model="name" type="text" placeholder="Name rooms ..." class="form-control">
                <button @click.prevent="addRoom" class="btn btn-success m-2"  >Tạo</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Room",
    data(){
        return {
            flagCreate : false,
            name : '',
            srRoom : '',
            count : 0,
            passConfirm : '',
            dataSearchRoom : []
        }
    },
    methods:{
        createRooms(){
            this.flagCreate = true
        },
        addRoom(){
            if(this.name.trim() == '' ){
                alert('Không bỏ trống !');
            }else if(this.name.length <= 4 || this.name.length > 50){
                alert('Tên nhóm trong khoảng 4 - 50 ký tự !');
            }else{
                this.addRoomSend();
            }

        },
        searchRoom(){
          if(this.srRoom.trim() == ''){

              alert('Nhập mã code');
          } else if(this.srRoom.length < 5  ){
              alert('Mã code lớn hơn 5 ký tự');
          }else{
              this.srRoomSend();
          }
        },
        addPeople(){

            this.addPeopleSend();
        },
        async addPeopleSend(){
            let formData = new FormData();
            if(this.dataSearchRoom.pass == 1){
                if(this.passConfirm.trim() == ''){
                    alert('Nhập mật khẩu phòng !');
                    return 1;
                }else{
                    formData.append('pass' , this.passConfirm);
                }
            }else{

            }
            formData.append('code' , this.dataSearchRoom.code);
            return await axios.post(this.uri+'api/add-people-room' , formData )
                .then(res => {

                    if(res.data.code == 200 ){
                        this.$router.push({ name: "ChatRoom" , params: { code : res.data.code_room } , query : { id : res.data.id} }).catch(()=>{});
                    }else if(res.data.code == 300){
                        alert(res.data.message);
                    }
                })
        },
        async srRoomSend(){
            let formData = new FormData();
            formData.append('data' , this.srRoom);
            return await axios.post(this.uri+'api/searh-room' , formData )
                .then(res => {

                    if(res.data.code == 300){
                        this.dataSearchRoom = [];
                        alert(res.data.mes);
                    }else{
                        this.dataSearchRoom = res.data.room;
                        this.count = res.data.count;

                    }
                })
        },
        async addRoomSend(){
            let formData = new FormData();
            formData.append('name' , this.name);
            return await axios.post(this.uri+'api/create-room' , formData )
                                .then(res => {
                                    this.flagCreate = false;
                                    if(res.data.code == 200 ){
                                        this.$router.push({ name: "ChatRoom" , params: { code : res.data.code_room } , query : { id : res.data.id}}).catch(()=>{});
                                    }
                                })
        }
    }

}
</script>

<style scoped>

</style>
