<template>
    <div class="flex gap-2 mb-4">
        <button
            v-for="option in filterOptions"
            :key="option.value"
            @click="$emit('update:modelValue', option.value)"
            :class="[
                'px-4 py-2 rounded-lg text-sm font-semibold',
                modelValue === option.value ? 'bg-blue-600 text-white' : 'bg-gray-200'
            ]"
        >
            {{ option.label }}
        </button>
    </div>
</template>
<script setup>
const props = defineProps({
    filterOptions: Array,
    modelValue: String
});
</script>
