<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/textarea.vue';
import LinkRegresar from '@/Components/linkRegresar.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const valoresIniciales = {
    Titulo:'',
    Categoria:'',
    Contenido:'',
    imagenes: [],
};

const props = defineProps({
    categorias:{type:Object}
});

const form = useForm(valoresIniciales);

const handleFileUpload = (event) => {
    form.imagenes = Array.from(event.target.files);
};

const submit = () => {
    let formData = new FormData();
    formData.append('Titulo', form.Titulo);
    formData.append('Categoria', form.Categoria);
    formData.append('Contenido', form.Contenido);
    // Agregar imágenes al FormData
    for (let i = 0; i < form.imagenes.length; i++) {
        formData.append('imagenes[]', form.imagenes[i]);
    }
    form.post(route('anuncios.store'), {
        onSuccess: () => form.reset(),
        onError: () => form.errors
    });
};

</script>

<template>
    <Head title="Gestion categorias formulario" />
    <AuthenticatedLayout>
        <template #header>
            Insertar datos del anuncio
        </template>
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
