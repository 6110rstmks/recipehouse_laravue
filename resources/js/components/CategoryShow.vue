<script setup lang="ts">
import axios from "axios";
import { defineProps, ref, reactive, watch} from "vue"
import { useRoute, useRouter } from 'vue-router'

import { submitNewCategory } from "../utils";

const route = useRoute()

const router = useRouter()

const props = defineProps({
  categoryId: String,
//   categoryId: Number,
})

const categories = ref([])

const newCategoryTitle = ref<string>('')

const errMsg = ref<string>('')

interface Category {
    id?: number
    title?: string
    created_at?: string
    updated_at?: string
}



// const singleCategory: Category = reactive({
const singleCategory: Category = ref({
  id: 0,
  title: '',
  created_at: '',
  updated_at: '',
});
// const singleCategory = ref({})

const recipes = ref([])

const newRecipe = ref<string>('')

//----------------------
// file upload

const fileInfo = ref("")
const user = ref(null)
const showUserImage = ref(false)
const filePath = ref("")
//----------------------


const inputValidation = async (text:string):Promise<string> => {
    return new Promise((resolve, reject) => {
        if (text.length <= 1)
        {
            reject("１文字以上入力してください")
        } else {
            resolve("")
        }
    })
}


const addCategory = async () => {
    try {
        errMsg.value = await inputValidation(newCategoryTitle.value)
    } catch {
        errMsg.value = "１文字以上入力してください"
        return
    }
    await submitNewCategory(newCategoryTitle.value)

    newCategoryTitle.value = ''

    // 右側を最新のカテゴリに更新する
    const tmpVar:Category = await getMaxIdCategory()
    let categoryId = tmpVar.data.id
    router.push({name: 'category.show', params: { categoryId: categoryId }})
}

const deleteCategory = async (id: number) => {
    if (confirm('Are you sure?'))
    {
        // 同期処理をするために、compSignの変数で受ける。
        const compSign = await axios.delete('/api/categories/' + id)

        //  move default page('/') when deleting the category that only one
        if (categories.value.length === 1) {
            router.push('/')
            return
        }

        let nowRightCategoryId:number = parseInt(route.params.categoryId)


        // if deleting the same category that is currently displayed on the right screen
        if (nowRightCategoryId === id)
        {
            let res:Category = await getMaxIdCategory()
            const nextRightCategoryId = res.data.id
            router.push({name: 'category.show', params: { categoryId: nextRightCategoryId }})
            return
        }

        // 削除したので改めてカテゴリ一覧を再取得
        // 削除のときはurlを変更する仕様にしていない
        const responseCategories = await getCategories()
        categories.value = responseCategories.data
    }
}

const getCategory = (categoryId:number):Promise<Category> => {
    return axios.get('/api/categories/' + categoryId)
}


const getCategories = ():Promise<unknown> => {
    return axios.get('/api/categories')
}


const getMaxIdCategory = ():Promise<Category> => {
    return axios.get('/api/max')
}


// ----------------------------
// function relate to recipe

const submitNewRecipe = (categoryId:number):Promise<unknown> => {
    return new Promise((resolve) => {
        axios.post('/api/categories/' + categoryId + '/recipes/store', {
            title: newRecipe.value
        }).then((res) => {
            resolve(res)
        })
    })
}

const addRecipe = async():Promise<void> => {

    let categoryId = parseInt(route.params.categoryId)
    await submitNewRecipe(categoryId)
    const a2 = await getRecipesLinkedWithCategory(categoryId)
    recipes.value = a2.data
    newRecipe.value = ''
}

const fileSelected = (event: Event) => {
    console.log(event.target);
    fileInfo.value = event.target.files[0]
    // fileInfo.value = (event.target as HTMLInputElement).files[0]


    const formData = new FormData()

    formData.append('file', fileInfo.value)

    axios.post('/api/img_upload', formData).then((response) => {

        if (response.data === 'ok')
        {
            showUserImage.value = true;
            user.value = fileInfo.value.name
            console.log(user.value)
        } else {

        }
    })
};

const deleteRecipe = async (recipeId):void => {
    const comp = await axios.delete('/api/categories/recipes/delete/' + recipeId)
    let categoryId = parseInt(route.params.categoryId)
    const responseRecipes = await getRecipesLinkedWithCategory(categoryId)
    recipes.value = responseRecipes.data
}


const getRecipesLinkedWithCategory = (categoryId:number) => {
    return axios.get('/api/categories/' + categoryId + '/recipes/')
}

const getCategoriesAndCategoryAndRecipes = async ():Promise<void> => {

    const str:string|string[] = route.params.categoryId

    const regex = RegExp('[a-zA-Z]+')

    // urlの数値の部分にstringがはいったとき、最大のidにとばす
    if (regex.test(str))
    {
        const tmpVar:Category = await getMaxIdCategory()
        const categoryId = tmpVar.data.id
        router.push({name: 'category.show', params: { categoryId: categoryId }})
        return
    }
    let categoryId = parseInt(route.params.categoryId)
    const tmpCategories = await getCategories()
    const tmpCategory:Category = await getCategory(categoryId)
    const tmpRecipes = await getRecipesLinkedWithCategory(categoryId)
    singleCategory.value = tmpCategory.data
    console.log(singleCategory.value)
    recipes.value = tmpRecipes.data
    categories.value = tmpCategories.data
}

// when created (in lifecycel hook)
getCategoriesAndCategoryAndRecipes()

// -----------------------

// onMounted(async () => {
// })

watch(route, async () => {
    //
    // if (!isNaN(categoryId))
    // {
    // }
    getCategoriesAndCategoryAndRecipes()
})
</script>

<template>
    <div class="left-container">
        <div class="form-box">
            <h4 style="margin-bottom: 20px; margin-top: 10px">RECIPE HOUSE</h4>
            <form method="post" v-on:submit.prevent="addCategory">
                <input type="text" v-model="newCategoryTitle">
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
                    <img width="200" :src="'/storage/' + user" />
                </div>

                <button type="submit" disabled style="display: none" aria-hidden="true"></button>
                <div>
                    <button>SUBMIT</button>
                </div>
            </form>
            <ul style="margin-top: 15px;">
                <li v-for="recipe in recipes">
                    <a :href="'/recipes/show/' + recipe.id">{{ recipe.title }}</a>
                    <button @click="deleteRecipe(recipe.id)">DELETE</button>
                </li>
            </ul>
        </div>
    </div>

</template>
