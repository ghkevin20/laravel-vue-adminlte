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
                component: require('./views/Home').default,
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
                path: 'home',
                name: 'Home',
                component: require('./views/AdminHome').default,
            },
            {
                path: 'profile',
                name: 'Profile',
                component: require('./views/users/profile').default,
            },
            {
                path: 'users',
                name: 'Users',
                component: require('./views/users').default,
            },
            {
                path: 'roles',
                name: 'Roles',
                component: require('./views/users/roles').default,
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

