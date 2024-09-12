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

const valoresIniciales = {
    NombreCarrera: '',
    Descripcion: '',
    dirCarrera_id: 0,
    UbicacionOficinas: '',
    imagenes: [], // Asegúrate de que esto sea un array vacío para manejar múltiples imágenes
};

const imagePreviews = ref([]);

const form = useForm(valoresIniciales);

const handleFileUpload = (event) => {
    const files = Array.from(event.target.files);
    form.imagenes = files;

    imagePreviews.value = []; 
    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreviews.value.push(e.target.result); 
        };
        reader.readAsDataURL(file);
    });
};

const submit = () => {
    let formData = new FormData();
    formData.append('NombreCarrera', form.NombreCarrera);
    formData.append('Descripcion', form.Descripcion);
    formData.append('dirCarrera_id', form.dirCarrera_id);
    formData.append('UbicacionOficinas', form.UbicacionOficinas);
    formData.append('imagenes[]', form.imagenes[0]); 
    form.post(route('carreras.store'), {
        onSuccess: () => form.reset(),
        onError: () => {
            const firstErrorFieldId = Object.keys(form.errors)[0];
            document.getElementById(firstErrorFieldId).focus();
        }
    });
};

const props = defineProps({
    DirCarrera: Array,
});
</script>


<template>
    <Head title="Gestion categorias formulario" />
    <AuthenticatedLayout>
        <template #header>
            Insertar datos del Carrera
            {{  }}
        </template>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 mx-2 border-b border-gray-200">
                <form @submit.prevent="submit">
                    <!-- Fila 1 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="NombreCarrera" value="Abreviación  de la carrera" />
                            <TextInput
                                id="NombreCarrera"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.NombreCarrera"
                                required
                                placeholder="Ingrese el nombre de la carrera"
                                autocomplete="NombreCarrera"
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.NombreCarrera" />
                        </div>
                    </div>

                    <!-- Fila 2 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="Descripcion" value="Nombre completo de la carrera" />
                            <TextArea
                                id="Descripcion"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.Descripcion"
                                required
                                placeholder="Ingrese el Nombre completo de la carrera"
                                autocomplete="Descripcion"
                            />
                            <InputError class="mt-2" :message="form.errors.Descripcion" />
                        </div>
                    </div>

                    <!-- Fila 3 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="UbicacionOficinas" value="Ubicación de las oficinas" />
                            <TextInput
                                id="UbicacionOficinas"
                                type="text"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.UbicacionOficinas"
                                required
                                placeholder="Ingrese la ubicación de las oficinas"
                                autocomplete="UbicacionOficinas"
                            />
                            <InputError class="mt-2" :message="form.errors.UbicacionOficinas" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="dirCarrera_id" value="Director de carrera" />
                            <select
                                id="dirCarrera_id"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.dirCarrera_id"
                                required
                                autocomplete="id_DirCarrera"
                            >
                            <option v-for="director in DirCarrera":key="director.dirCarrera_id":value="director.dirCarrera_id"
                            >
                                {{ director.user_name }}
                            </option>
                            </select>
                            
                        </div>
                    </div>

                    <!-- Fila 4: Imágenes -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="imagenes" value="Imágenes" />
                            <input
                                id="imagenes"
                                type="file"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                @change="handleFileUpload"
                                
                            />
                            <InputError class="mt-2" :message="form.errors.imagenes" />
                        </div>
                    </div>

                    <!-- Sección para mostrar previsualización de imágenes -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 mt-4">
                        <div class="col-span-12">
                            <div class="grid grid-cols-2 gap-2">
                                <div v-for="(preview, index) in imagePreviews" :key="index" class="relative">
                                    <img :src="preview" alt="Previsualización de imagen" class="w-20 h-20 object-cover" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-left mt-4">
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" />
                            </svg>
                            Registrar
                        </PrimaryButton>
                        <LinkRegresar class="mx-2" :href="route('carreras.indexGestion')">
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
