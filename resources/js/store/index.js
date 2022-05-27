import Vue from "vue";
import vuex from "vuex";
Vue.use(vuex);

import state_r from "./state.js";
import getters_r from "./getters.js";
import mutations_r from "./mutations.js";
import actions_r from "./actions.js";

const  store = new vuex.Store({
    state: state_r,
    getters: getters_r,
    mutations: mutations_r,
    actions: actions_r,
})

export  { store }
