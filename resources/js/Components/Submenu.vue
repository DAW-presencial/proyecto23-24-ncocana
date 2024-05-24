<!-- Componente del submenú -->
<template>
    <div class="absolute top-full left-0 bg-white border border-gray-200 py-2 px-4 rounded shadow-lg">
        <ul>
            <li><a href="#" class="block py-2 text-gray-800 hover:bg-gray-200">Español</a></li>
            <li><a href="#" class="block py-2 text-gray-800 hover:bg-gray-200">Inglés</a></li>
            <li><a href="#" class="block py-2 text-gray-800 hover:bg-gray-200">Francés</a></li>
            <!-- Agrega más idiomas según sea necesario -->
        </ul>
        <button @click="closeSubMenu"
            class="block mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">Cerrar</button>
    </div>
</template>

<script>
export default {
    methods: {
        closeSubMenu() {
            this.$emit('close');
        },
    },
};
</script>
