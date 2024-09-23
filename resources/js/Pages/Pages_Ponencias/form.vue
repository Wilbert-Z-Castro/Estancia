<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/textarea.vue';
import LinkRegresar from '@/Components/linkRegresar.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    ponentes:{type:Object},
    egresados:{type:Object},
    EgresadosPorCarrera:{type:Object},
});

const valoresIniciales = {
    TituloPonencia: '',
    DescripcionPonencia: '',
    Fecha: '',
    Lugar: '',
    imagenes: [],
    egresados: [],
    
     // Asegúrate de que esto sea un array vacío para manejar múltiples imágenes
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
    form.post(route('Ponencias.store'), {
        onSuccess: () => form.reset(),
        onError: () => {
        const firstErrorFieldId = Object.keys(form.errors)[0];
        document.getElementById(firstErrorFieldId).focus();
        }
    });
};

const maxDate = ref('');

const setMaxDate = () => {
    const today = new Date();
    const year = today.getFullYear();
    const month = (today.getMonth() + 1).toString().padStart(2, '0');
    const day = today.getDate().toString().padStart(2, '0');
    const hours = today.getHours().toString().padStart(2, '0');
    const minutes = today.getMinutes().toString().padStart(2, '0');
    maxDate.value = `${year}-${month}-${day}T${hours}:${minutes}`; // Ajustado para datetime-local
};

// Llamar a setMaxDate cuando el componente se monta
onMounted(() => {
    setMaxDate();
});



</script>


<template>
    <Head title="Gestion categorias formulario" />
    <AuthenticatedLayout>
        <template #header>
            crear ponencia
            
        </template>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 mx-2 border-b border-gray-200">

                <form @submit.prevent="submit">
                    <!-- Fila 1 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="TituloPonencia" value="Titulo de la ponencia" />
                            <TextInput
                                id="TituloPonencia"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.TituloPonencia"
                                required
                                placeholder="Ingrese el titulo de la ponencia"
                                autocomplete="TituloPonencia"
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.TituloPonencia" />
                        </div>
                    </div>

                    <!-- Fila 2 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="DescripcionPonencia" value="Descripcion de ponencia" />
                            <TextArea
                                id="DescripcionPonencia"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.DescripcionPonencia"
                                required
                                placeholder="Ingrese la descripción de la ponencia"
                                autocomplete="DescripcionPonencia"
                            />
                            <InputError class="mt-2" :message="form.errors.DescripcionPonencia" />
                        </div>
                    </div>

                    <!-- Fila 3 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="Lugar" value="Lugra de la ponencia" />
                            <TextInput
                                id="Lugar"
                                type="text"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.Lugar"
                                required
                                placeholder="Ingrese el lugar de la ponencia o la URL del evento"
                                autocomplete="Lugar"
                            />
                            <InputError class="mt-2" :message="form.errors.Lugar" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="Fecha" value="Fecha de Asignación" />
                            <TextInput
                            id="Fecha"
                            type="datetime-local"
                            class="mt-1 block w-full"
                            v-model="form.Fecha"
                            :min="maxDate"
                            required
                            />
                            <InputError class="mt-2" :message="form.errors.Fecha" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="egresados" value="Egresados" />
                            <v-select
                                v-model="form.egresados"
                                id="egresados"
                                class="mt-1 w-full "
                                :style="{ '--vs-font-size': '1rem' }"
                                :options="props.EgresadosPorCarrera"
                                placeholder="Seleccione uno o varios egresados"
                                label="NombreCompleto"
                                :reduce="egresados => egresados.idEgresado"
                                :required="!form.egresados"
                                multiple
                                />
                            <InputError class="mt-2" :message="form.errors.egresados" />
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
                            Registrarse
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
