import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        isAuth: false
    },
    mutations: {
        auth(state,value){
            state.isAuth = value
        }
    },
    actions:{
        authenticate ({ commit }) {
            commit('auth',true);
        },
        disprove ({ commit }) {
            commit('auth',false);
        }
    },
    getters:{
        auth: (state) => {
            return state.isAuth;
        }
    }
})

export default store;
