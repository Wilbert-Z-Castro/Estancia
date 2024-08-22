<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Header from '@/Layouts/Header.vue';
import NavLink from '@/Components/NavLink.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Panel de noticias" />

    <AuthenticatedLayout>
        <template #header>
            Panel principal
        </template>
        <br>
        <nav class="grid grid-cols-1 md:grid-cols-4 gap-2 w-full">
            <nav-link 
                :href="route('dashboard')" 
                :active="route().current('dashboard')" 
                class="bg-red-500 text-white hover:bg-red-700 text-center p-2 rounded-lg">
                Bienvenida
            </nav-link>
            <nav-link 
                :href="route('dashboard')" 
                :active="route().current('dashboard')" 
                class="bg-blue-500 text-white hover:bg-blue-700 text-center p-2 rounded-lg">
                Posgrados
            </nav-link>
            <nav-link 
                :href="route('about')" 
                :active="route().current('about')" 
                class="bg-green-500 text-white hover:bg-green-700 text-center p-2 rounded-lg">
                Doctorados
            </nav-link>
            <nav-link 
                :href="route('about')" 
                :active="route().current('about')" 
                class="bg-yellow-500 text-white hover:bg-yellow-700 text-center p-2 rounded-lg">
                Eventos
            </nav-link>
            <nav-link 
                :href="route('about')" 
                :active="route().current('about')" 
                class="bg-yellow-500 text-white hover:bg-yellow-700 text-center p-2 rounded-lg">
                Otros
            </nav-link>
        </nav>
        
        <br>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <form>
                    <!-- Tu formulario aquí -->
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
