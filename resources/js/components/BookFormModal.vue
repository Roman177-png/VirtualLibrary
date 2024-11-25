<template>
    <div v-if="show" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white w-1/2 rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">{{ isEdit ? 'Edit Book' : 'Add Book' }}</h2>
            <form @submit.prevent="handleSubmit">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Title</label>
                    <input
                        type="text"
                        v-model="book.title"
                        class="w-full px-3 py-2 border rounded"
                        required
                    />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Author</label>
                    <input
                        type="text"
                        v-model="book.author"
                        class="w-full px-3 py-2 border rounded"
                        required
                    />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Year</label>
                    <input
                        type="number"
                        v-model="book.year"
                        class="w-full px-3 py-2 border rounded"
                        required
                    />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Description</label>
                    <textarea
                        v-model="book.description"
                        class="w-full px-3 py-2 border rounded"
                    ></textarea>
                </div>
                <div class="flex justify-end">
                    <button
                        type="button"
                        @click="closeModal"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2">
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        show: Boolean,
        isEdit: Boolean,
        existingBook: {
            type: Object,
            default: null,
        },
    },
    data() {
        return {
            book: {
                title: '',
                author: '',
                year: '',
                description: '',
            },
        };
    },
    watch: {
        existingBook: {
            immediate: true,
            handler(newVal) {
                if (newVal) {
                    this.book = { ...newVal };
                }
            },
        },
    },
    methods: {
        closeModal() {
            this.$emit('close');
        },
        async handleSubmit() {
            try {
                if (this.isEdit) {
                    await this.$emit('save', { ...this.book });
                } else {
                    await this.$emit('save', { ...this.book });
                }
                this.closeModal();
            } catch (error) {
                console.error('Error saving book:', error);
            }
        },
    },
};
</script>
