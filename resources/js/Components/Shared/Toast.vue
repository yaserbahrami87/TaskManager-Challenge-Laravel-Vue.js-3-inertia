<template>
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-xl shadow">
        {{ message }}
    </div>
</template>
<script setup>
const props = defineProps({ message: String });
</script>
