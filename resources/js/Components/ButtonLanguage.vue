<!-- Componente del botón principal -->
<template>
    <div class="relative" @click="toggleSubMenu">
        <button
            class="bg-blue-500 text-white px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">Idiomas</button>
        <sub-menu v-if="showSubMenu" @close="closeSubMenu"></sub-menu>
    </div>
</template>

<script>
import SubMenu from './SubMenu.vue'; // Importa el componente del submenú

export default {
    components: {
        SubMenu,
    },
    data() {
        return {
            showSubMenu: false,
        };
    },
    methods: {
        toggleSubMenu() {
            this.showSubMenu = !this.showSubMenu;
        },
        closeSubMenu() {
            this.showSubMenu = false;
        },
    },
};
</script>
