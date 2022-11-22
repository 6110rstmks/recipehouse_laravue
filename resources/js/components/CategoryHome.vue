<script setup>
import axios from "axios";
import { ref } from "vue"
import { useRouter } from 'vue-router'

const router = useRouter()

const newCategory = ref('')

const category = ref({})

// const dataFlag = ref(false)

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

const submitNewCategory = () => {
  return new Promise((resolve) => {
    axios.post('/api/categories/store', {
        title: newCategory.value
    })
    resolve()
  })
}

const addCategory = async () => {
    await submitNewCategory()
    const a1 = await getMaxIdCategory()
    categoryId =a1.data.id
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
    <!-- <div class="right-container" v-if="dataFlag"> -->
    <div class="right-container">
        <div>No category</div>
    </div>

</template>

