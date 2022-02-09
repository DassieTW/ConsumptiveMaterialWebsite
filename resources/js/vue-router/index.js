import { createRouter, createWebHistory } from "vue-router";
import ExampleComponent from '../components/test/ExampleComponent.vue'

const routes = [
    {
        path: '/dashboard',
        name: 'test.example',
        component: ExampleComponent
    }
] ;

export default createRouter({
    history: createWebHistory(),
    routes
})