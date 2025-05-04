<template>
    <div class="max-w-4xl mx-auto py-10 px-4">
        <h1 class="text-3xl font-bold mb-6">مدیریت تسک‌ها</h1>

        <div class="mb-4 flex justify-between items-center">
            <div class="space-x-2">
                <button v-for="status in filterOptions" :key="status.value"
                        @click="currentFilter = status.value"
                        :class="[
                            'px-3 py-1 rounded border',
                            currentFilter === status.value ? 'bg-blue-600 text-white' : 'bg-white text-gray-700'
                        ]">
                    {{ status.label }}
                </button>
            </div>
            <button @click="openCreateModal" class="bg-green-600 text-white px-4 py-2 rounded shadow">
                + تسک جدید
            </button>
        </div>

        <!-- لیست تسک‌ها -->
        <div v-if="filteredTasks.length">
            <div v-for="task in filteredTasks" :key="task.id" class="bg-white rounded shadow p-4 mb-4">
                <div class="flex justify-between">
                    <div>
                        <h2 class="text-xl font-semibold">{{ task.title }}</h2>
                        <p class="text-sm text-gray-500">{{ task.description }}</p>
                        <p class="text-sm text-gray-400">سررسید: {{ task.due_date ?? '---' }}</p>
                        <p class="text-sm mt-1 font-medium">وضعیت:
                            <span class="px-2 py-0.5 text-sm rounded bg-gray-100 text-gray-700">{{ task.status }}</span>
                        </p>
                    </div>
                    <div class="space-x-2">
                        <button @click="editTask(task)" class="text-yellow-500">✏️</button>
                        <button @click="deleteTask(task.id)" class="text-red-500">🗑️</button>
                    </div>
                </div>

                <!-- زیرتسک‌ها -->
                <div class="mt-4 border-t pt-3">
                    <h3 class="font-semibold mb-2">زیرتسک‌ها:</h3>
                    <div v-for="sub in task.subtasks" :key="sub.id" class="flex items-center gap-2 mb-2">
                        <input type="checkbox" :checked="sub.is_done" @change="toggleSubtaskStatus(sub, task)" />
                        <input type="text" v-model="sub.title" @blur="updateSubtask(sub)" class="flex-1 border px-2 py-1 rounded" />
                        <button @click="deleteSubtask(sub.id)" class="text-red-500">✕</button>
                    </div>
                    <div class="flex gap-2">
                        <input v-model="newSubtask.title" @keyup.enter="createSubtask(task.id)" placeholder="افزودن زیرتسک..." class="flex-1 border px-2 py-1 rounded" />
                        <button @click="createSubtask(task.id)" class="bg-blue-500 text-white px-3 py-1 rounded">➕</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="text-center text-gray-500 mt-10">هیچ تسکی وجود ندارد.</div>

        <!-- مودال فرم -->
        <Modal v-if="showModal" @close="closeModal">
            <template #header>{{ editMode ? 'ویرایش تسک' : 'تسک جدید' }}</template>
            <template #body>
                <input v-model="form.title" placeholder="عنوان" class="border w-full px-2 py-1 mb-2 rounded" />
                <textarea v-model="form.description" placeholder="توضیحات" class="border w-full px-2 py-1 mb-2 rounded"></textarea>
                <input type="date" v-model="form.due_date" class="border w-full px-2 py-1 mb-2 rounded" />
                <select v-model="form.status" class="border w-full px-2 py-1 rounded">
                    <option value="todo">در صف انجام</option>
                    <option value="in_progress">در حال انجام</option>
                    <option value="done">تکمیل شده</option>
                </select>
            </template>
            <template #footer>
                <button @click="submitForm" class="bg-blue-600 text-white px-4 py-2 rounded">ثبت</button>
            </template>
        </Modal>

        <!-- Toast پیام موفقیت -->
        <transition name="fade">
            <div v-if="successMessage" class="fixed bottom-5 left-5 bg-green-600 text-white py-2 px-4 rounded shadow">
                {{ successMessage }}
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';

const { props } = usePage();
const tasks = ref(props.tasks);

const showModal = ref(false);
const editMode = ref(false);
const selectedTaskId = ref(null);
const form = ref({ title: '', description: '', due_date: '', status: 'todo' });
const newSubtask = ref({ title: '' });
const currentFilter = ref('all');
const successMessage = ref('');

const filterOptions = [
    { label: 'همه', value: 'all' },
    { label: 'در حال انجام', value: 'in_progress' },
    { label: 'تکمیل شده', value: 'done' },
    { label: 'در صف انجام', value: 'todo' }
];

const filteredTasks = computed(() => {
    if (currentFilter.value === 'all') return tasks.value;
    return tasks.value.filter(t => t.status === currentFilter.value);
});

function openCreateModal() {
    form.value = { title: '', description: '', due_date: '', status: 'todo' };
    selectedTaskId.value = null;
    editMode.value = false;
    showModal.value = true;
}

function editTask(task) {
    form.value = {
        title: task.title,
        description: task.description,
        due_date: task.due_date,
        status: task.status
    };
    selectedTaskId.value = task.id;
    editMode.value = true;
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
}

function submitForm() {
    const route = editMode.value ? `/tasks/${selectedTaskId.value}` : '/tasks';
    const method = editMode.value ? 'patch' : 'post';

    router[method](route, form.value, {
        onSuccess: (data) => {
            successMessage.value = editMode.value ? 'تسک ویرایش شد' : 'تسک جدید ثبت شد';

            // بروزرسانی مستقیم داده‌های تسک‌ها
            if (editMode.value) {
                const taskIndex = tasks.value.findIndex(task => task.id === selectedTaskId.value);
                tasks.value[taskIndex] = data.task; // به‌روزرسانی تسک ویرایش‌شده
            } else {
                tasks.value.push(data.task); // اضافه کردن تسک جدید
            }

            setTimeout(() => successMessage.value = '', 3000);
        }
    });

    closeModal();
}

function deleteTask(id) {
    if (confirm('از حذف مطمئن هستید؟')) {
        router.delete(`/tasks/${id}`, {
            onSuccess: () => {
                successMessage.value = 'تسک حذف شد';

                // حذف تسک از آرایه
                tasks.value = tasks.value.filter(task => task.id !== id);

                setTimeout(() => successMessage.value = '', 3000);
            }
        });
    }
}

function createSubtask(taskId) {
    if (!newSubtask.value.title) return;
    router.post(`/tasks/${taskId}/subtasks`, newSubtask.value, {
        onSuccess: (data) => {
            newSubtask.value.title = '';

            // اضافه کردن زیرتسک به لیست تسک‌ها
            const taskIndex = tasks.value.findIndex(task => task.id === taskId);
            tasks.value[taskIndex].subtasks.push(data.subtask); // اضافه کردن زیرتسک جدید

            setTimeout(() => successMessage.value = '', 3000);
        }
    });
}

function updateSubtask(subtask) {
    router.patch(`/subtasks/${subtask.id}`, subtask, {
        onSuccess: () => successMessage.value = 'زیرتسک بروزرسانی شد'
    });
}

function toggleSubtaskStatus(subtask, task) {
    subtask.is_done = !subtask.is_done;
    router.patch(`/subtasks/${subtask.id}`, subtask, {
        onSuccess: () => {
            successMessage.value = 'وضعیت زیرتسک تغییر کرد';

            // به‌روزرسانی وضعیت زیرتسک
            const taskIndex = tasks.value.findIndex(t => t.id === task.id);
            const subtaskIndex = tasks.value[taskIndex].subtasks.findIndex(s => s.id === subtask.id);
            tasks.value[taskIndex].subtasks[subtaskIndex] = subtask; // به‌روزرسانی زیرتسک

            // بررسی و بروزرسانی وضعیت تسک
            checkAndUpdateTaskStatus(task);
        }
    });
}

function checkAndUpdateTaskStatus(task) {
    const allDone = task.subtasks.every(s => s.is_done);
    if (allDone && task.status !== 'done') {
        router.patch(`/tasks/${task.id}`, { status: 'done' }, {
            onSuccess: () => {
                successMessage.value = 'وضعیت تسک به "انجام شده" بروزرسانی شد';
                setTimeout(() => successMessage.value = '', 3000);
            }
        });
    }
}

function deleteSubtask(id) {
    if (confirm('زیرتسک حذف شود؟')) {
        router.delete(`/subtasks/${id}`, {
            onSuccess: () => {
                successMessage.value = 'زیرتسک حذف شد';

                // حذف زیرتسک از آرایه
                tasks.value.forEach(task => {
                    task.subtasks = task.subtasks.filter(sub => sub.id !== id);
                });

                setTimeout(() => successMessage.value = '', 3000);
            }
        });
    }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.4s;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
