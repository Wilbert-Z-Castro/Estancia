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
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div>
                    <a :href="route('Egresados.ReporteEgresados')" target="_blank" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 me-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        Crear pdf Egresados
                    </a>
                    <a :href="route('Ponencias.ReportePonencias')" target="_blank" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 me-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        Crear pdf anuncios y ponencias
                    </a>
                    <a :href="route('Carreras.ReporteCarreras')" target="_blank" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 me-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        Crear pdf carreras
                    </a>
                    <div>
                        <!-- Inputs para filtrar por fecha -->
                        <label for="start">Fecha de Inicio</label>
                        <input type="date" v-model="startDate" @change="fetchData">

                        <label for="end">Fecha de Fin</label>
                        <input type="date" v-model="endDate" @change="fetchData">
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
