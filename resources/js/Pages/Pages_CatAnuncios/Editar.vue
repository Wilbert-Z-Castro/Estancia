<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import LinkRegresar from '@/Components/linkRegresar.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2'


const props = defineProps({
    categoria:{type:Object}
});

const valoresIniviales = {
    Nombre: props.categoria.Nombre,
    Descripcion: props.categoria.Descripcion,
    Color: props.categoria.Color,
};

const form = useForm(valoresIniviales);

const submit = () => {
    form.put(route('cat_anuncios.update', props.categoria.idCatAnuncio));
};

const colores = [
    { name: 'Rojo', value: 'red' },
    { name: 'Verde', value: 'green' },
    { name: 'Azul', value: 'blue' },
    { name: 'Amarillo', value: 'yellow' },
    { name: 'Naranja', value: 'orange' },
    { name: 'Morado', value: 'purple' }
];
</script>

<template>
    <Head title="Gestion categorias formulario" />
    <AuthenticatedLayout>
        <template #header>
            Actualizar Categoria de anuncios
            
        </template>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
            <div class="p-6 mx-2 border-b border-gray-200">
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-12">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="Nombre" value="Nombre" />
                            <TextInput
                                id="Nombre"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.Nombre"
                                required
                                autofocus
                                autocomplete="Nombre"
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.Nombre" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="Descripcion" value="Descripcion" />
                            <TextInput
                                id="Descripcion"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.Descripcion"
                                required
                                autofocus
                                autocomplete="Descripcion"
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.Descripcion" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="Color" value="Color" />
                            <select
                                id="Color"
                                class="mt-1 block w-full rounded border border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                v-model="form.Color"
                                required
                            >
                                <option v-for="color in colores" :key="color.value" :value="color.value">
                                    {{ color.name }}
                                </option>
                            </select>
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.Color" />
                        </div>
                    </div>

                    <div class="flex items-center justify-left mt-4">
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" />
                            </svg>

                            Actualizar
                        </PrimaryButton>
                        <LinkRegresar class="mx-2" :href="route('cat_anuncios.index')">
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
