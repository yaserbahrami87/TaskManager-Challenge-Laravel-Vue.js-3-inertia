<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
            <!-- Header -->
            <div class="mb-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold">
                    <slot name="header">عنوان</slot>
                </h2>
                <button @click="$emit('close')" class="text-gray-500 hover:text-red-600 text-xl">
                    &times;
                </button>
            </div>

            <!-- Body -->
            <div class="mb-4">
                <slot name="body">
                    <p>بدون محتوای بدنه</p>
                </slot>
            </div>

            <!-- Footer -->
            <div class="mt-4 flex justify-end gap-2">
                <slot name="footer">
                    <button @click="$emit('close')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">بستن</button>
                </slot>
            </div>
        </div>
    </div>
</template>

<script setup>
// این اسکریپت نیازی به چیز خاصی نداره مگر اینکه بعداً رفتار خاصی اضافه کنیم.
</script>
