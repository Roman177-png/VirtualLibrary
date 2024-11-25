<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-semibold text-gray-800">Reviews</h3>

            <div class="flex items-center space-x-4">
                <label for="ratingFilter" class="text-gray-600">Filter by rating:</label>
                <select
                    id="ratingFilter"
                    v-model="ratingFilter"
                    @change="fetchReviews"
                    class="bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="0">All</option>
                    <option value="1">1 Star</option>
                    <option value="2">2 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="5">5 Stars</option>
                </select>

            </div>
        </div>

        <div v-if="reviews.length === 0" class="text-gray-500">
            No reviews yet. Be the first to review this book!
        </div>

        <div v-for="review in reviews" :key="review.id" class="bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <h4 class="text-xl font-semibold text-blue-700">{{ review.name }}</h4>
                <span class="text-yellow-400 text-xl">{{ '⭐'.repeat(review.rating) }}</span>
            </div>
            <p class="mt-2 text-gray-600">{{ review.content }}</p>

            <div class="flex justify-end mt-4 space-x-4">
                <button
                    @click="editReview(review)"
                    class="text-yellow-500 hover:text-yellow-600 focus:outline-none transition-transform transform hover:scale-110">
                    Edit
                </button>
                <button
                    @click="deleteReview(review.id)"
                    class="text-red-500 hover:text-red-600 focus:outline-none transition-transform transform hover:scale-110">
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
            ratingFilter: 0,
        };
    },
    methods: {
        async fetchReviews() {
            try {
                const response = await axios.get(`/api/books/${this.bookId}/reviews`, {
                    params: {
                        rating: this.ratingFilter === 0 ? undefined : this.ratingFilter,
                        sort: 'created_at',
                        order: 'desc',
                    },
                });
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
