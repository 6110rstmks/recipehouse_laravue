// localで確認するときは、php artisan serve とnpm run devを実行してください
// 久しぶりに確認するときは忘れがち。
import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler'
import * as VueRouter from 'vue-router'

import CategoryHome from './components/CategoryHome.vue'
import CategoryShow from './components/CategoryShow.vue'

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
]

const router = VueRouter.createRouter({
    history: VueRouter.createWebHistory(),
    routes,
})

const app = createApp({})
app.use(router)
app.mount('#app')
