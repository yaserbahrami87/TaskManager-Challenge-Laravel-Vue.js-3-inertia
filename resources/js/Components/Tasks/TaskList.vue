<template>
    <div>
        <div
            v-for="task in tasks"
            :key="task.id"
            class="border rounded-xl p-4 mb-4 shadow"
        >
            <div class="flex justify-between">
                <div>
                    <h2 class="text-xl font-bold">{{ task.title }}</h2>
                    <p class="text-sm text-gray-600">{{ task.description }}</p>
                    <p class="text-sm text-gray-600">{{ task.status }}</p>
                    <p class="text-xs text-gray-400 mt-1">تا {{ task.due_date }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="$emit('edit-task', task)" class="text-blue-500">ویرایش</button>
                    <button @click="$emit('delete-task', task.id)" class="text-red-500">حذف</button>
                </div>
            </div>

            <div class="mt-4">
                <h3 class="text-sm font-semibold mb-1">زیرتسک‌ها</h3>
                <ul class="ml-4 list-disc">
                    <li
                        v-for="sub in task.subtasks || []"
                        :key="sub.id"
                        class="flex justify-between items-center"
                    >
                        <span :class="sub.is_done ? 'line-through' : ''">{{ sub.title }}</span>
                        <div class="flex gap-2">
                            <input type="checkbox" :checked="sub.is_done" @change="$emit('update-subtask', { ...sub, is_done: !sub.is_done })" />
                            <button @click="$emit('delete-subtask', sub.id)" class="text-xs text-red-500">x</button>
                        </div>
                    </li>
                </ul>
                <input
                    type="text"
                    v-model="newSubtask"
                    placeholder="عنوان زیرتسک جدید"
                    class="mt-2 px-2 py-1 border rounded text-sm w-full"
                    @keyup.enter="addSubtask(task.id)"
                />
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';
const props = defineProps({ tasks: Array });
const emit = defineEmits(['edit-task', 'delete-task', 'create-subtask', 'update-subtask', 'delete-subtask']);
const newSubtask = ref('');
function addSubtask(taskId) {
    if (!newSubtask.value.trim()) return;
    emit('create-subtask', taskId, newSubtask.value);
    newSubtask.value = '';
}
</script>
