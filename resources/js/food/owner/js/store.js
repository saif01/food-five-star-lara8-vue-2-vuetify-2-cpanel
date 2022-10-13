import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)


export default new Vuex.Store({

    state : {
        auth  : null,
    },

    getters : {

        getAuth(state){
            return state.auth;
        },
 
    },

    mutations : {

        // Auth User
        setAuth(state, data){
            state.auth = data;
        },
        
    },

    actions :  {
        
    },

});