import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from "vuex-persistedstate";

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        appNameFirst: 'Laravel',
        appNameLast: 'Vue Admin',
        isAuth: false,
        user: {},
    },
    mutations: {
        setAuth(state, value) {
            state.isAuth = value
        },
        setUser(state, value) {
            state.user = value
        }
    },
    actions: {
        authenticate({commit}) {
            commit('setAuth', true)
        },
        disprove({commit}) {
            commit('setAuth', false)
        },
        setUser({commit}, data) {
            commit('setUser', data)
        },
        unsetUser({commit}) {
            commit('setUser', {})
        }
    },
    getters: {
        appNameFirst: (state) => {
            return state.appNameFirst;
        },
        appNameLast: (state) => {
            return state.appNameLast;
        },
        auth: (state) => {
            return state.isAuth;
        },
        user: (state) => {
            return state.user;
        },
    },
    plugins: [
        createPersistedState({storage: window.sessionStorage})
    ],
})

export default store;
