// import  Main from '../views/main/Main';
// import  Chat from '../views/main/Chat';
// import Profile from "../views/Profile/Profile";
// import ProfileYou from "../views/Profile/ProfileYour";
// import ContentChat from "../views/main/ContentChat";
// import ChatRoom from "../views/main/ChatRoom";

// const Profile = (importFile) => {
//     require.ensure([ ] , () => {
//         importFile(require('../views/Profile/Profile.vue'));
//     })
// }
// const ProfileYou = (importFile) => {
//     require.ensure([ ] , () => {
//         importFile(require('../views/Profile/ProfileYour.vue'));
//     })
// }
// const ContentChat = (importFile) => {
//     require.ensure([ ] , () => {
//         importFile(require('../views/main/ContentChat.vue'));
//     }, 'chat')
// }
// const Main = (importFile) => {
//     require.ensure([ ] , () => {
//         importFile(require('../views/main/Main.vue'));
//     }, 'chat')
// }
// const Chat = (importFile) => {
//     require.ensure([ ] , () => {
//         importFile(require('../views/main/Chat.vue'));
//     }, 'chat')
// }

const routers = [
    {
        path: "/chat",
        name: "Chat",
        component: () =>
            import(/* webpackChunkName: "Chat" */ "../views/main/Main"),
        children: [
            {
                path: "/",
                name: "ContentChat",
                component: () =>
                    import(
                        /* webpackChunkName: "ContentChat" */ "../views/main/ContentChat.vue"
                    ),
            },
            {
                path: ":id",
                name: "ChatUser",
                component: () =>
                    import(
                        /* webpackChunkName: "ChatUser" */ "../views/main/Chat"
                    ),
            },
            {
                path: "/room/:code",
                name: "ChatRoom",
                component: () =>
                    import(
                        /* webpackChunkName: "ChatRoom" */ "../views/main/ChatRoom"
                    ),
            },
        ],
    },
    {
        path: "/profile",
        name: "Profile",
        component: () =>
            import(
                /* webpackChunkName: "Profile" */ "../views/Profile/Profile"
            ),
    },
    {
        beforeEnter(to, from, next) {
            if (to.params.id == id_me) return next("profile");
            return next();
        },
        path: "/profile/:id",
        name: "ProfileViewYou",
        component: () =>
            import(
                /* webpackChunkName: "ProfileViewYou" */ "../views/Profile/ProfileYour"
            ),
    },
    {
        path: "/community",
        name: "Community",
        component: () =>
            import(
                /* webpackChunkName: "Community" */ "../views/community/Main"
            ),
    },
    {
        path: "/posts/:id/:name",
        name: "ShowPost",
        component: () =>
            import(
                /* webpackChunkName: "Community" */ "../views/post/ShowPost"
            ),
    },
    {
        path: "/storage",
        name: "Storage",
        component: () =>
            import(
                /* webpackChunkName: "Storage" */ "../views/storage/Storage"
            ),
        children: [
            {
                path: ":id",
                name: "ShowStorage",
                component: () =>
                    import(
                        /* webpackChunkName: "Storage" */ "../views/storage/ShowStorage"
                    ),
            },
        ],
    },
    {
        path: "/login",
        name: "Login",
        component: () =>
            import(
                /* webpackChunkName: "Community" */ "../views/login/Login.vue"
            ),
    },
    {
        path: "/error",
        name: "Error",
        component: () =>
            import(/* webpackChunkName: "Error" */ "../views/error/Error"),
    },
    {
        path: "*",
        name: "Default",
        component: () =>
            import(/* webpackChunkName: "Error" */ "../views/error/Error"),
    },
];

export { routers };
