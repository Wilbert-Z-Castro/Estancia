<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Header from '@/Layouts/Header.vue';
import NavLink from '@/Components/NavLink.vue';
import { Head, useForm  } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2'

const errors = ref({
    importacion:'',
    expotacion:'',
});

let imagenes =null;
const valoresIniviales = {
    imagenes :null,
};

const form = useForm(valoresIniviales);
const fileupdate = (event) => {
    imagenes = event.target.files[0];
    form.imagenes = imagenes;
}


const submit= (a) => {
    Swal.fire({
        title: 'Cargando',
        text: 'Por favor espera mientras se envían los datos...',
        allowOutsideClick: false, // Deshabilita que el usuario cierre la alerta
        didOpen: () => {
            Swal.showLoading(); // Muestra el spinner
        }
    });
    form.post(route('ResturacionDB'), {
        onSuccess: (response) => {
            form.reset();
            Swal.close();
            setTimeout(() => {
                Swal.fire({
                    title: "Exito!",
                    text: "Base de datos restaurada correctamente",
                    icon: "success"
                });
            }, 300);
            console.log(response);
        },
        onError: (errors) => {
            console.log(errors);    
        },
    });

}
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
                <H1>Respaldo y restauracion de DB</H1>
                <p>En esta seccion podras realizar respaldos y restauraciones de la base de datos</p>
                <br>
                <form @submit.prevent="submit">

                    <InputLabel for="imagenes" value="Selecionar Archivo sql" />
                        <input
                        id="imagenes"
                        type="file"
                        accept=".sql"
                        @change="fileupdate"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        />
                    <InputError class="mt-2"  />
                    <br>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Hacer restauración</button>

                    <br>
                </form>
                <h3>Exporta Base de datos</h3>
                <br>
                <a :href="route('RespaldoDB')" target="_blank" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Hacer respaldo</a>
            </div>


        </div>
    </AuthenticatedLayout>
</template>
