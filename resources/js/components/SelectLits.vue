<template>
    <VueSelect @search="fetchOptions" :filterable="false" :options="options" v-model="selected" multiple/>
    <input type="text" :value="literatures" name="lits" hidden>
</template>

<script setup>
import VueSelect from "vue-select";
import 'vue-select/dist/vue-select.css';
import { ref, computed } from 'vue';

const selected = ref([])
const options = ref([])
const fetchOptions = (search, loading) => {
    loading()
    fetch(window?.searchUrl + `?q=${search}`)
    .then(res => res.json())
    .then(data => {
        const set = new Set([
            ...data,
            ...options.value
        ])

        options.value = Array.from(set.values())
    })
    .catch(e => {
        console.warn(e)
    })
    .finally(loading)
}

const literatures = computed(() => selected.value.join(';;;'))
</script>
