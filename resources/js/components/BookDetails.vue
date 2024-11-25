<template>
    <div class="container mx-auto mt-10">
        <div v-if="loading" class="text-center text-gray-500">Loading book details...</div>
        <div v-else-if="book" class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <h1 class="text-3xl font-bold text-blue-700 mb-4">{{ book.title }}</h1>
            <p class="text-lg text-gray-600 mb-2">Author: <span class="font-semibold">{{ book.author }}</span></p>
            <p class="text-lg text-gray-600 mb-2">Published: <span class="font-semibold">{{ book.year }}</span></p>
            <p class="text-gray-700 mb-4">{{ book.description }}</p>
            <div class="mt-6">
                <router-link
                    to="/"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300 shadow-md">
                    Back to Books
                </router-link>
            </div>
        </div>
        <div v-else class="text-center text-gray-500">Book not found.</div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            book: null,
            loading: true,
        };
    },
    async created() {
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
};
</script>

<style scoped>
.container {
    max-width: 800px;
    margin: 0 auto;
}
</style>
