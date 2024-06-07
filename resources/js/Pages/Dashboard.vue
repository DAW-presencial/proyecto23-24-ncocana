<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const categories = [
    { name: 'Book', description: 'Guarda tus libros favoritos y descubre nuevas lecturas.'},
    { name: 'Series', description: 'Mantén un seguimiento de tus series y comparte recomendaciones.'},
    { name: 'Movie', description: 'Organiza tus películas para una próxima noche de cine.'},
    { name: 'Fanfic', description: 'Sumérgete en el mundo de los fanfics y encuentra tus historias.'}
    // Agrega más categorías según tus necesidades
];

const goToType = (category) => {
    window.location.href = `/searchadvanced?type=${category}`;
} 
</script>

<template>
    <div>
        <Head :title="$t('Dashboard')" />
        <AuthenticatedLayout>
            <div class="py-12 m-10 sm:m-0">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <!-- Introducción -->
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold mb-4 text-center">{{$t('¡Bienvenido a Tu Sitio de Marcadores!')}}</h2>
                        <p class="text-lg text-gray-800 mb-4 text-center">{{$t('Organiza tus marcadores de una manera fácil y divertida. Explora nuestras categorías y encuentra nuevas lecturas, series, películas y más.')}}
                        </p>
                    </div>

                    <!-- Categorías -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-7 lg:gap-3">
                        <div v-for="(category, index) in categories" :key="index"
                            class="card bg-white shadow-md rounded-lg overflow-hidden flex flex-col justify-end">
                            <div class="card-image">
                                <img v-if="category.name === 'Book'"
                                    src="https://img.freepik.com/free-vector/hand-drawn-flat-design-stack-books-illustration_23-2149341898.jpg">
                                <img v-if="category.name === 'Series'"
                                    src="https://static.vecteezy.com/system/resources/previews/002/580/448/original/tv-series-streaming-rgb-color-icon-vector.jpg">
                                <img v-if="category.name === 'Movie'"
                                    src="https://cdn-icons-png.flaticon.com/512/4831/4831192.png">
                                <img v-if="category.name === 'Fanfic'"
                                    src="https://cdn-icons-png.freepik.com/512/4784/4784220.png">
                            </div>
                            <div class="p-4">
                                <h2 class="text-xl font-semibold mb-2">{{ $t(category.name) }}</h2>
                                <p class="text-gray-800 mb-5">{{ $t(category.description) }}</p>
                                <div>
                                    <PrimaryButton @click="goToType(category.name)">
                                        {{$t('Ver')}} {{ $t(category.name) }}
                                    </PrimaryButton>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ -->
                    <div class="mt-12">
                        <h2 class="text-3xl font-bold m-10 sm:mb-5 text-center">{{$t('Preguntas Frecuentes (FAQ)')}}</h2>
                        <div class="faq-item mb-6">
                            <h3 class="text-lg font-semibold">{{$t('¿Cómo puedo crear un marcador?')}}</h3>
                            <p class="text-gray-800">{{$t("Para crear un marcador, simplemente navega a la categoría correspondiente y haz clic en 'Crear Marcador'. Luego, completa los detalles y guárdalo.")}}
                            </p>
                        </div>
                        <div class="faq-item mb-6">
                            <h3 class="text-lg font-semibold">{{$t('¿Cómo puedo editar o eliminar un marcador?')}}</h3>
                            <p class="text-gray-800">{{$t('Para editar o eliminar un marcador, simplemente ve a la página del marcador y busca las opciones correspondientes. Puedes editar los detalles del marcador o eliminarlo por completo según sea necesario.')}}</p>
                        </div>
                        <div class="faq-item mb-6">
                            <h3 class="text-lg font-semibold">{{$t('¿Cómo puedo buscar un marcador en especifico?')}}</h3>
                            <p class="text-gray-800">{{$t('Para poder buscar un marcador, puedes ir al apartado de busqueda avanzada y buscar segun los datos del marcador.')}}
                            </p>
                        </div>
                        <div class="faq-item mb-6">
                            <h3 class="text-lg font-semibold">{{$t('¿Cómo puedo ordenar mis marcadores?')}}</h3>
                            <p class="text-gray-800">{{$t('Para ello, lo que tienes que hacer es ir al apartado donde estan todos los marcadores y ordenar por los campos que hay, lo puedes hacer de forma ascendente o descendente y si quieres buscar por tags, ¡¡Tambien puedes hacerlo!!')}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>
<style scoped>
.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.card-image img {
    transition: transform 0.3s ease;
}

.card-image img:hover {
    transform: scale(1.05);
}

.faq-item {
    border-bottom: 1px solid #ddd;
    padding-bottom: 1.5rem;
}

.faq-item:last-child {
    border-bottom: none;
}
</style>
