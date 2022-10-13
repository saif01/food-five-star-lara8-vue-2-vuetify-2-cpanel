import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)


export default new Vuex.Store({

    state: {
        auth: null,
        roles: null,

        cartItem: [],
        lang: null,
        cDetails: [],
        order: [],
        announce: [],
        cartTrigger: null,
    },

    getters: {

        getAuth(state) {
            return state.auth;
        },
        getRoles(state) {
            return state.roles;
        },
        getCart(state) {
            return state.cartItem;
        },
        getLang(state) {
            return state.lang;
        },
        getCdetails(state) {
            return state.cDetails;
        },
        getOrder(state) {
            return state.order;
        },
        getAnnounce(state) {
            return state.announce;
        },
        getCartTrigger(state) {
            return state.cartTrigger;
        }


    },

    mutations: {

        // Auth User
        setAuth(state, data) {
            state.auth = data;
        },

        // Roles User
        setRoles(state, data) {
            state.roles = data;
        },


        setCart(state, data) {
            state.cartItem = data;
        },

        setLang(state, data) {
            state.lang = data;
        },

        setCdetails(state, data) {
            state.cDetails = data;
        },

        setOrder(state, data) {
            state.order = data;
        },
        setAnnounce(state, data) {
            state.announce = data;
        },
        setCartTrigger(state, data) {
            state.cartTrigger = data
        }

    },

    actions: {

    },

});