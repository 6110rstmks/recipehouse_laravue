<script setup>
import axios from "axios";
import { defineProps, onMounted, ref, watch} from "vue"
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()

const router = useRouter()

const props = defineProps({
  categoryId: String,
})

const categories = ref([])

const newCategory = ref('')

const category = ref({})

const recipes = ref([])

const newRecipe = ref('')

let categoryId = parseInt(route.params.categoryId)
// let categoryId = route.params.categoryId


const submitNewCategory = () => {
  return new Promise((resolve) => {
    axios.post('/api/categories/store', {
        title: newCategory.value
    })
    console.log(1)
    resolve();

  })
}

const addCategory = async () => {
    await submitNewCategory()
    console.log(2)
    await getMaxIdCategory()
    console.log(4)
    newCategory.value = ''
    router.push({name: 'category.show', params: { categoryId: categoryId }})
}

const deleteCategory = async (id) => {
    if (confirm('Are you sure?'))
    {
        console.log(`categoryId:${categoryId}`)
        axios.delete('/api/categories/' + id)

        //  カテゴリが一件しかない状態でそれを削除するとデフォルトページへ遷移
        if (categories.value.length === 1) {
            router.push('/')
            return
        }

        // 現在のカテゴリの画面と同じカテゴリを削除したとき。

        if (categoryId === id)
        {
            console.log('akasatan')
            let cde = await getMaxIdCategory()
            router.push({name: 'category.show', params: { categoryId: categoryId }})
        }

        getCategories()
    }
}

const getCategory = async () => {
    const res = await axios.get('/api/categories/' + categoryId)
    category.value = res.data
}


const getMaxIdCategory = () => {
    return new Promise((resolve) => {
        axios.get('/api/max')
            .then((res) => {
                category.value = res.data
                categoryId = category.value.id
                console.log(3)
                resolve();
            })
    })
}


const getCategories = async () => {
    return new Promise((resolve) => {
        axios.get('/api/categories')
            .then((res) => {
                categories.value = res.data
            })
        resolve()
    })

}

// ----------------------------
// function relate to recipe

const submitNewRecipe = () => {
    axios.post('/api/categories/' + categoryId + '/recipes/store', {
        title: newRecipe.value
    })
}

const addRecipe = () => {
    submitNewRecipe()
    getRecipes()
    newRecipe.value = ''
}

const getRecipes = async () => {
    const res = await axios.get('/api/categories/' + categoryId + '/recipes/')
    recipes.value = res.data
}

// -----------------------

onMounted(() => {
    categoryId = parseInt(route.params.categoryId)
    getCategories()
    getCategory()
        .then((data) => {
            getRecipes()
        })
})


watch(route, () => {
    categoryId = parseInt(route.params.categoryId)
    if (!isNaN(categoryId))
    {
        getCategories()
        getCategory()
        getRecipes()
    }


})
</script>

<template>
    <div class="left-container">
        <div class="form-box">
            <h4 style="margin-bottom: 20px; margin-top: 10px">RECIPE HOUSE</h4>
            <form method="post" v-on:submit.prevent="addCategory">
                <input type="text" v-model="newCategory">
            </form>
        </div>
        <ul class="category_ul">
            <li v-for="category in categories" :key="category.id">
                <button class="btn btn-danger" v-on:click="deleteCategory(category.id)">Delete</button>
                <router-link :to="{name: 'category.show', params: {categoryId: category.id }}">
                    <span>{{ category.title }}</span>
                </router-link>
            </li>
        </ul>
    </div>

    <div class="right-container">
        <span class="icon">
            <i class="fas fa-utensils fa-lg"></i>
        </span>
        <div>
            <span>{{ category.title }}</span>
            <!-- <span>{{ currentCategory.title }}</span> -->
            <form method="post" v-on:submit.prevent="addRecipe">
                <input type="text" v-model="newRecipe">
            </form>
            <ul style="margin-top: 15px;">
                <li v-for="recipe in recipes">{{ recipe.title }}</li>
            </ul>
        </div>
    </div>

</template>
