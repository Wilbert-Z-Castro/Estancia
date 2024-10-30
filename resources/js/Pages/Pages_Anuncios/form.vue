<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/textarea.vue';
import LinkRegresar from '@/Components/linkRegresar.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'

const valoresIniciales = {
    Titulo:'',
    Categoria:'',
    Contenido:'',
    imagenes: [],
};

const props = defineProps({
    categorias:{type:Object}
});
const cargando = ref(false);
const form = useForm(valoresIniciales);

const handleFileUpload = (event) => {
    form.imagenes = Array.from(event.target.files);
};

const submit = () => {
    Swal.fire({
        title: 'Cargando',
        text: 'Por favor espera mientras se envían los datos...',
        allowOutsideClick: false, // Deshabilita que el usuario cierre la alerta
        didOpen: () => {
            Swal.showLoading(); // Muestra el spinner
        }
    });
    cargando.value = true;
    let formData = new FormData();
    formData.append('Titulo', form.Titulo);
    formData.append('Categoria', form.Categoria);
    formData.append('Contenido', form.Contenido);
    // Agregar imágenes al FormData
    for (let i = 0; i < form.imagenes.length; i++) {
        formData.append('imagenes[]', form.imagenes[i]);
    }
    
    form.post(route('anuncios.store'), {
        onSuccess: () => {
            form.reset(),
            Swal.close();
            cargando.value = false;
        },
        onError: () => {
            form.errors,
            cargando.value = false;
            const firstErrorFieldId = Object.keys(form.errors)[0];
            document.getElementById(firstErrorFieldId).focus();
        },
    });
};

</script>

<template>
    <Head title="Gestion categorias formulario" />
    <AuthenticatedLayout>
        <template #header>
            Insertar datos del anuncio
        </template>
        <div v-if="cargando" role="status">
            <svg aria-hidden="true" class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
            </svg>
            <p class="" >Enviando</p>
            <span class="sr-only">Loading...</span>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
            <div class="p-6 mx-2 border-b border-gray-200">
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <!-- Fila 3 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="Titulo" value="Titulo" />
                            <TextInput
                            id="Titulo"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.Titulo"
                            required
                            placeholder="Ingrese el Titulo del anuncio"
                            autofocus
                            autocomplete="Titulo"
                            />
                            <InputError class="mt-2" :message="form.errors.Titulo" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="Categoria" value="Categoria" />
                            <select
                            id="Categoria"
                            class="mt-1 block w-full rounded border border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            v-model="form.Categoria"
                            required
                            >
                            <option v-for="categoria in categorias" :key="categoria.idCatAnuncio" :value="categoria.idCatAnuncio">
                                {{ categoria.Nombre }}
                            </option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.Categoria" />
                        </div>
                    </div>
                        
                        <!-- Fila 4 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="Contenido" value="Contenido" />
                            <textarea
                            id="Contenido"
                            type="text"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            v-model="form.Contenido"
                            required
                            placeholder="Ingrese la Descripcion del anuncio"
                            autofocus
                            autocomplete="Contenido"
                            />
                            <InputError class="mt-2" :message="form.errors.Contenido" />
                        </div>
                        
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="imagenes" value="Imágenes" />
                            <input
                            id="imagenes"
                            type="file"
                            accept="image/*"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            @change="handleFileUpload"
                            multiple
                            />
                            <InputError class="mt-2" :message="form.errors.imagenes" />
                        </div>
                    </div>
                    <div class="flex items-center justify-left mt-4">
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" />
                            </svg>

                            Registrarse
                        </PrimaryButton>
                        <LinkRegresar class="mx-2" :href="route('anuncios.index')">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />
                            </svg>

                            Regresar
                        </LinkRegresar> 
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
