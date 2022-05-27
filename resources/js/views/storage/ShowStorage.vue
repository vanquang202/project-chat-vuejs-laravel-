<template>
    <div class="modal-stg">
        <button @click.prevent="removeStorageMain" class="btn btn-danger">Xóa kho lưu trữ </button>
        <button class="btn btn-outline-primary back-st" @click.prevent="back">Back</button>
        <div class="m-3 p-2">
            <div class="my-2">
                <form action="">
                    <label for="file">Tải lên file của bạn </label> <br>
                    <small> * Loại file : zip  </small> <br>
                    <small> * Dung lượng : 10.000 kb  </small> <br>
                    <input @change="changeFile" type="file" id="file" class="form-control" >
                    <br>
                    <input type="text" class="form-control" v-model="name" placeholder="Đặt tên cho file ...">
                    <br>
                    <input type="reset" value="Reset" class="btn btn-outline-info">
                    <button v-if="!flag" @click.prevent="uploadfile" class="btn btn-primary">Tải lên </button>
                    <button v-else class="btn btn-primary">Đang tải ... </button>
                </form>
            </div>
            <hr>
        </div>
        <div class="">
            <h1>Thư mục</h1>
            <div>
                <p v-for="(f,i) in files" :key="i" >
                    <button style="width : 50px ; background:none ; border: none ; list-style: none;">
                        <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="folder" class="svg-inline--fa fa-folder fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M464 128H272l-54.63-54.63c-6-6-14.14-9.37-22.63-9.37H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V176c0-26.51-21.49-48-48-48zm0 272H48V112h140.12l54.63 54.63c6 6 14.14 9.37 22.63 9.37H464v224z"></path></svg>
                    </button>
                    {{ f.name }} - {{ f.size }} bytes - {{  f.created_at }}
                    <a target="_blank" :href="uri + 'api/download-storage?name='+f.url" class="badge rounded-pill bg-success">Tải xuống </a>
                    <button v-if="!flagRemove" @click.prevent="removeStorage(f.id)" class="badge rounded-pill bg-warning"> Xóa </button>
                    <button v-else   class="badge rounded-pill bg-warning"> Đang xóa ... </button>
                </p>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return{
            flag: false,
            flagRemove: false,
            name: '',
            files: [],
        }
    },
    created() {
        this.getFileStorage();
    },
    methods: {
        async removeStorageMain()
        {
            if(confirm("Bạn có chắc muốn xóa kho lưu trữ này chứ ? \n Các thư mục thuộc kho lưu trữ này sẽ bị xóa !"))
            {
                return await axios.delete(this.uri + 'api/storage/'+this.$route.params.id)
                                .then(res => {
                                    this.$router.push({name : 'Storage'});
                                })
                                .catch(err => {

                                })
            }
        },
        async removeStorage(id)
        {
            this.flagRemove = true;
            return await axios.get(this.uri + 'api/remove-storage/'+id)
                                .then(res  => {
                                    this.getFileStorage();
                                })
                                .catch(err => {
                                    alert(err);
                                })
                                .finally(() => {
                                    this.flagRemove = false;
                                });
        },
        async getFileStorage(){
            return await axios.get(this.uri + 'api/storage/'+this.$route.params.id)
                                .then(res => {
                                    this.files = res.data.data;
                                })
                                .catch(err => {
                                    alert(err);
                                })
        },
        async uploadfile()
        {
            this.flag = true;
            let data = new FormData();
            let file = document.getElementById('file').files[0];
            data.append('file',file);
            data.append('name',this.name);
            data.append('id',this.$route.params.id);
            return await axios.post(this.uri + 'api/upload-storage', data)
                                .then(res => {
                                    this.name = '';
                                    this.getFileStorage();
                                })
                                .catch(err =>{
                                    alert(err);
                                })
                                .finally(() => {
                                     this.flag = false;
                                })
        },
        changeFile(e)
        {
           return  this.name = e.target.files[0].name;
        },
        back()
        {
            this.$router.go(-1);
        }
    },

}
</script>
<style scoped>
    .back-st {
        position: absolute;
        top: 2%;
        right: 2%;
    }
    .modal-stg{
        z-index: 999;
        overflow: auto;
        box-shadow: 2px 2px 20px rgb(0, 0, 0);
        background-color:rgb(255, 255, 255);
        width:80%;
        height:80%;
        padding: 20px ;
        border-radius: 20px;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50% , -50%);
    }
</style>
