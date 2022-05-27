<template>
    <div class="container">
        <div class="row m-4 p-4">
            <div v-if="!flagEdit" class="col-sm-4">
                <div class="outer-circle">
                    <div class="inner-circle">
                        <img
                            width="100%"
                            :src="uri + 'images/' + getImage"
                            alt=""
                        />
                    </div>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <div v-if="flagEdit" class="col-sm-4">
                <div class="outer-circle">
                    <div class="inner-circle">
                        <img
                            v-if="!changeImgProfile"
                            width="100%"
                            :src="uri + 'images/' + getImage"
                            alt=""
                        />
                        <img
                            v-if="changeImgProfile"
                            width="100%"
                            :src="urlImgProfile"
                            alt=""
                        />
                    </div>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <label for="changeF" class="btn btn-outline-info">
                    Thay đổi ảnh
                </label>
                <input
                    @change="changePreviewImage"
                    style="display: none"
                    type="file"
                    id="changeF"
                    class="form-control"
                />
            </div>

            <div v-if="!flagEdit" class="col-sm-6">
                <div class="p-3">
                    <h2>{{ getNameLocal }}</h2>
                    <small>{{ getEmail }}</small>
                    <p>
                        👁️‍🗨️ {{ view }} | ✅ {{ vote }} | 🕵️‍♀️{{
                            followers ? followers.length : 0
                        }}
                    </p>
                    <hr />
                    <p style="white-space: pre-line">
                        <strong>Tiểu sử : </strong> <br />
                        {{ getDetailsLocal }}
                    </p>
                </div>
            </div>

            <div v-if="flagEdit" class="col-sm-6">
                <div class="p-3">
                    <h2>
                        <input
                            class="form-control"
                            v-model="getNameLocal"
                            type="text"
                        />
                    </h2>
                    <small>{{ getEmail }}</small>
                    <hr />
                    <br />
                    <textarea
                        class="form-control textarea"
                        v-model="getDetailsLocal"
                    ></textarea>
                </div>
            </div>

            <div v-if="!flagEdit" class="col-sm-2 p-1" style="">
                <button
                    @click.prevent="flagEdit = true"
                    class="btn btn-primary"
                >
                    Edit
                </button>
            </div>
        </div>
        <div v-if="flagEdit" class="col-sm-2 p-1" style="">
            <button @click.prevent="saveProfile" class="btn btn-primary">
                Lưu
            </button>
            <button
                @click.prevent="flagEdit = false"
                class="btn btn-outline-primary"
            >
                Hủy
            </button>
        </div>

        <div v-if="!flagCrPost && !flagCrSeri">
            <button
                class="btn btn-outline-primary"
                @click.prevent="flagCrPost = !flagCrPost"
            >
                Tạo bài viết
            </button>
            <button
                class="btn btn-outline-primary"
                @click.prevent="flagCrSeri = !flagCrSeri"
            >
                Tạo seri bài viết
            </button>
        </div>

        <div v-else-if="flagCrPost" class="anim">
            <button
                class="btn btn-danger"
                @click.prevent="flagCrPost = !flagCrPost"
            >
                Hủy
            </button>
            <button
                style="float: right"
                class="d-flex btn btn-primary m-3"
                v-if="data.flag"
            >
                Đang xuất bản ...
            </button>
            <button
                style="float: right"
                class="d-flex btn btn-primary m-3"
                @click.prevent="editPost"
                v-else-if="data.flagUpdate"
            >
                Lưu và xuất bản
            </button>
            <button
                style="float: right"
                class="d-flex btn btn-primary m-3"
                v-else
                @click.prevent="createPost"
            >
                Xuất bản
            </button>
            <div v-if="seris.length > 0">
                <select class="form-control" v-model="data.seri_id">
                    <option value="0">🛠️ Chọn seri hoặc không ! 🛠️</option>
                    <option
                        v-for="(seri, index) in seris"
                        :key="index"
                        :value="seri.id"
                        class="form-control"
                    >
                        💧 {{ seri.name }} 💧
                    </option>
                </select>
            </div>
            <br />
            <input
                type="text"
                class="form-control"
                v-model="data.title"
                placeholder="Tiêu đề"
            />
            <br />
            <input
                type="text"
                class="form-control"
                v-model="data.tag"
                placeholder="Tag bài viết , cách nhau bằng dấu ' , ' "
            />
            <br />
            <ckeditor
                :editor="editor"
                v-model="data.editorData"
                :config="editorConfig"
            ></ckeditor>
        </div>

        <div v-else-if="flagCrSeri" class="anim">
            <button
                class="btn btn-danger"
                @click.prevent="flagCrSeri = !flagCrSeri"
            >
                Hủy
            </button>
            <button
                style="float: right"
                class="d-flex btn btn-primary m-3"
                v-if="dataSeri.flag"
            >
                Đang xuất bản ...
            </button>

            <button
                style="float: right"
                class="d-flex btn btn-primary m-3"
                v-else
                @click.prevent="createSeri"
            >
                Xuất bản
            </button>

            <input
                type="text"
                class="form-control"
                v-model="dataSeri.name_seri"
                placeholder="Tên seri ..."
            />
            <br />
            <br />
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
                                <p>
                                    👁️‍🗨️ {{ post.view }} | ✅ {{ post.vote }} |
                                    <button
                                        class="btn-cs"
                                        @click.prevent="removePost(post.id)"
                                    >
                                        🗑️Xóa
                                    </button>
                                    |
                                    <button
                                        class="btn-cs"
                                        @click.prevent="updatePost(post.id)"
                                    >
                                        🔧Sửa
                                    </button>
                                </p>
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
                                <a
                                    >🛠️ {{ seri.name }}
                                    <button
                                        class="btn-cs"
                                        @click.prevent="removeSeri(seri.id)"
                                    >
                                        🗑️Xóa
                                    </button>
                                    |

                                    <button
                                        data-bs-toggle="modal"
                                        data-bs-target="#exampleModal"
                                        class="btn-cs"
                                        @click.prevent="updateSeri(seri.id)"
                                    >
                                        🔧Sửa
                                    </button></a
                                >
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

            <!-- Modal -->
            <div
                class="modal fade"
                id="exampleModal"
                tabindex="-1"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                Update seri
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            ></button>
                        </div>
                        <div class="modal-body">
                            <input
                                v-model="dataUpdateSeri.name_seri"
                                type="text"
                                class="form-control"
                            />
                            <div>
                                <div
                                    v-for="(p, i) in dataUpdateSeri.posts"
                                    :key="i"
                                    class="form-check"
                                >
                                    <input
                                        v-model="dataUpdateSeri.checkPosts"
                                        :value="p.id"
                                        class="form-check-input"
                                        type="checkbox"
                                        id="flexCheckDefault"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="flexRadioDefault1"
                                    >
                                        {{ p.title }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                            >
                                Thoát
                            </button>
                            <button
                                v-if="!dataUpdateSeri.flag"
                                @click.prevent="saveUpdateSeri"
                                type="button"
                                class="btn btn-primary"
                            >
                                Lưu thay đổi
                            </button>
                            <button
                                v-else
                                type="button"
                                class="btn btn-primary"
                            >
                                Đang lưu thay đổi ...
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

export default {
    name: "Profile",
    data() {
        return {
            flagEdit: false,
            flagCrPost: false,
            flagCrSeri: false,
            changeImgProfile: false,
            urlImgProfile: "",
            content: "",
            posts: {},
            followers: [],
            seris: {},
            page: [],
            view: 0,
            vote: 0,
            current_page: null,
            dataSeri: {
                name_seri: "",
                flag: false,
            },
            dataUpdateSeri: {
                name_seri: "",
                flag: false,
                posts: null,
                id: null,
                checkPosts: [],
            },
            data: {
                title: "",
                flag: false,
                flagUpdate: false,
                post_id: null,
                tag: "",
                seri_id: 0,
                editorData: "<p>Thêm bài viết mới của bạn tại đây  </p>",
            },
            editor: ClassicEditor,

            editorConfig: {},
        };
    },
    created() {
        this.getPost();
        this.getSeri();
    },
    computed: {
        ...mapGetters(["getName", "getEmail", "getImage", "getDetails"]),
        getNameLocal: {
            get() {
                return this.getName;
            },
            set(value) {
                this.$store.dispatch("setName", value);
            },
        },
        getDetailsLocal: {
            get() {
                return this.getDetails;
            },
            set(value) {
                this.$store.dispatch("setDetails", value);
            },
        },
    },
    methods: {
        async saveUpdateSeri() {
            console.log(this.dataUpdateSeri);
            this.dataUpdateSeri.flag = true;
            return await axios
                .put(this.uri + "api/seri/" + this.dataUpdateSeri.id, {
                    data: {
                        name: this.dataUpdateSeri.name_seri,
                    },
                    data_add_post: this.dataUpdateSeri.checkPosts,
                })
                .then((data) => {
                    this.dataUpdateSeri.flag = false;
                    this.dataUpdateSeri.checkPosts = [];
                    document.querySelector(".btn-close").click();
                    this.getSeri();
                    this.getPost();
                });
        },
        async removeSeri(id) {
            if (confirm("Bạn chắc chắn muốn xóa bài viết này chứ ")) {
                return await axios
                    .delete(this.uri + "api/seri/" + id)
                    .then((data) => {
                        this.getSeri();
                        this.getPost();
                    })
                    .catch((error) => {
                        alert("Errors");
                    });
            }
        },
        async updateSeri(id) {
            return await axios
                .get(this.uri + "api/seri/" + id + "/edit")
                .then((data) => {
                    this.dataUpdateSeri.name_seri = data.data.data.name;
                    this.dataUpdateSeri.id = data.data.data.id;
                    this.dataUpdateSeri.posts = data.data.post;
                    console.log(this.dataUpdateSeri);
                });
        },
        async removePost(id) {
            if (confirm("Bạn chắc chắn muốn xóa bài viết này chứ ")) {
                return await axios
                    .delete(this.uri + "api/post/" + id)
                    .then((data) => {
                        this.getPost();
                    })
                    .catch((error) => {
                        alert("Errors");
                    });
            }
        },
        async updatePost(id) {
            return await axios
                .get(this.uri + "api/post/" + id)
                .then((data) => {
                    this.flagCrPost = true;
                    this.data = {
                        title: data.data.data.title,
                        flagUpdate: true,
                        post_id: data.data.data.id,
                        tag: data.data.data.tag,
                        seri_id: data.data.data.seri_id,
                        editorData: data.data.data.content,
                    };
                    console.log(this.data);
                })
                .catch((error) => {
                    alert("Errors");
                });
        },
        async editPost() {
            let data = {};
            let flagSeri = false;
            if (this.data.seri_id !== null) {
                flagSeri = true;
                data = {
                    title: this.data.title,
                    content: this.data.editorData,
                    tag: this.data.tag,
                    seri_id: this.data.seri_id,
                };
            } else {
                data = {
                    title: this.data.title,
                    content: this.data.editorData,
                    tag: this.data.tag,
                };
            }
            return await axios
                .put(this.uri + "api/post/" + this.data.post_id, {
                    data: data,
                })
                .then((data) => {
                    if (flagSeri) {
                        this.getSeri();
                    }
                    this.getPost();
                    this.data = {
                        title: "",
                        flagUpdate: false,
                        tag: "",
                        editorData:
                            "<p>Thêm bài viết mới của bạn tại đây  </p>",
                    };
                })
                .catch((error) => {
                    alert("Errors");
                });
        },
        saveProfile() {
            this.saveApiProfile();
        },
        changePreviewImage(e) {
            const file = e.target.files[0];
            this.changeImgProfile = true;
            this.urlImgProfile = URL.createObjectURL(file);
        },
        async viewPage(i) {
            return await axios
                .get(this.uri + "api/post-me?page=" + i)
                .then((data) => {
                    this.posts = data.data.data.data;
                    this.current_page = data.data.data.current_page;
                })
                .catch(() => {
                    alert("Error");
                });
        },
        async createSeri() {
            this.dataSeri.flag = true;
            return await axios
                .post(this.uri + "api/seri", {
                    data: {
                        name: this.dataSeri.name_seri,
                        user_id: id_me,
                    },
                })
                .then((data) => {
                    this.dataSeri = {
                        name_seri: "",
                    };
                    this.dataSeri.flag = false;
                    this.getSeri();
                })
                .finally(() => {
                    this.dataSeri.flag = false;
                });
        },
        async createPost() {
            let data = {};
            let flagSeri = false;
            this.data.flag = true;
            if (this.data.seri_id !== 0) {
                flagSeri = true;
                data = {
                    title: this.data.title,
                    content: this.data.editorData,
                    tag: this.data.tag,
                    seri_id: this.data.seri_id,
                    user_id: id_me,
                };
            } else {
                data = {
                    title: this.data.title,
                    content: this.data.editorData,
                    tag: this.data.tag,
                    user_id: id_me,
                };
            }
            return await axios
                .post(this.uri + "api/post", {
                    data: data,
                })
                .then((data) => {
                    if (flagSeri) {
                        this.getSeri();
                    }
                    this.data = {
                        title: "",
                        tag: "",
                        editorData:
                            "<p>Thêm bài viết mới của bạn tại đây  </p>",
                    };
                    this.getPost();
                })
                .finally(() => {
                    this.data.flag = false;
                });
        },
        async getPost() {
            return await axios
                .get(this.uri + "api/post-me")
                .then((data) => {
                    this.page = [];
                    this.posts = data.data.data.data;
                    this.followers = data.data.follower;
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
                .get(this.uri + "api/get-seri-me")
                .then((data) => {
                    this.seris = data.data.data;
                })
                .catch(() => {
                    alert("Error");
                });
        },
        async saveApiProfile() {
            let formData = new FormData();
            let file = document.getElementById("changeF").files[0];
            if (this.getDetailsLocal.length > 200) {
                alert("Tiểu sử nhỏ hơn 200 kí tự !");
                return 0;
            }
            if (file) {
                formData.append("image", file);
            }
            formData.append("name", this.getNameLocal);
            formData.append("details", this.getDetailsLocal);

            return await axios
                .post(this.uri + "api/save-me", formData)
                .then((res) => {
                    this.flagEdit = false;
                    this.$store.dispatch("setImage", res.data.imageNew);
                });
        },
    },
};
</script>

<style scoped>
.anim {
    transition: opacity 0.3s;
}
.btn-cs {
    background: none;
    border: none;
    list-style: none;
}
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
textarea {
    font-size: 24px;
    border: 10px solid black;
    padding: 2rem 1rem;
    min-height: 3em;
    resize: both;
    background: #ffffff33;
    border-image: url("data:image/svg+xml;charset=utf-8,%3Csvg width='100' height='100' viewBox='0 0 100 100' fill='none' xmlns='http://www.w3.org/2000/svg'%3E %3Cstyle%3Epath%7Banimation:stroke 5s infinite linear%3B%7D%40keyframes stroke%7Bto%7Bstroke-dashoffset:776%3B%7D%7D%3C/style%3E%3ClinearGradient id='g' x1='0%25' y1='0%25' x2='0%25' y2='100%25'%3E%3Cstop offset='0%25' stop-color='%232d3561' /%3E%3Cstop offset='25%25' stop-color='%23c05c7e' /%3E%3Cstop offset='50%25' stop-color='%23f3826f' /%3E%3Cstop offset='100%25' stop-color='%23ffb961' /%3E%3C/linearGradient%3E %3Cpath d='M1.5 1.5 l97 0l0 97l-97 0 l0 -97' stroke-linecap='square' stroke='url(%23g)' stroke-width='3' stroke-dasharray='388'/%3E %3C/svg%3E")
        1;
}
input {
    font-size: 24px;
    border: 10px solid black;
    resize: both;
    background: #ffffff33;
    border-image: url("data:image/svg+xml;charset=utf-8,%3Csvg width='100' height='100' viewBox='0 0 100 100' fill='none' xmlns='http://www.w3.org/2000/svg'%3E %3Cstyle%3Epath%7Banimation:stroke 5s infinite linear%3B%7D%40keyframes stroke%7Bto%7Bstroke-dashoffset:776%3B%7D%7D%3C/style%3E%3ClinearGradient id='g' x1='0%25' y1='0%25' x2='0%25' y2='100%25'%3E%3Cstop offset='0%25' stop-color='%232d3561' /%3E%3Cstop offset='25%25' stop-color='%23c05c7e' /%3E%3Cstop offset='50%25' stop-color='%23f3826f' /%3E%3Cstop offset='100%25' stop-color='%23ffb961' /%3E%3C/linearGradient%3E %3Cpath d='M1.5 1.5 l97 0l0 97l-97 0 l0 -97' stroke-linecap='square' stroke='url(%23g)' stroke-width='3' stroke-dasharray='388'/%3E %3C/svg%3E")
        1;
}
</style>
