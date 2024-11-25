<template>
    <div class="bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
        <nav class="bg-blue-600 text-white shadow-lg py-4">
            <div class="container mx-auto flex justify-between items-center px-4">
                <h1 class="text-2xl font-bold">📚 My Library</h1>
                <router-link to="/" class="text-lg hover:underline">Home</router-link>
            </div>
        </nav>
        <main class="container mx-auto mt-8 px-4">
            <router-view />
        </main>
        <footer class="bg-gray-800 text-white py-6 mt-10">
            <div class="text-center">
                <p>© 2024 My Library. All Rights Reserved.</p>
            </div>
        </footer>
    </div>
</template>

<script>
export default {};
</script>

<style>
body {
    font-family: 'Inter', sans-serif;
}
</style>
