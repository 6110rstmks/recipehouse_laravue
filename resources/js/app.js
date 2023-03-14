import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler'
import * as VueRouter from 'vue-router'

import CategoryHome from './components/CategoryHome.vue'
import CategoryShow from './components/CategoryShow.vue'
import RecipeShow from './RecipeShow.vue'

const routes = [
    {
        path: '/',
        name: 'category.home',
        components: {
            Home: CategoryHome
        }
    },
    {
        path: '/categories/:categoryId',
        name: 'category.show',
        components: {
            Show: CategoryShow
        },
        props: true
    },
    {
        path: '/recipes/show/:recipeId',
        component: RecipeShow
    }
]

const router = VueRouter.createRouter({
    history: VueRouter.createWebHistory(),
    routes,
})

const app = createApp({})
app.use(router)
app.mount('#app')
