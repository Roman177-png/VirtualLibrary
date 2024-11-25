<template>
    <div class="container mx-auto mt-10 p-6 bg-gradient-to-b from-gray-100 to-gray-300 rounded-lg shadow-lg">
        <div v-if="loading" class="text-center text-gray-500">
            <p class="text-lg">Loading book details...</p>
        </div>

        <div v-else-if="book" class="bg-white p-8 rounded-lg shadow-lg border border-gray-200">
            <h1 class="text-4xl font-extrabold text-blue-800 mb-6">{{ book.title }}</h1>
            <p class="text-xl text-gray-600 mb-3">Author: <span class="font-semibold">{{ book.author }}</span></p>
            <p class="text-lg text-gray-500 mb-5">Published: <span class="font-semibold">{{ book.year }}</span></p>

            <p class="text-gray-700 text-lg mb-6">{{ book.description }}</p>

            <div class="mt-8">
                <h3 class="text-2xl font-semibold text-blue-700 mb-4">Reviews</h3>

                <ReviewList :bookId="book.id" @edit-review="openEditReviewForm" />

                <div class="mt-6 text-right">
                    <button
                        @click="openAddReviewForm"
                        class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 focus:outline-none transition duration-300">
                        <span class="material-icons mr-2">add</span>
                        Add Review
                    </button>
                </div>
            </div>

            <div class="mt-6">
                <router-link
                    to="/"
                    class="inline-block bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 focus:outline-none transition duration-300">
                    Back to Books
                </router-link>
            </div>
        </div>

        <div v-else class="text-center text-gray-500">
            <p class="text-lg">Book not found.</p>
        </div>

        <ReviewForm
            v-if="showReviewForm"
            :show="showReviewForm"
            :isEdit="isEditing"
            :existingReview="selectedReview"
            @close="closeReviewForm"
            @add-review="addReview"
            @update-review="updateReview"
        />
    </div>
</template>

<script>
import axios from 'axios';
import ReviewList from './ReviewList.vue';
import ReviewForm from './ReviewForm.vue';

export default {
    components: {
        ReviewList,
        ReviewForm,
    },
    data() {
        return {
            book: null,
            loading: true,
            showReviewForm: false,
            isEditing: false,
            selectedReview: null
        };
    },
    methods: {
        async fetchBookDetails() {
            const bookId = this.$route.params.id;
            try {
                const response = await axios.get(`/api/books/${bookId}`);
                this.book = response.data;
            } catch (error) {
                console.error('Error fetching book details:', error);
                this.book = null;
            } finally {
                this.loading = false;
            }
        },

        openAddReviewForm() {
            this.selectedReview = null;
            this.isEditing = false;
            this.showReviewForm = true;
        },

        openEditReviewForm(review) {
            this.selectedReview = review;
            this.isEditing = true;
            this.showReviewForm = true;
        },

        closeReviewForm() {
            this.showReviewForm = false;
            this.selectedReview = null;
            this.isEditing = false;
        },

        addReview(reviewData) {
            axios.post(`/api/books/${this.book.id}/reviews`, reviewData)
                .then(response => {
                    this.book.reviews.push(response.data);
                    this.closeReviewForm();
                })
                .catch(error => {
                    console.error('Error adding review:', error);
                });
        },

        updateReview(updatedReview) {
            axios.put(`/api/books/${this.book.id}/reviews/${updatedReview.id}`, updatedReview)
                .then(response => {
                    const index = this.book.reviews.findIndex(r => r.id === updatedReview.id);
                    if (index !== -1) {
                        this.book.reviews[index] = response.data;
                    }
                    this.closeReviewForm();
                })
                .catch(error => {
                    console.error('Error updating review:', error);
                });
        }
    },
    mounted() {
        this.fetchBookDetails();
    }
};
</script>

<style scoped>
.container {
    max-width: 1000px;
    margin: 0 auto;
}

button,
a {
    transition: all 0.3s ease;
}

button:hover,
a:hover {
    transform: scale(1.05);
}
</style>
