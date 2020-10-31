import Vue from 'vue';
import VueRouter from 'vue-router';
import register from './components/auth/Register';
import login from './components/auth/Login';
import home from './components/Home';

const routes = [
    {path:'/', name:'Home', component:home},
    {path:'/register', name:'Register', component:register, meta:{guestOnly:true}},
    {path:'/login', name:'Login', component:login, meta:{guestOnly:true}}
];

Vue.use(VueRouter);

const router = new VueRouter({routes});

function isLoggedIn() {
    return localStorage.getItem("token");
}

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.authOnly)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!isLoggedIn()) {
            next({
                path: "/",
                query: { redirect: to.fullPath }
            });
        } else {
            next();
        }
    } else if (to.matched.some(record => record.meta.guestOnly)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (isLoggedIn()) {
            next({
                path: "/",
                query: { redirect: to.fullPath }
            });
        } else {
            next();
        }
    } else {
        next(); // make sure to always call next()!
    }
});

export default router;