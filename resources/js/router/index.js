import { createRouter, createWebHistory } from 'vue-router';
import BookList from '../components/BookList.vue';
import BookDetails from '../components/BookDetails.vue';

const routes = [
    {
        path: '/',
        name: 'BookList',
        component: BookList,
    },
    {
        path: '/books/:id',
        name: 'BookDetails',
        component: BookDetails,
        props: true,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
