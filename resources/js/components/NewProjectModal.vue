<template>
    <modal name="new-project" classes="p-10 bg-card rounded-lg" height="auto">
        <h1 class="font-normal mb-16 text-center text-2xl">Let's Start Something New</h1>

        <form @submit.prevent="submit">
            <div class="flex">
                <div class="flex-1 mr-4">
                    <div class="mb-4">
                        <label for="title" class="text-sm block mb-2">Title</label>
                        <input
                            type="text"
                            id="title"
                            class="border p-2 text-sm w-full rounded"
                            :class="form.errors.title ? 'border-error' : 'border-muted-light'"
                            v-model="form.title">
                        <span class="text-xs italic text-error" v-if="form.errors.title">{{ form.errors.title[0] }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="text-sm block mb-2">Description</label>
                        <textarea
                            type="text"
                            id="description"
                            class="border border-muted-light p-2 text-sm w-full rounded"
                            :class="form.errors.description ? 'border-error' : 'border-muted-light'"
                            rows="7" v-model="form.description">
                        </textarea>
                        <span class="text-xs italic text-error" v-if="form.errors.description">{{ form.errors.description[0] }}</span>
                    </div>
                </div>

                <div class="flex-1 ml-4">
                    <div class="mb-4">
                        <label class="text-sm block mb-2">Need Some Tasks?</label>
                        <input v-for="(task, index) in form.tasks" :key="index"
                            v-model="task.body"
                            type="text"
                            class="border border-muted-light mb-2 p-2 text-sm w-full rounded"
                            :placeholder="`Task ${++index}`">
                    </div>

                    <button type="button" class="button" @click="addTask">Add New Task Field</button>
                </div>
            </div>

            <footer class="flex justify-end">
                <button type="button" class="button is-outlined mr-4" @click="close">Cancel</button>
                <button class="button">Create Project</button>
            </footer>
        </form>
    </modal>
</template>

<script>
import BirdboardForm from './BirdboardForm';

export default {
    data: () => ({
        form: new BirdboardForm({
            title: '',
            description: '',
            tasks: [
                { body: '' }
            ],
        }),
    }),
    methods: {
        addTask() {
            this.form.tasks.push({ body: '' });
        },
        submit() {
            this.form.submit('/projects')
                .then(({ data }) => window.location = data.message);
        },
        close() {
            this.$modal.hide('new-project');
            this.form = new BirdboardForm({
                title: '',
                description: '',
                tasks: [
                    { body: '' }
                ]
            });
        }
    }
}
</script>

<style>

</style>
