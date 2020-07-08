import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        component: require('./layouts/Public').default,
        meta: {validate: ['guest']},
        children: [
            {
                path: '/',
                component: require('./views/Home').default
            },
            {
                path: 'login',
                component: require('./views/auth/Login').default
            },
            {
                path: 'register',
                component: require('./views/auth/Register').default
            },
            {
                path: 'forgot-password',
                component: require('./views/auth/ForgotPassword').default
            },
            {
                path: 'recover-password',
                component: require('./views/auth/RecoverPassword').default
            },
        ]
    }, // can be access without authentication
    {
        path: '/',
        component: require('./layouts/Master').default,
        meta: {validate: ['auth']},
        children: [
            {
                path: '/',
                component: require('./views/AdminHome').default
            },
            {
                path: 'home',
                component: require('./views/AdminHome').default
            },
        ]
    }, // authentication required
]

const router = new VueRouter({
    routes,
    mode: 'history',
    linkActiveClass: 'active',
    linkExactActiveClass: 'active'
})

export default router

