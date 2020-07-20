import Vue from 'vue'
import VueRouter from 'vue-router'
import store from './store'

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        component: require('./layouts/Public').default,
        meta: {},
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
        meta: { requiresAuth: true },
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
                path: 'categories',
                name: 'Categories',
                component: require('./views/categories').default,
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
});


router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!store.getters.auth) {
            next({path: '/login'})
            alert('hello1');
        } else {
            next()
            alert('hello2');
        }
    } else {
        if (store.getters.auth) {
            next({path: '/home'})
        }
        alert(store.getters.auth)
        next() // make sure to always call next()!
    }
})

export default router

