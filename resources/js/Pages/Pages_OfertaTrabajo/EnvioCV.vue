<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/textarea.vue';
import LinkRegresar from '@/Components/linkRegresar.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({
    oferta:{type:Object}
});
const valoresIniciales = {
    Mensaje: '',
    Id_oferta: props.oferta.idOfertaTrabajo,
    TituloOferta: props.oferta.TituloOferta,
    imagenes: [],
};
const imagePreviews = ref([]);
const form = useForm(valoresIniciales);

const submit = () => {
    form.post(route('ofertasTrabajo.EnvioCV'), {
        onSuccess: () => form.reset(),
        onError: () => {
        const firstErrorFieldId = Object.keys(form.errors)[0];
        document.getElementById(firstErrorFieldId).focus();
        }
    });
};

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


</script>

<template>
    <Head title="Gestion categorias formulario" />
    <AuthenticatedLayout>
        <template #header>
            Envia tu CV junto con un mensaje
            
        </template>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
            <div class="p-6 mx-2 border-b border-gray-200 overflow-y-auto">
                <form @submit.prevent="submit">
                    <!-- Fila 1 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="TituloOferta" value="Titulo de la oferta" />
                            <TextInput
                                id="TituloOferta"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.TituloOferta"
                                disabled
                                placeholder="Ingrese el Titulo de la oferta"
                                autocomplete="TituloOferta"
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.TituloOferta" />
                        </div>
                    </div>

                    <!-- Fila 2 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="Mensaje" value="Mensaje adicional" />
                            <TextArea
                                id="Mensaje"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.Mensaje"
                                placeholder="Ingrese un mensaje"
                                autocomplete="Mensaje"
                            />
                            <InputError class="mt-2" :message="form.errors.Mensaje" />
                        </div>
                    </div>
                    <!-- Fila 4: Imágenes -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="imagenes" value="CV" />
                            <input
                                id="imagenes"
                                type="file"
                                accept="application/pdf, image/jpeg, image/png"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                @change="handleFileUpload"
                            />
                            <InputError class="mt-2" :message="form.errors.imagenes" />
                        </div>
                    </div>

                    <!-- Sección para mostrar previsualización de imágenes 
                     
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 mt-4">
                        <div class="col-span-12">
                            <div class="grid grid-cols-2 gap-2">
                                <div v-for="(preview, index) in imagePreviews" :key="index" class="relative">
                                    <canvas :src="preview" alt="Previsualización de imagen" class="w-20 h-20 object-cover"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 mt-4">
                        <div class="col-span-12">
                            <div class="grid grid-cols-2 gap-2">
                                <div v-for="(preview, index) in imagePreviews" :key="index" class="relative">
                                    <canvas :src="preview" alt="Previsualización de imagen" class="w-20 h-20 object-cover"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    -->
                    

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
