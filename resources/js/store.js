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
        roles: [],
        permissions: [],
    },
    mutations: {
        setAuth(state, value) {
            state.isAuth = value
        },
        setUser(state, value) {
            state.user = value
        },
        setRoles(state, value) {
            state.roles = value
        },
        setPermissions(state, value) {
            state.permissions = value
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
        },
        setRoles({commit}, data) {
            commit('setRoles', data)
        },
        setPermissions({commit}, data) {
            commit('setPermissions', data)
        },
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
        roles: (state) => {
            return state.roles;
        },
        isSuperAdmin: (state) => {
            return state.roles.includes('Super Admin');
        },
        hasRole: (state, getters) => (role) => {
            return getters.isSuperAdmin ? true : state.roles.includes(role);
        },
        hasPermission: (state, getters) => (permission) => {
            return getters.isSuperAdmin ? true : state.permissions.includes(permission);
        },
        hasAnyRole: (state, getters) => (roles) => {
            return getters.isSuperAdmin ? true : roles.some(role => state.roles.includes(role));
        },
        hasAnyPermission: (state, getters) => (permissions) => {
            return getters.isSuperAdmin ? true : permissions.some(permission => state.permissions.includes(permission));
        }
    },
    plugins: [
        createPersistedState({storage: window.sessionStorage})
    ],
})

export default store;
