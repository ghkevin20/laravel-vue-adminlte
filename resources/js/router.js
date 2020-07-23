import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store';
import axios from "axios";

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
                path: 'recover-password/:token',
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
];

const router = new VueRouter({
    routes,
    mode: 'history',
    linkActiveClass: 'active',
    linkExactActiveClass: 'active'
});


router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.validate)) {
        const find = to.matched.find(record => record.meta.validate)

        axios.post('/api/check')
            .then(response => {
                // alert('alert');
                if (response.data.authenticated) {
                    store.dispatch('authenticate');
                    store.dispatch('setUser', response.data.data);

                    if (find.meta.validate.includes('guest')) {
                        next({path: '/home'});
                    }else{
                        next();
                    }
                } else {
                    store.dispatch('disprove');
                    store.dispatch('unsetUser');

                    if (find.meta.validate.includes('auth')) {
                        next({path: '/login'});
                    }else{
                        next();
                    }
                }
            })
            .catch(error => {
                document.cookie = "XSRF-TOKEN=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

                store.dispatch('disprove');
                store.dispatch('unsetUser');

                if(to.path !== '/login'){
                    next({path: '/login'});
                }else{
                    next()
                }
            });
    } else {
        next(); // make sure to always call next()!
    }

})

export default router;

