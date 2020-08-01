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
                component: require('./views/public').default,
            },
            {
                path: '/login',
                component: require('./views/auth/Login').default
            },
            {
                path: '/register',
                component: require('./views/auth/Register').default
            },
            {
                path: '/forgot-password',
                component: require('./views/auth/ForgotPassword').default
            },
            {
                path: '/recover-password/:token',
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
                path: '/home',
                name: 'Home',
                component: require('./views/master').default,
            },
            {
                path: '/profile',
                name: 'Profile',
                component: require('./views/profile').default,
            },
            {
                path: '/users',
                name: 'Users',
                component: require('./views/users').default,
                meta: {permission: ['Browse User']}
            },
            {
                path: '/roles',
                name: 'Roles',
                component: require('./views/roles').default,
                meta: {permission: ['Browse Role']}
            },
            {
                path: '/permissions',
                name: 'Permissions',
                component: require('./views/permissions').default,
                meta: {permission: ['Browse Permission']}
            },
            {
                path: '/activity-logs',
                name: 'Activity Logs',
                component: require('./views/activity-logs').default,
                meta: {permission: ['Browse Activity Log']}
            },
        ]
    }, // authentication required
    {
        path: '/',
        component : require('./layouts/Error').default,
        children: [
            {
                path: '/403',
                component: require('./views/errors/403').default
            },
            {
                path: '/404',
                component: require('./views/errors/404').default
            },
            {
                path: '*',
                component: require('./views/errors/404').default
            }
        ]
    } // Not Found
];

const router = new VueRouter({
    routes,
    mode: 'history',
    linkActiveClass: 'active',
    linkExactActiveClass: 'active'
});

let pageLoader;
router.beforeEach((to, from, next) => {
    pageLoader = Vue.$loading.show({color: '#007bff'});

    if (to.matched.some(record => record.meta.validate)) {
        const find = to.matched.find(record => record.meta.validate)

        axios.post('/api/check')
            .then(response => {
                // alert('alert');
                if (response.data.authenticated) {
                    store.dispatch('authenticate');
                    store.dispatch('setUser', response.data.data.user);
                    store.dispatch('setRoles', response.data.data.roles);
                    store.dispatch('setPermissions', response.data.data.permissions);

                    if (find.meta.validate.includes('guest')) {
                        next(new Error('cancel'));
                        next({path: '/home'});
                    } else {
                        if(to.meta.hasOwnProperty('permission')){
                            if(!store.getters.hasAnyPermission(to.meta.permission)){
                                // 403
                                next(new Error('cancel'));
                                next({path: '/403'});
                            }
                        }
                        next();
                    }
                } else {
                    store.dispatch('disprove');
                    store.dispatch('unsetUser');

                    if (find.meta.validate.includes('auth')) {
                        next(new Error('cancel'));
                        next({path: '/login'});
                    } else {
                        next();
                    }
                }
            })
            .catch(error => {
                document.cookie = "XSRF-TOKEN=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

                store.dispatch('disprove');
                store.dispatch('unsetUser');

                if (to.path !== '/login') {
                    next(new Error('cancel'));
                    next({path: '/login'});
                } else {
                    next()
                }
            });
    } else {
        next(); // make sure to always call next()!
    }

});

router.onError((e) => {
    if(e.toString() === 'Error: cancel'){
        pageLoader.hide();
    }
})

router.afterEach((to, from) => {
    pageLoader.hide();
});


export default router;

