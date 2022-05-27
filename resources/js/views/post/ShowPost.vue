<template>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-sm-1">
                    <router-link
                        style="text-decoration: none; color: black"
                        :to="{
                            name: 'ProfileViewYou',
                            params: { id: post.user.id },
                        }"
                    >
                        <img
                            style="width: 50px; height: 50px"
                            class="rounded-circle"
                            :src="uri + 'images/' + post.user.image"
                            alt=""
                        />
                    </router-link>

                    <button
                        @click.pr.prevent="incement"
                        style="
                            border: none;
                            background: none;
                            list-style: none;
                            font-size: 40px;
                        "
                    >
                        🔼
                    </button>
                    <p
                        style="font-size: 20px; font-weight: bold"
                        class="text-xxl bold"
                    >
                        {{ post.vote }}
                    </p>
                    <button
                        @click.pr.prevent="decrement"
                        style="
                            border: none;
                            background: none;
                            list-style: none;
                            font-size: 40px;
                        "
                    >
                        🔽
                    </button>
                </div>
                <div class="col-sm-11 row">
                    <div style="overflow: hidden" class="col-sm-10">
                        <h1>{{ post.title }}</h1>
                        <router-link
                            style="text-decoration: none; color: black"
                            :to="{
                                name: 'ProfileViewYou',
                                params: { id: post.user.id },
                            }"
                        >
                            <h4>
                                {{ post.user.name }}
                                <small style="font-size: 13px"
                                    >{{ post.user.email }}
                                    <strong
                                        >Đã đăng vào
                                        {{ post.created_at }}</strong
                                    >
                                </small>
                            </h4>
                        </router-link>

                        <p>👁️‍🗨️ {{ post.view }} | ✅ {{ post.vote }}</p>
                        <hr />
                        <div v-html="post.content"></div>
                        <div>
                            <span
                                style="
                                    background: #ccc;
                                    padding: 5px;
                                    margin-left: 5px;
                                "
                                v-for="(v, i) in post.slug_tag"
                                :key="i"
                            >
                                <router-link
                                    style="text-decoration: none; color: black"
                                    :to="{
                                        name: 'Community',
                                        query: { tag: v },
                                    }"
                                >
                                    {{ v }}
                                </router-link>
                            </span>
                        </div>
                        <br />
                        <hr />
                        <br />

                        <div>
                            <div v-if="post.seri">
                                <h3>Bài viết liên quan</h3>
                                <div class="row">
                                    <div
                                        v-for="(p, i) in post.data_suport"
                                        v-if="p.id != $route.params.id"
                                        :key="i"
                                        class="col-sm-3"
                                    >
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    {{ post.user.name }}
                                                </h5>
                                                <router-link
                                                    style="
                                                        text-decoration: none;
                                                    "
                                                    :to="{
                                                        name: 'ShowPost',
                                                        params: {
                                                            id: p.id,
                                                            name: p.slug_title,
                                                        },
                                                    }"
                                                >
                                                    {{ p.title }}
                                                </router-link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <hr />
                            <br />
                            <div>
                                <h3>Các bài viết khác</h3>
                                <div class="row">
                                    <div
                                        v-for="(p, i) in posts"
                                        v-if="p.id != $route.params.id"
                                        :key="i"
                                        class="col-sm-3"
                                    >
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    {{ p.user.name }}
                                                </h5>
                                                <router-link
                                                    style="
                                                        text-decoration: none;
                                                    "
                                                    :to="{
                                                        name: 'ShowPost',
                                                        params: {
                                                            id: p.id,
                                                            name: p.slug_title,
                                                        },
                                                    }"
                                                >
                                                    {{ p.title }}
                                                </router-link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <comment
                                    @resetComment="resetComment"
                                    :comments="post.comment"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div v-if="post.seri">
                            <h2 class="text-primary">{{ post.seri.name }}</h2>
                            <p v-for="(p, i) in post.data_suport" :key="i">
                                <router-link
                                    style="text-decoration: none"
                                    :to="{
                                        name: 'ShowPost',
                                        params: {
                                            id: p.id,
                                            name: p.slug_title,
                                        },
                                    }"
                                >
                                    {{ p.title }}
                                </router-link>
                            </p>
                        </div>
                        <div>
                            <h3>Các tổ chức được đề xuất</h3>
                            <a href="laravel.com">Laravel</a>
                            <a href="youtube.com">Youtube</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Comment from "../comment/Comment.vue";

export default {
    name: "ShowPost",
    data() {
        return {
            post: {},
            posts: {},
        };
    },
    created() {
        this.getPost();
        this.getAllpost();
    },
    methods: {
        resetComment() {
            this.getPost();
        },
        async getPost() {
            return await axios
                .get(this.uri + "api/post/" + this.$route.params.id)
                .then((data) => {
                    this.post = data.data.data;
                    this.post.data_suport = data.data.data_sp;
                })
                .catch((error) => {
                    // alert(Error);
                });
        },
        async getAllpost() {
            return await axios
                .get(this.uri + "api/post/?limit=4")
                .then((data) => {
                    this.posts = data.data.data.data;
                })
                .catch((error) => {
                    // alert(Error);
                });
        },
        async incement() {
            return await axios
                .get(this.uri + "api/post-increment/" + this.$route.params.id)
                .then((data) => {
                    console.log(data);
                    if (data.data == 0) {
                        alert("Bạn đã vote bài này rồi ! ");
                    } else {
                        this.getPost();
                    }
                })
                .catch((error) => {
                    alert(Error);
                });
        },
        async decrement() {
            return await axios
                .get(this.uri + "api/post-decrement/" + this.$route.params.id)
                .then((data) => {
                    if (data.data == 0) {
                        alert("Bạn đã vote bài này rồi ! ");
                    } else {
                        this.getPost();
                    }
                })
                .catch((error) => {
                    alert(Error);
                });
        },
    },
    watch: {
        $route() {
            this.getPost();
        },
    },
    components: {
        comment: Comment,
    },
};
</script>

<style scoped></style>
