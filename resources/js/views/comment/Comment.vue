<template>
    <div>
        <hr />
        <br />
        <h3>Bình luận :</h3>
        <div>
            <ckeditor
                :editor="editor"
                v-model="data.editorData"
                :config="editorConfig"
            ></ckeditor>
            <br />
            <button
                class="btn btn-outline-primary"
                @click.prevent="commentSend"
            >
                Bình luận
            </button>
        </div>
        <hr />
        <div v-if="comments">
            <div v-for="(v, i) in comments" :key="i" class="row">
                <div class="col-sm-1">
                    <button
                        @click.pr.prevent="incement(v.id)"
                        style="
                            border: none;
                            background: none;
                            list-style: none;
                            font-size: 25px;
                        "
                    >
                        🔼
                    </button>
                    <p
                        style="font-size: 20px; font-weight: bold"
                        class="text-xxl bold"
                    >
                        👋 {{ v.vote }}
                    </p>
                    <button
                        @click.pr.prevent="decrement(v.id)"
                        style="
                            border: none;
                            background: none;
                            list-style: none;
                            font-size: 25px;
                        "
                    >
                        🔽
                    </button>
                </div>
                <div class="col-sm-11">
                    <h3>
                        <img
                            style="width: 50px; height: 50px"
                            class="rounded-circle"
                            :src="uri + 'images/' + v.user.image"
                            alt=""
                        />
                        {{ v.user.name }}
                        <small style="float: right; font-size: 15px">
                            {{ v.created_at }}
                        </small>
                    </h3>
                    <div
                        v-if="!flag_update"
                        style="
                            margin-left: 20px;
                            background-color: #ccc;
                            padding: 10px;
                        "
                        v-html="v.content"
                    ></div>
                    <div v-else>
                        <ckeditor
                            :editor="editor"
                            v-model="comment.content"
                            :config="editorConfig"
                        ></ckeditor>
                        <br />
                        <button
                            @click="updateComment(v.id)"
                            class="btn btn-primary"
                        >
                            Cập nhật bình luận
                        </button>
                        <button
                            @click="cancelEditComment"
                            class="btn btn-outline-primary"
                        >
                            Hủy
                        </button>
                    </div>
                    <p style="cursor: pointer">Trả lời</p>

                    <small
                        >@{{ v.user.email }} |
                        <span
                            style="cursor: pointer"
                            class="p-2"
                            v-if="id_me == v.user.id"
                            @click="removeComments(v.id)"
                            >🛠️ Xóa
                        </span>
                        or
                        <span
                            style="cursor: pointer"
                            class="p-2"
                            v-if="id_me == v.user.id"
                            @click="editComments(v.id)"
                            >🛠️ Sửa
                        </span></small
                    >
                    <ckeditor
                        :editor="editor"
                        v-model="v.editorData"
                        :config="editorConfig"
                    ></ckeditor>
                    <button
                        class="btn btn-outline-primary"
                        @click="reply(v.id, v.editorData)"
                    >
                        Trả lời
                    </button>
                </div>
                <div v-if="v.comment" class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-11">
                        <hr />
                        <h5>🖍️ Câu trả lời 🖍️</h5>
                        <br />
                        <div
                            v-for="(vD, iD) in v.comment"
                            :key="iD"
                            class="row"
                        >
                            <div class="col-sm-1">
                                <button
                                    @click.prevent.prevent="incement(vD.id)"
                                    style="
                                        border: none;
                                        background: none;
                                        list-style: none;
                                        font-size: 25px;
                                    "
                                >
                                    🔼
                                </button>
                                <p
                                    style="font-size: 20px; font-weight: bold"
                                    class="text-xxl bold"
                                >
                                    👋 {{ vD.vote }}
                                </p>
                                <button
                                    @click.prevent.prevent="decrement(vD.id)"
                                    style="
                                        border: none;
                                        background: none;
                                        list-style: none;
                                        font-size: 25px;
                                    "
                                >
                                    🔽
                                </button>
                            </div>
                            <div class="col-sm-11">
                                <h3>
                                    <img
                                        style="width: 50px; height: 50px"
                                        class="rounded-circle"
                                        :src="uri + 'images/' + vD.user.image"
                                        alt=""
                                    />
                                    {{ vD.user.name }}
                                    <small
                                        style="float: right; font-size: 15px"
                                    >
                                        {{ vD.created_at }}
                                    </small>
                                </h3>
                                <div
                                    style="
                                        margin-left: 20px;
                                        background-color: #ccc;
                                        padding: 10px;
                                    "
                                    v-html="vD.content"
                                ></div>
                            </div>
                            <small
                                >@{{ vD.user.email }} |
                                <span
                                    class="p-2"
                                    v-if="id_me == vD.user.id"
                                    style="cursor: pointer"
                                    @click="removeComments(vD.id)"
                                    >🛠️ Xóa
                                </span>
                                or
                                <span
                                    style="cursor: pointer"
                                    class="p-2"
                                    v-if="id_me == vD.user.id"
                                    >🛠️ Sửa
                                </span></small
                            >
                            <hr />
                        </div>
                    </div>
                </div>
                <hr />
            </div>
        </div>
    </div>
</template>

<script>
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { comment } from "postcss";

export default {
    name: "Comment",
    created() {},
    data() {
        return {
            editor: ClassicEditor,
            id_me: id_me,
            data: {
                editorData: "<p> Xóa bình luận mặc định để bình luận <3</p>",
            },
            editorConfig: {},
            comment: null,
            flag_update: false,
        };
    },
    props: {
        comments: {
            type: Array | null,
        },
    },
    methods: {
        async removeComments(id) {
            return await axios
                .delete(this.uri + "api/comment/" + id)
                .then((data) => {
                    if (data.data == 1) {
                        this.$emit("resetComment");
                    } else {
                        alert("Có lỗi xảy ra , không thể xóa !");
                    }
                })
                .catch((error) => {
                    alert("Error");
                });
        },
        async editComments(id) {
            return await axios
                .get(this.uri + "api/comment/" + id)
                .then((data) => {
                    if (data.data.status == false) alert(data.data.payload);
                    this.comment = data.data.payload;
                    this.flag_update = true;
                })
                .catch((error) => {
                    alert("Error");
                });
        },
        async updateComment(id) {
            return await axios
                .put(this.uri + "api/comment/" + id, {
                    content: this.comment.content,
                })
                .then((data) => {
                    if (data.data.payload === "error") alert(data.data.payload);
                    if (data.data.payload == false) alert(data.data.payload);
                    this.$emit("resetComment");
                    this.cancelEditComment();
                })
                .catch((error) => {
                    alert("Error");
                });
        },
        cancelEditComment() {
            this.comment = null;
            this.flag_update = false;
        },
        async reply(i, data) {
            return await axios
                .post(this.uri + "api/comment", {
                    data: {
                        content: data,
                        user_id: id_me,
                        post_id: this.$route.params.id,
                        comment_id: i,
                    },
                })
                .then((data) => {
                    this.$emit("resetComment");
                })
                .catch((error) => {
                    alert("Error");
                });
        },
        async incement(id) {
            return await axios
                .get(this.uri + "api/comment-increment/" + id)
                .then((data) => {
                    if (data.data == 0) {
                        alert("Bạn đã vote bình luận này ");
                    } else {
                        this.$emit("resetComment");
                    }
                });
        },
        async decrement(id) {
            return await axios
                .get(this.uri + "api/comment-decrement/" + id)
                .then((data) => {
                    if (data.data == 0) {
                        alert("Bạn đã vote bình luận này ");
                    } else {
                        this.$emit("resetComment");
                    }
                });
        },
        async commentSend() {
            return await axios
                .post(this.uri + "api/comment", {
                    data: {
                        content: this.data.editorData,
                        user_id: id_me,
                        post_id: this.$route.params.id,
                    },
                })
                .then((data) => {
                    this.data.editorData = "";
                    this.$emit("resetComment");
                })
                .catch((error) => {
                    alert("error");
                });
        },
    },
    watch: {
        id_me(data, dataOld) {
            this.id_me = dataOld;
        },
    },
};
</script>

<style scoped></style>
