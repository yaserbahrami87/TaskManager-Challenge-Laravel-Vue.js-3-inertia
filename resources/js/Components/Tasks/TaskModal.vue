<!-- File: resources/js/Components/Tasks/TaskModal.vue -->
<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-lg relative">
            <h2 class="text-xl font-bold mb-4">
                {{ editMode ? 'ویرایش تسک' : 'تسک جدید' }}
            </h2>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-sm mb-1">عنوان:</label>
                    <input v-model="localForm.title" type="text" class="input" name="title" required   />
                </div>
                <div class="mb-4">
                    <label class="block text-sm mb-1">توضیحات:</label>
                    <textarea v-model="localForm.description" class="input" rows="3" name="description"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm mb-1">تاریخ سررسید:</label>
                    <input v-model="localForm.due_date" type="date" class="input" name="due_date"  />
                </div>
                <div class="mb-4">
                    <label class="block text-sm mb-1">وضعیت:</label>
                    <select v-model="localForm.status" class="input" name="status">
                        <option value="todo">در صف انجام</option>
                        <option value="in_progress">در حال انجام</option>
                        <option value="done">تکمیل شده</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" @click="$emit('close')" class="btn-secondary">لغو</button>
                    <button type="submit" class="btn-primary">ثبت</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { watch, ref, toRefs, onMounted } from 'vue';

const props = defineProps({
    form: { type: Object, required: true },
    editMode: { type: Boolean, default: false },
});

const emit = defineEmits(['submit', 'close']);

const localForm = ref({
    title: props.form.title || '', // مقداردهی اولیه با prop دریافتی
    description: props.form.description || '',
    due_date: props.form.due_date || '',
    status: props.form.status || 'todo',
});

// وقتی prop فرم تغییر کرد، مقدار فرم محلی رو باهاش سینک کن
onMounted(() => {
    if (props.editMode) {
        localForm.value = { ...props.form };
    }
});

function submit() {
    if (!localForm.value.title.trim()) {
        alert('لطفا عنوان تسک را وارد کنید');
        return;
    }
    console.log(localForm.value);
    emit('submit', localForm.value);
}
</script>

<style scoped>
.input {
    @apply w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500;
}
.btn-primary {
    @apply bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700;
}
.btn-secondary {
    @apply bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400;
}
</style>
