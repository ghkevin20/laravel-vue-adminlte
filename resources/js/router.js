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
        ]

    } // can be access without authentication
]

const router = new VueRouter({
    routes,
    mode: 'history'
})

export default router

