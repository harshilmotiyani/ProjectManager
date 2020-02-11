require('./bootstrap');
import Vue from 'vue';
import VueRouter from "vue-router";
import Vuex from 'vuex';
Vue.use(VueRouter);
Vue.use(Vuex);

import VueAxios from 'vue-axios';
import axios from 'axios';
Vue.use(VueAxios, axios);
Vue.component('welcome', require('../views/Welcome.vue'));
Vue.component('welcome', require('../views/logout.vue'));

Vue.component('login', require('../views/Login.vue'));
Vue.component('register', require('../views/Register.vue'));
Vue.component('board', require('../views/Board.vue'));



import Welcome from '../views/Welcome';
import App from '../views/App';
import Login from '../views/Login';
import Register from '../views/Register';
import Dashboard from '../views/Board';
import Logout from '../views/logout';
const router= new VueRouter({
    mode:'history',
    routes:[
        {
            path:'/',
            name:'home',
            component:Welcome
        },
        {
            path:'/login',
            name:'login',
            component:Login
        },
        {
           path:'/register',
           name:'register',
           component:Register
        },
        {
            path:'/board',
            name:'board',
            component:Dashboard
        },
        {
            path:'/logout',
            name:'logout',
            component:Logout
        }
    ],
});
export const store = new Vuex.Store({
    state:{
        isLoggedIn:false,
        name:'',

    },

    mutations:{
        change(state)
        {
            state.isLoggedIn =!state.isLoggedIn ;
        },
        setName(state,name)
        {
            state.name=name;
        }

    },
    getters:{
        isLoggedIn: state =>state.isLoggedIn,
        name: state=>state.name,

    }
}

);
const app= new Vue({
    el: '#app',
    components:{App},
    store,
    router,

});
