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

const singleCategory = ref({})

const recipes = ref([])

const newRecipe = ref('')

let categoryId = parseInt(route.params.categoryId)

const submitNewCategory = () => {
    return new Promise((resolve) => {
        axios.post('/api/categories/store', {
            title: newCategory.value
        }).then(response => {
            console.log('nuts')
            resolve(response);
        })
    })
}


const addCategory = async () => {
    await submitNewCategory()
    const a1 = await getMaxIdCategory()
    categoryId =a1.data.id
    newCategory.value = ''
    router.push({name: 'category.show', params: { categoryId: categoryId }})
}

const deleteCategory = async (id) => {
    if (confirm('Are you sure?'))
    {
        const comp = await axios.delete('/api/categories/' + id)

        //  カテゴリが一件しかない状態でそれを削除するとデフォルトページへ遷移
        if (categories.value.length === 1) {
            router.push('/')
            return
        }

        // if deleting the same category that is currently displayed on the screen...

        if (categoryId === id)
        {
            let res = await getMaxIdCategory()
            categoryId = res.data.id
            router.push({name: 'category.show', params: { categoryId: categoryId }})
            return
        }

        const res1 = await getCategories()
        categories.value = res1.data
    }
}



const getCategory = () => {
    return axios.get('/api/categories/' + categoryId)
}



const getCategories = () => {
    return axios.get('/api/categories')
}


const getMaxIdCategory = () => {
    return axios.get('/api/max')
}


// ----------------------------
// function relate to recipe

const submitNewRecipe = () => {
    return new Promise((resolve) => {
        axios.post('/api/categories/' + categoryId + '/recipes/store', {
            title: newRecipe.value
        }).then((res) => {
            resolve(res)
        })
    })
}

const addRecipe = async() => {
    submitNewRecipe()
    const a2 = await getRecipes()
    recipes.value = a2.data
    newRecipe.value = ''
}


const getRecipes = () => {
    return axios.get('/api/categories/' + categoryId + '/recipes/')
}

const getCategoriesAndCategoryAndRecipes = async () => {
    categoryId = parseInt(route.params.categoryId)
    const res1 = await getCategories()
    const res2 = await getCategory()
    const res3 = await getRecipes()
    singleCategory.value = res2.data
    recipes.value = res3.data
    categories.value = res1.data


}
getCategoriesAndCategoryAndRecipes()

// -----------------------

// onMounted(async () => {
//     getCategoriesAndCategoryAndRecipes()
// })


watch(route, async () => {
    if (!isNaN(categoryId))
    {
        getCategoriesAndCategoryAndRecipes()
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
            <!-- <span>{{ category.title }}</span> -->
            <span>{{ singleCategory.title }}</span>
            <form method="post" v-on:submit.prevent="addRecipe">
                <input type="text" v-model="newRecipe">
            </form>
            <ul style="margin-top: 15px;">
                <li v-for="recipe in recipes">{{ recipe.title }}</li>
            </ul>
        </div>
    </div>

</template>
