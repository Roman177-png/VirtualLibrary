<template>
    <div class="space-y-6">
        <h3 class="text-2xl font-semibold text-gray-800">Reviews</h3>

        <div v-if="reviews.length === 0" class="text-gray-500">
            No reviews yet. Be the first to review this book!
        </div>

        <div v-for="review in reviews" :key="review.id" class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
            <div class="flex items-center justify-between">
                <h4 class="text-xl font-semibold text-blue-700">{{ review.name }}</h4>
                <span class="text-yellow-400">{{ '⭐'.repeat(review.rating) }}</span>
            </div>
            <p class="mt-2 text-gray-600">{{ review.content }}</p>
            <div class="flex justify-end mt-4 space-x-4">
                <button
                    @click="editReview(review)"
                    class="text-yellow-500 hover:text-yellow-600 focus:outline-none">
                    Edit
                </button>
                <button
                    @click="deleteReview(review.id)"
                    class="text-red-500 hover:text-red-600 focus:outline-none">
                    Delete
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        bookId: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            reviews: [],
        };
    },
    methods: {
        async fetchReviews() {
            try {
                const response = await axios.get(`/api/books/${this.bookId}/reviews`);
                this.reviews = response.data;
            } catch (error) {
                console.error('Error fetching reviews:', error);
            }
        },
        async deleteReview(id) {
            try {
                await axios.delete(`/api/books/${this.bookId}/reviews/${id}`);
                this.reviews = this.reviews.filter(review => review.id !== id);
            } catch (error) {
                console.error('Error deleting review:', error);
            }
        },
        editReview(review) {
            this.$emit('edit-review', review);
        }
    },
    mounted() {
        this.fetchReviews();
    }
};
</script>

<style scoped>
button {
    transition: all 0.3s ease;
}
button:hover {
    transform: scale(1.1);
}
</style>
