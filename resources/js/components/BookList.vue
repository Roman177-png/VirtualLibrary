<template>
    <div>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-semibold text-gray-800">Books Collection</h2>
            <button
                @click="openAddForm"
                class="flex items-center bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 shadow-md text-sm">
                <span class="material-icons mr-2">Add New Book</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <input
                v-model="filters.title"
                type="text"
                placeholder="Search by Title"
                class="p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">

            <input
                v-model="filters.author"
                type="text"
                placeholder="Search by Author"
                class="p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">

            <input
                v-model.number="filters.year"
                type="number"
                placeholder="Filter by Year"
                class="p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">

            <select
                v-model="sortOption"
                class="p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
                <option value="title">Sort by Title</option>
                <option value="author">Sort by Author</option>
                <option value="year">Sort by Year</option>
            </select>
        </div>

        <BookFormModal
            :show="showForm"
            :isEdit="isEditing"
            :existingBook="selectedBook"
            @close="closeForm"
            @save="saveBook"
        />

        <div v-if="loading" class="text-center text-gray-500">Loading books...</div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div
                v-for="book in filteredBooks"
                :key="book.id"
                class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:shadow-lg">
                <h3 class="text-lg font-bold text-blue-700 mb-1 truncate">{{ book.title }}</h3>
                <p class="text-sm text-gray-600">by {{ book.author }}</p>
                <p class="text-xs text-gray-500">Published: {{ book.year }}</p>
                <p class="text-gray-700 mt-4 text-sm truncate">{{ book.description }}</p>

                <div class="flex justify-between items-center mt-4 space-x-2">
                    <router-link
                        :to="`/books/${book.id}`"
                        class="flex-1 bg-green-500 text-white py-2 rounded-md hover:bg-green-600 text-sm text-center">
                        <span class="material-icons text-base mr-1">Details</span>
                    </router-link>
                    <button
                        @click="editBook(book)"
                        class="flex-1 bg-yellow-500 text-white py-2 rounded-md hover:bg-yellow-600 text-sm">
                        <span class="material-icons text-base mr-1">Edit</span>
                    </button>
                    <button
                        @click="deleteBook(book.id)"
                        class="flex-1 bg-red-500 text-white py-2 rounded-md hover:bg-red-600 text-sm">
                        <span class="material-icons text-base mr-1">Remove</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import BookFormModal from './BookFormModal.vue';
import axios from 'axios';

export default {
    components: { BookFormModal },
    data() {
        return {
            books: [],
            loading: true,
            showForm: false,
            isEditing: false,
            selectedBook: null,
            filters: {
                title: '',
                author: '',
                year: null,
            },
            sortOption: 'title',
        };
    },
    computed: {
        filteredBooks() {
            let filtered = this.books;

            if (this.filters.title) {
                filtered = filtered.filter((book) =>
                    book.title.toLowerCase().includes(this.filters.title.toLowerCase())
                );
            }
            if (this.filters.author) {
                filtered = filtered.filter((book) =>
                    book.author.toLowerCase().includes(this.filters.author.toLowerCase())
                );
            }
            if (this.filters.year) {
                filtered = filtered.filter((book) => book.year == this.filters.year);
            }

            if (this.sortOption === 'title') {
                filtered = filtered.sort((a, b) => a.title.localeCompare(b.title));
            } else if (this.sortOption === 'author') {
                filtered = filtered.sort((a, b) => a.author.localeCompare(b.author));
            } else if (this.sortOption === 'year') {
                filtered = filtered.sort((a, b) => a.year - b.year);
            }

            return filtered;
        },
    },
    methods: {
        async fetchBooks() {
            this.loading = true;
            try {
                const response = await axios.get('/api/books');
                this.books = response.data;
            } catch (error) {
                console.error('Error fetching books:', error);
            } finally {
                this.loading = false;
            }
        },
        openAddForm() {
            this.isEditing = false;
            this.selectedBook = null;
            this.showForm = true;
        },
        editBook(book) {
            this.isEditing = true;
            this.selectedBook = book;
            this.showForm = true;
        },
        closeForm() {
            this.showForm = false;
        },
        async saveBook(book) {
            try {
                if (this.isEditing) {
                    const response = await axios.put(`/api/books/${book.id}`, book);
                    const index = this.books.findIndex((b) => b.id === book.id);
                    if (index !== -1) this.books.splice(index, 1, response.data);
                } else {
                    const response = await axios.post('/api/books', book);
                    this.books.push(response.data);
                }
            } catch (error) {
                console.error('Error saving book:', error);
            }
        },
        async deleteBook(id) {
            try {
                await axios.delete(`/api/books/${id}`);
                this.books = this.books.filter((book) => book.id !== id);
            } catch (error) {
                console.error('Error deleting book:', error);
            }
        },
    },
    mounted() {
        this.fetchBooks();
    },
};
</script>

<style scoped>
button, a {
    display: flex;
    justify-content: center;
    align-items: center;
    transition: background-color 0.3s ease;
}
button:hover, a:hover {
    transform: scale(1.05);
}
</style>
