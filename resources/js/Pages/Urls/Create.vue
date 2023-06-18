<script setup>
defineProps({
    urls: Object,
});
</script>

<template>
    <div v-for="url in urls" :key="url.id">{{ url.url }}</div>
</template>
