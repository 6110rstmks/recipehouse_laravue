<script setup>
import { onMounted, ref, computed } from "vue"

    import { useRoute } from 'vue-router';

    const route = useRoute();

    const recipeId = computed(() => {
        return route.params.id;
    });

    const getRecipes = () => {
        return axios.get('/api/recipes/' + recipeId)
    }

    const tmpRecipes = ref()

    tmpRecipes.value = getRecipes()


</script>


<template>
    <div>oiu</div>
</template>
