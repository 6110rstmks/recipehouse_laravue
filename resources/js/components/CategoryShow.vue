<script setup>
import axios from "axios";
import { defineProps, ref, watch} from "vue"
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()

const router = useRouter()

const props = defineProps({
  categoryId: String,
})

const categories = ref([])

const newCategory = ref('')

const errMsg = ref('')

const singleCategory = ref({})

const recipes = ref([])

const newRecipe = ref('')

let categoryId = ''

//----------------------
// file upload

const fileInfo = ref("")
const user = ref(null)
const showUserImage = ref(false)
const filePath = ref("")
//----------------------

// let categoryId = parseInt(route.params.categoryId)

const submitNewCategory = () => {
    return new Promise((resolve) => {
        axios.post('/api/categories/store', {
            title: newCategory.value
        }).then(response => {
            resolve(response);
        })
    })
}

const addCategory = async () => {
    if (newCategory.value.length === 0)
    {
        errMsg.value = "文字を入力してください"
        return
    } else {
        errMsg.value = ""
    }
    await submitNewCategory()

    newCategory.value = ''

    // 右側を最新のカテゴリに更新する
    const a1 = await getMaxIdCategory()
    categoryId = a1.data.id
    router.push({name: 'category.show', params: { categoryId: categoryId }})
}

const deleteCategory = async (id) => {
    if (confirm('Are you sure?'))
    {
        // 同期処理をするために、compSignの変数で受ける。
        const compSign = await axios.delete('/api/categories/' + id)

        //  move default page('/') when deleting the category that only one
        if (categories.value.length === 1) {
            router.push('/')
            return
        }

        // if deleting the same category that is currently displayed on the right screen
        if (categoryId === id)
        {
            let res = await getMaxIdCategory()
            categoryId = res.data.id
            router.push({name: 'category.show', params: { categoryId: categoryId }})
            return
        }

        // 削除したので改めてカテゴリ一覧を再取得
        // 削除のときはurlを変更する仕様にしていない
        const responseCategories = await getCategories()
        categories.value = responseCategories.data
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
    await submitNewRecipe()
    const a2 = await getRecipes()
    recipes.value = a2.data
    newRecipe.value = ''
}

const fileSelected = (event) => {

    fileInfo.value = event.target.files[0]

    const formData = new FormData()

    formData.append('file', fileInfo.value)

    axios.post('/api/img_upload', formData).then((response) => {
        // console.log(response.data)

        if (response.data === 'ok')
        {
            showUserImage.value = true;
            user.value = fileInfo.value.name
            console.log(user.value)
        } else {

        }
    })
};

const deleteRecipe = async (recipeId) => {
    console.log(recipeId)
    const comp = await axios.delete('/api/categories/recipes/delete/' + recipeId)

    const responseRecipes = await getRecipes()
    console.log(responseRecipes.data)

    recipes.value = responseRecipes.data
}


const getRecipes = () => {
    return axios.get('/api/categories/' + categoryId + '/recipes/')
}

const getCategoriesAndCategoryAndRecipes = async () => {
    categoryId = parseInt(route.params.categoryId)
    const tmpCategories = await getCategories()
    const tmpCategory = await getCategory()
    const tmpRecipes = await getRecipes()
    singleCategory.value = tmpCategory.data
    recipes.value = tmpRecipes.data
    categories.value = tmpCategories.data
}

// when created (in lifecycel hook)
getCategoriesAndCategoryAndRecipes()

// -----------------------

// onMounted(async () => {
// })

//
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
                <button type="submit" disabled style="display: none" aria-hidden="true"></button>
                <div>
                    <button>SUBMIT</button>
                </div>
            </form>
        </div>
        <ul class="category_ul" style="margin-top: 30px">
            <li v-for="category in categories" :key="category.id">
                <button class="btn btn-danger" v-on:click="deleteCategory(category.id)">Delete</button>
                <router-link :to="{name: 'category.show', params: {categoryId: category.id }}">
                    <span>{{ category.title }}</span>
                </router-link>
            </li>
        </ul>
    </div>
    {{ errMsg }}


    <div class="right-container">
        <span class="icon">
            <i class="fas fa-utensils fa-lg"></i>
        </span>
        <div>
            <span>{{ singleCategory.title }}</span>
            <form method="post" v-on:submit.prevent="addRecipe">
                <input type="text" v-model="newRecipe">
                <p><input type="file" @change="fileSelected" /></p>

                <div v-if="showUserImage">
                    <img :src="'/storage/' + user" />
                </div>

                <button type="submit" disabled style="display: none" aria-hidden="true"></button>
                <div>
                    <button>SUBMIT</button>
                </div>
            </form>
            <ul style="margin-top: 15px;">
                <li v-for="recipe in recipes">
                    <span>{{ recipe.title }}</span>
                    <button @click="deleteRecipe(recipe.id)">削除</button>
                </li>
            </ul>
        </div>
    </div>

</template>
