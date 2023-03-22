<script setup lang="ts">
import axios from "axios"
import { onMounted, ref } from "vue"

import { useRouter } from 'vue-router'
import { submitNewCategory } from "../utils";

// import router from '../router.js'

const router = useRouter()

const newCategory = ref('')
let categoryId = ''

async function initial() {
    try {
        const res = await axios.get('/api/max')
        categoryId = res.data.id
        router.push({name: 'category.show', params: { categoryId: categoryId }})
    } catch(err) {
        console.log('error handling')
    }
}

initial()

const addCategory = async () => {
    await submitNewCategory(newCategory.value)
    const res = await getMaxIdCategory()
    categoryId = res.data.id
    router.push({name: 'category.show', params: { categoryId: categoryId }})
}

const getMaxIdCategory = () => {
    return axios.get('/api/max')
}

</script>

<template>
    <div class="left-container">
        <h4 style="margin-bottom: 20px; margin-top: 10px">RECIPE HOUSE</h4>
        <form method="post" v-on:submit.prevent="addCategory">
            <input type="text" v-model="newCategory">
        </form>
    </div>
    <div class="right-container">
        <div>No category</div>
    </div>

</template>

