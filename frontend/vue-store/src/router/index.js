import Landing from "../views/Landing.vue";
import Login from "../views/Login.vue";
import FormStore from "../views/FormStore.vue";
import {createRouter, createWebHistory} from "vue-router";

const routes = [
    {
        path: '/',
        name: 'home',
        component: Landing
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/form',
        name: 'form',
        component: FormStore
    },
    {
        path: "/:pathMatch(.*)*",
        redirect: "/"
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;