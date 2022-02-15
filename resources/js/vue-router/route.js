import { createRouter, createWebHistory } from "vue-router";
import ExampleComponent from '../components/test/ExampleComponent.vue'
import BreadCrumbNav from '../components/breadcrumb/BreadCrumbNav.vue'

const routes = [
    {
        path: '/vuetest',
        name: 'test.example',
        component: ExampleComponent
    },
    {
        path: '/catchAll(.*)',
        name: 'breadcrumb.nav',
        component: BreadCrumbNav
    }
] ;

export default createRouter({
    history: createWebHistory(),
    routes
})