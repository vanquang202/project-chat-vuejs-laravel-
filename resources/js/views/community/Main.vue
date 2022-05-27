<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-9">
            <button @click.prevent="getAllpost('')" :class="{ 'btn' : true , 'btn btn-primary' : !flag_fl}">Bài viết mới </button>
            <button @click.prevent="getAllpostFl" :class="{ 'btn' : true , 'btn btn-primary' : flag_fl}">Đang follow</button>
                <div v-for="(post,i) in posts" :key="i" class="m-2 p-3">
                    <router-link   style="text-decoration: none ; color:black"  :to="{ name:'ProfileViewYou' , params: { id : post.user.id  }   }">
                        <p>  <img  style="width:30px ; height: 30px" class="rounded-circle" :src="uri + 'images/' + post.user.image" alt="" /> {{ post.user.name }} </p>
                    </router-link>

                    <h4>
                        <router-link   style="text-decoration: none ; color:black"  :to="{ name:'ShowPost' , params: { id : post.id , name : post.slug_title}   }">
                            {{ post.title }}
                        </router-link>
                    </h4>
                    <small>👁️‍🗨️ {{ post.view }} | ✅ {{ post.vote }} - {{ post.created_at}}</small>

                    <span style="background:#ccc ; padding:5px ; margin-left: 5px ;" v-for="(v,i) in post.slug_tag" :key="i">
                          <router-link   style="text-decoration: none ; color:black"  :to="{ name:'Community'  , query : { tag: v}  }">
                            {{ v }}
                        </router-link>
                    </span>

                    <hr>
                </div>
            </div>
            <div class="col-sm-3">
                <div>
                    <div class="px-1 py-1">
                        <h4>Bài viết quan tâm </h4>
                        <div class=" my-2 p-2" v-for="(p,k) in posts_vote" :key="k">
                            <h5>
                                <router-link   style="text-decoration: none ; color:black"  :to="{ name:'ShowPost' , params: { id : p.id , name : p.slug_title}   }">
                                    {{ p.title }}
                                </router-link>
                                <br>
                                <small>👁️‍🗨️ {{ p.view }} | ✅ {{ p.vote }} - {{ p.created_at}}</small>
                                <p style="color:#ccc">Tác giả : {{ p.user.name }}</p>
                                <span style="background:#ccc ; padding:5px ; margin-left: 5px ;" v-for="(v,i) in p.slug_tag" :key="i">
                                    <router-link   style="text-decoration: none ; color:black"  :to="{ name:'Community'  , query : { tag: v}  }">
                                        {{ v }}
                                    </router-link>
                                </span>
                            </h5>
                        </div>
                    </div>
                    <hr>

                    <div class="px-1 py-1">
                        <h4>Bài viết xem nhiều  </h4>
                        <div class=" my-2 p-2" v-for="(p,k) in posts_view" :key="k">
                            <h5>
                                <router-link   style="text-decoration: none ; color:black"  :to="{ name:'ShowPost' , params: { id : p.id , name : p.slug_title}   }">
                                    {{ p.title }}
                                </router-link>
                                <br>
                                <small>👁️‍🗨️ {{ p.view }} | ✅ {{ p.vote }} - {{ p.created_at}}</small>
                                <p style="color:#ccc">Tác giả : {{ p.user.name }}</p>
                                <span style="background:#ccc ; padding:5px ; margin-left: 5px ;" v-for="(v,i) in p.slug_tag" :key="i">
                                    <router-link   style="text-decoration: none ; color:black"  :to="{ name:'Community'  , query : { tag: v}  }">
                                        {{ v }}
                                    </router-link>
                                </span>
                            </h5>
                        </div>
                    </div>

                    <div class="px-2 ">
                        <h4>Tác giả nổi bật   </h4>
                        <router-link class="px-2"  style="text-decoration: none ; color:black"  :to="{ name:'ProfileViewYou' , params: { id : top_user_one_start.id  }   }">
                            <p>  <img  style="width:30px ; height: 30px" class="rounded-circle" :src="uri + 'images/' + top_user_one_start.image" alt="" /> 🌟 {{ top_user_one_start.name }} <small>🖍️ {{ top_user_one_start.post_count}}</small></p>
                        </router-link>
                    </div>

                </div>
            </div>
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" @click.prevent="viewPage((current_page - 1) >= 1 ? (current_page - 1) : 1)" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li v-for="(data,index) in page" :key="index" :class="{'page-item' : true, 'active' : current_page == data}"><a class="page-link" @click.prevent="viewPage(data)" href="#">{{ data }}</a></li>
                <li class="page-item">
                    <a class="page-link" @click.prevent="viewPage(((current_page + 1) <= page[page.length - 1] ? (current_page + 1) : page[page.length - 1]))" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
export default {
    name: "Main",
    data(){
        return {
            posts:{},
            page:[],
            posts_vote: [],
            posts_view: [],
            top_user_one_start: null,
            current_page: 0,
            flag_fl: false,
        }
    },
    created() {
        this.getAllpost();
    },
    methods: {
        async getAllpostFl(){
            this.flag_fl = true;
              return await axios.get(this.uri + 'api/post/?limit=5&follower')
                .then(data => {
                    this.posts = data.data.data.data;
                    this.page = [];
                    for (let i = 1 ; i <= data.data.data.last_page ; i++){
                        this.page.push(i);
                    }
                    this.current_page = data.data.data.current_page;
                })
                .catch(error => {
                    alert(Error);
                })
        },
        async viewPage(i){
            var uriAppend = '';
            if( this.flag_fl){
                uriAppend = '&follower';
            }
            return await axios.get(this.uri + 'api/post?limit=5&page='+i+uriAppend)
                .then(data => {
                    this.posts = data.data.data.data;
                    this.current_page = data.data.data.current_page;
                })
                .catch(() => {
                    alert('Error')
                })
        },
        async getAllpost(tag = ''){
             this.flag_fl = false;
            return await axios.get(this.uri + 'api/post/?limit=5&tag='+tag)
                .then(data => {
                    this.posts = data.data.data.data;
                    this.posts_vote = data.data.post_vote;
                    this.posts_view = data.data.post_view;
                    this.top_user_one_start = data.data.top_one[0];
                    this.page = [];
                    for (let i = 1 ; i <= data.data.data.last_page ; i++){
                        this.page.push(i);
                    }
                    this.current_page = data.data.data.current_page;
                })
                .catch(error => {
                    alert(Error);
                })
        },
    },
    watch: {
        '$route.query.tag'(){
            this.getAllpost(this.$route.query.tag);
        }
    }
}
</script>

<style scoped>

</style>
