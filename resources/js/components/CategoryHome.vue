<script setup>
import axios from "axios";
import { ref, onMounted } from "vue"
import { useRouter } from 'vue-router'


const router = useRouter()

const newCategory = ref('')

const categories = ref([])

const category = ref({})

let categoryId = ''

const submitNewCategory = () => {
  return new Promise((resolve) => {
    axios.post('/api/categories/store', {
        title: newCategory.value
    })
    resolve();
  })
}

const getMaxIdCategory = () => {
    return new Promise((resolve) => {
        axios.get('/api/max')
            .then((res) => {
                category.value = res.data
                categoryId = category.value.id
                resolve();
            })
    })
}


const addCategory = async () => {
    await submitNewCategory()
    await getCategories()
    await getMaxIdCategory()
    newCategory.value = ''
    router.push({name: 'category.show', params: { categoryId: categoryId }})

}

const getCategories = async () => {

    return new Promise((resolve) => {
        axios.get('/api/categories')
            .then((res) => {
                categories.value = res.data
                if (categories.value.length === 0)
                {
                    noData.value = !noData.value
                }
            })
        resolve()

    })
}


onMounted(() => {
    axios.get('/api/max')
        .then((res) => {
            // category.value = res.data
            // categoryId = category.value.id
            categoryId = res.data.id
            router.push({name: 'category.show', params: { categoryId: categoryId }})
        })
        .catch((err) => {
            // console.log('ssssssuuuuu')
        })
})

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

