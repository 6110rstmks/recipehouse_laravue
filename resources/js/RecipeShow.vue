<script setup>
import { onMounted, ref, computed } from "vue"

    import { useRoute } from 'vue-router';

    const route = useRoute();

    const recipeId = computed(() => {
        return route.params.id;
    });

    const getRecipe = (recipeId) => {
        return axios.get('/api/recipes/' + recipeId)
    }

    const initial = async () => {
        const recipe = await getRecipe(recipeId)

    }




</script>


<template>
    <div>oiu</div>
</template>
