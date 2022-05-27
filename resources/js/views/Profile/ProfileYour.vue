<template>
    <div>
        <div class="row m-4 p-4">
            <div class="col-sm-4">
                <div class="outer-circle">
                    <div class="inner-circle">
                        <img
                            width="100%"
                            :src="uri + 'images/' + you.image"
                            alt=""
                        />
                    </div>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="p-3">
                    <h2>
                        {{ you.name }}
                        <button
                            v-if="!flag_follower"
                            @click.prevent="addFollower"
                            class="btn btn-outline-primary"
                        >
                            + Theo dõi
                        </button>
                        <button
                            v-else
                            @click.prevent="unFollower"
                            class="btn btn-outline-success"
                        >
                            Đang theo dõi
                        </button>
                    </h2>
                    <small>{{ you.email }}</small>
                    <p>
                        👁️‍🗨️ {{ view }} | ✅ {{ vote }} | 🕵️‍♀️{{
                            followers ? followers.length : 0
                        }}
                    </p>
                    <hr />
                    <p style="white-space: pre-line">
                        <strong>Tiểu sử : </strong> <br />
                        {{ details }}
                    </p>
                </div>
            </div>
        </div>

        <div
            style="overflow: hidden"
            v-if="posts !== []"
            class="container-fluid"
        >
            <div class="row">
                <div class="col-sm-8">
                    <div
                        v-for="(post, index) in posts"
                        :key="index"
                        class="p-3"
                    >
                        <div class="row">
                            <div class="col-sm-1">
                                <img
                                    style="width: 40px; height: 40px"
                                    class="bg-info rounded-circle"
                                    :src="uri + 'images/' + post.user.image"
                                    alt=""
                                />
                            </div>
                            <div class="col-sm-9">
                                <small
                                    >{{ post.user.name }} |
                                    {{ post.created_at }}</small
                                >
                                <p
                                    class="bg-success text-light p-3"
                                    v-if="post.seri"
                                >
                                    Seri <strong>{{ post.seri.name }}</strong>
                                </p>
                                <h3 style="cursor: pointer">
                                    <router-link
                                        style="text-decoration: none"
                                        :to="{
                                            name: 'ShowPost',
                                            params: {
                                                id: post.id,
                                                name: post.slug_title,
                                            },
                                        }"
                                    >
                                        {{ post.title }}
                                    </router-link>
                                </h3>
                                <p>👁️‍🗨️ {{ post.view }} | ✅ {{ post.vote }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h2>Seri :</h2>
                    <div v-if="seris.length > 0">
                        <div
                            v-for="(seri, index) in seris"
                            :key="index"
                            class="p-3"
                        >
                            <div style="margin-left: 20px">
                                <a>🛠️ {{ seri.name }}</a>
                                <p
                                    style="margin-left: 20px"
                                    v-for="(post, i) in seri.post"
                                    :key="i"
                                    href=""
                                >
                                    --
                                    <router-link
                                        style="text-decoration: none"
                                        :to="{
                                            name: 'ShowPost',
                                            params: {
                                                id: post.id,
                                                name: post.slug_title,
                                            },
                                        }"
                                    >
                                        {{ post.title }}
                                    </router-link>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div v-else>Không có seri !</div>
                </div>
            </div>

            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item">
                        <a
                            class="page-link"
                            @click.prevent="
                                viewPage(
                                    current_page - 1 >= 1 ? current_page - 1 : 1
                                )
                            "
                            href="#"
                            aria-label="Previous"
                        >
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li
                        v-for="(data, index) in page"
                        :key="index"
                        :class="{
                            'page-item': true,
                            active: current_page == data,
                        }"
                    >
                        <a
                            class="page-link"
                            @click.prevent="viewPage(data)"
                            href="#"
                            >{{ data }}</a
                        >
                    </li>
                    <li class="page-item">
                        <a
                            class="page-link"
                            @click.prevent="
                                viewPage(
                                    current_page + 1 <= page[page.length - 1]
                                        ? current_page + 1
                                        : page[page.length - 1]
                                )
                            "
                            href="#"
                            aria-label="Next"
                        >
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    name: "ProfileYou",
    data() {
        return {
            you: [],
            details: "",
            posts: {},
            seris: {},
            page: [],
            followers: 0,
            flag_follower: null,
            view: 0,
            vote: 0,
            current_page: null,
        };
    },
    created() {
        this.getProfileYou();
        this.getPost();
        this.getSeri();
    },
    methods: {
        async addFollower() {
            try {
                var data = await axios.get(
                    this.uri + "api/add-follower/" + this.$route.params.id
                );
                await this.getProfileYou();
            } catch (error) {
                console.error("Errors", error);
            }
        },
        async unFollower() {
            if (!confirm("Bạn chắc chứ !")) return;
            try {
                var data = await axios.get(
                    this.uri + "api/un-follower/" + this.$route.params.id
                );
                await this.getProfileYou();
            } catch (error) {
                console.error("Errors", error);
            }
        },
        async getProfileYou() {
            return await axios
                .get(this.uri + "api/get-you/" + this.$route.params.id)
                .then((res) => {
                    this.you = res.data.you;
                    this.details = res.data.details;
                    this.followers = res.data.you.follower.folower;
                    this.flag_follower = res.data.flag_follower;
                    // console.log(res.data.you.follower);
                });
        },
        async getPost() {
            return await axios
                .get(this.uri + "api/post-you/" + this.$route.params.id)
                .then((data) => {
                    this.page = [];
                    this.posts = data.data.data.data;
                    this.view = data.data.sum_view;
                    this.vote = data.data.sum_vote;
                    for (let i = 1; i <= data.data.data.last_page; i++) {
                        this.page.push(i);
                    }
                    this.current_page = data.data.data.current_page;
                })
                .catch(() => {
                    alert("Error");
                });
        },
        async getSeri() {
            return await axios
                .get(this.uri + "api/get-seri-you/" + this.$route.params.id)
                .then((data) => {
                    this.seris = data.data.data;
                })
                .catch(() => {
                    alert("Error");
                });
        },
        async viewPage(i) {
            return await axios
                .get(
                    this.uri +
                        "api/post-you/" +
                        this.$route.params.id +
                        "?page=" +
                        i
                )
                .then((data) => {
                    this.posts = data.data.data.data;
                    this.current_page = data.data.data.current_page;
                })
                .catch(() => {
                    alert("Error");
                });
        },
    },
};
</script>

<style scoped>
img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
}
.outer-circle {
    position: relative;
    height: 200px;
    width: 200px;
    background: linear-gradient(#14ffe9, #ffeb3b, #ff00e0);
    border-radius: 50%;
}
.outer-circle:hover {
    animation: rotate 2.5s linear infinite;
}
.outer-circle span {
    position: absolute;
    height: 200px;
    width: 200px;
    background: linear-gradient(#14ffe9, #ffeb3b, #ff00e0);
    border-radius: 50%;
}

span:nth-child(1) {
    filter: blur(5px);
}

span:nth-child(2) {
    filter: blur(10px);
}

span:nth-child(3) {
    filter: blur(25px);
}

span:nth-child(4) {
    filter: blur(150px);
}

.inner-circle {
    height: 180px;
    width: 180px;
    position: absolute;
    top: 10px;
    left: 10px;
    border-radius: 50%;
    z-index: 9;
}

@keyframes rotate {
    0% {
        filter: hue-rotate(0deg);
    }
    100% {
        filter: hue-rotate(360deg);
    }
}
</style>
