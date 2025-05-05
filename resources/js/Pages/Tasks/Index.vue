<!-- File: resources/js/Pages/Tasks/Index.vue -->
<template>
    <div class="max-w-4xl mx-auto py-10 px-4">
        <h1 class="text-3xl font-bold mb-6">مدیریت تسک‌ها</h1>

        <FilterButtons :filter-options="filterOptions" v-model="currentFilter" />
        <CreateButton @click="openCreateModal" />

        <TaskList
            :tasks="filteredTasks"
            @edit-task="editTask"
            @delete-task="deleteTask"
            @create-subtask="createSubtask"
            @update-subtask="updateSubtask"
            @delete-subtask="deleteSubtask"
        />

        <TaskModal
            v-if="showModal"
            :form="form"
            :edit-mode="editMode"
            @submit="submitForm"
            @close="closeModal"
        />

        <Toast v-if="toast.message" :type="toast.type" :message="toast.message" />
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

import FilterButtons from '@/Components/Tasks/FilterButtons.vue';
import CreateButton from '@/Components/Tasks/CreateButton.vue';
import TaskList from '@/Components/Tasks/TaskList.vue';
import TaskModal from '@/Components/Tasks/TaskModal.vue';
import Toast from '@/Components/Shared/Toast.vue';

const { props } = usePage();
const tasks = ref(props.tasks ?? []);

const showModal = ref(false);
const editMode = ref(false);
const selectedTaskId = ref(null);

const form = ref({ title: '', description: '', due_date: '', status: 'todo' });
const currentFilter = ref('all');

const toast = ref({ message: '', type: 'success' });

watch(() => props.tasks, (newTasks) => {
    tasks.value = newTasks;
});

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

function showToast(message, type = 'success') {
    toast.value = { message, type };
    setTimeout(() => {
        toast.value.message = '';
    }, 3000);
}

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

function refresh() {
    router.reload({
        only: ['tasks'],
        onSuccess: () => {
            // بروزرسانی داده‌های لوکال بعد از رفرش
            tasks.value = usePage().props.tasks;
        }
    });
}


function submitForm(localForm) {

    const endpoint = editMode.value ? `/tasks/${selectedTaskId.value}` : '/tasks';
    const method = editMode.value ? 'patch' : 'post';
    console.log(localForm);
    router[method](endpoint, localForm, {
        preserveScroll: true,
        onSuccess: () => {
            showToast(editMode.value ? 'تسک با موفقیت ویرایش شد' : 'تسک جدید ایجاد شد', 'success');
            closeModal();
            refresh();
        },
        onError: (errors) => {
            const messages = Object.values(errors).flat().join(' | ');
            showToast(messages, 'error');
        }
    });
}

function deleteTask(id) {
    if (confirm('آیا از حذف تسک مطمئن هستید؟')) {
        router.delete(`/tasks/${id}`, {
            preserveScroll: true,
            onSuccess: () => {
                showToast('تسک حذف شد', 'success');
                refresh();
            },

        });
    }
}

function createSubtask(taskId, title) {
    router.post(`/tasks/${taskId}/subtasks`, { title }, {
        preserveScroll: true,
        onSuccess: () => {
            showToast('زیرتسک اضافه شد', 'success');
            refresh();
        },
        onError: (errors) => {
            const messages = Object.values(errors).flat().join(' | ');
            showToast(messages, 'error');
            refresh();
        }
    });
}

function updateSubtask(subtask) {
    console.log(subtask);
    router.patch(`/subtasks/${subtask.id}`, subtask, {
        preserveScroll: true,
        onSuccess: () => {
            showToast('زیرتسک بروزرسانی شد', 'success');
            refresh();
        }
    });
}

function deleteSubtask(id) {
    if (confirm('آیا از حذف زیرتسک مطمئن هستید؟')) {
        router.delete(`/subtasks/${id}`, {
            preserveScroll: true,
            onSuccess: () => {
                showToast('زیرتسک حذف شد', 'success');
                refresh();
            }
        });
    }
}
</script>
