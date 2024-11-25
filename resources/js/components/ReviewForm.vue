<template>
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">{{ isEdit ? 'Edit Review' : 'Add Review' }}</h2>

            <form @submit.prevent="submitForm">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">Your Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        id="name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    />
                </div>

                <div class="mb-4">
                    <label for="rating" class="block text-gray-700 font-semibold mb-2">Rating</label>
                    <select
                        v-model="form.rating"
                        id="rating"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                        <option value="1">1 Star</option>
                        <option value="2">2 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="5">5 Stars</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="content" class="block text-gray-700 font-semibold mb-2">Review</label>
                    <textarea
                        v-model="form.content"
                        id="content"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    ></textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <button
                        type="button"
                        @click="closeForm"
                        class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500">
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
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
        show: {
            type: Boolean,
            required: true
        },
        isEdit: {
            type: Boolean,
            default: false
        },
        existingReview: {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            form: {
                name: '',
                rating: 1,
                content: ''
            }
        };
    },
    methods: {
        closeForm() {
            this.$emit('close');
        },
        submitForm() {
            if (this.isEdit) {
                this.$emit('update-review', this.form);
            } else {
                this.$emit('add-review', this.form);
            }
        }
    },
    watch: {
        existingReview: {
            handler(newReview) {
                if (newReview) {
                    this.form = { ...newReview };
                }
            },
            immediate: true
        }
    }
};
</script>

<style scoped>
form input,
form select,
form textarea {
    transition: border-color 0.3s ease;
}
form input:focus,
form select:focus,
form textarea:focus {
    border-color: #4CAF50;
}
</style>
