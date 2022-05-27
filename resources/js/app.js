require("./bootstrap");

import Vue from "vue";
import VueRouter from "vue-router";
import VueX from "vuex";
// import VueAxios from "vue-axios";
const VueAxios = require("vue-axios/dist/vue-axios.common.min");
import axios from "axios";
import CKEditor from "@ckeditor/ckeditor5-vue2";
import ExampleComponent from "./components/ExampleComponent";
import { store } from "./store";
import VueSocialauth from "vue-social-auth";
window.Vue = require("vue").default;

Vue.use(VueSocialauth, {
    providers: {
        google: {
            clientId:
                "492481953658-bnorvv9ssh31nimtjgsda7h67tg17mpa.apps.googleusercontent.com",
            client_secret: "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",
            redirectUri: "http://127.0.0.1:8000/callback",
        },
    },
});

Vue.use(VueRouter);
Vue.use(VueX);
Vue.use(VueAxios, axios);
Vue.use(EmojiPicker);
Vue.use(CKEditor);
Vue.mixin({
    data() {
        return {
            uri: "http://127.0.0.1:8000/",
            countGetMe: 0,
        };
    },
    created() {
        let count = 0;
        let data = this.$store.state.countUser;
        if (data == 0) {
            this.getMeProfile();
            data++;
            this.$store.dispatch("setCountUser", data);
        }
        const that = this;
        Echo.channel("notifi-" + id_me).listen("NotificationC", function () {
            that.getMeProfile();
        });
        // Echo.channel('private-notify-user-show.'+id_me)
        //     .listen('NotfiUser', function () {
        //         count ++ ;
        //         if(count == 1) {
        //             toastr.success('Bạn có tin nhắn mới', 'Thông báo');
        //         };
        //     })
    },
    methods: {
        async getMeProfile() {
            return await axios.get(this.uri + "api/get-me").then((res) => {
                this.$store.dispatch("setName", res.data.user.name);
                this.$store.dispatch("setImage", res.data.user.image);
                this.$store.dispatch("setEmail", res.data.user.email);
                this.$store.dispatch("setDetails", res.data.details);
            });
        },
    },
});

// Vue router
import { routers } from "./routes";
const route = new VueRouter({
    linkExactActiveClass: "link-router-actived-by-quangvq812",
    mode: "history",
    routes: routers,
});

const app = new Vue({
    el: "#app",
    router: route,
    store: store,
    render: (h) => h(ExampleComponent),
});
