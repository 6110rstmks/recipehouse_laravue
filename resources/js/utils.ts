// // import axios from "axios"
// import { ref } from "vue"

// import { useRoute, useRouter } from 'vue-router'
// // import router from './router.js'
// // const route = useRoute()


// const newCategory = ref('')

// const categories = ref([])


// const singleCategory = ref({})

// const recipes = ref([])

// const newRecipe = ref('')
// let categoryId = ''


export function submitNewCategory(newCategory:string):Promise<unknown> {
    return new Promise((resolve) => {
        axios.post('/api/categories/store', {
            // title: newCategory.value
            title: newCategory
        }).then(response => {
            resolve(response);
        })
    })
}


// export const addCategory = async () => {
//     const router = import('./router.js')
//     await submitNewCategory()
//     const a1 = await getMaxIdCategory()
//     categoryId = a1.data.id
//     newCategory.value = ''
//     router.push({name: 'category.show', params: { categoryId: categoryId }})
// }

// export const deleteCategory = async (id) => {
//     if (confirm('Are you sure?'))
//     {
//         const router = import('./router.js')
//         const comp = await axios.delete('/api/categories/' + id)

//         //  move default page when category deleting the category that only one
//         if (categories.value.length === 1) {
//             router.push('/')
//             return
//         }

//         // if deleting the same category that is currently displayed on the screen...

//         if (categoryId === id)
//         {
//             let res = await getMaxIdCategory()
//             categoryId = res.data.id
//             router.push({name: 'category.show', params: { categoryId: categoryId }})
//             return
//         }

//         const res = await getCategories()
//         categories.value = res.data
//     }
// }



// export const getCategory = () => {
//     return axios.get('/api/categories/' + categoryId)
// }



// export const getCategories = () => {
//     return axios.get('/api/categories')
// }


// export const getMaxIdCategory = () => {
//     return axios.get('/api/max')
// }

// // ----------------------------
// // function relate to recipe

// export const submitNewRecipe = () => {
//     return new Promise((resolve) => {
//         axios.post('/api/categories/' + categoryId + '/recipes/store', {
//             title: newRecipe.value
//         }).then((res) => {
//             resolve(res)
//         })
//     })
// }

// export const addRecipe = async() => {
//     await submitNewRecipe()
//     const a2 = await getRecipes()
//     recipes.value = a2.data
//     newRecipe.value = ''
// }


// export const getRecipes = () => {
//     return axios.get('/api/categories/' + categoryId + '/recipes/')
// }

// export const getCategoriesAndCategoryAndRecipes = async () => {
//     const route = useRoute()
//     categoryId = parseInt(route.params.categoryId)
//     const res1 = await getCategories()
//     const res2 = await getCategory()
//     const res3 = await getRecipes()
//     singleCategory.value = res2.data
//     recipes.value = res3.data
//     categories.value = res1.data
// }

