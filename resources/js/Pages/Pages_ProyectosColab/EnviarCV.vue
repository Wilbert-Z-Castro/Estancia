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
    proyecto:{type: Object}

});

const valoresIniciales = {
    Mensaje: '',
    TituloProyecto: props.proyecto.TituloProyecto,
    Id_proyecto: props.proyecto.idProyectoColab,
    imagenes: null,

};
const imagePreviews = ref([]);

const form = useForm(valoresIniciales);

const handleFileUpload = (event) => {
    const file = event.target.files[0]; // Solo un archivo
    form.imagenes = file;

    imagePreviews.value = []; 
    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreviews.value.push(e.target.result); 
    };
    reader.readAsDataURL(file);
};

const cargando = ref(false);
const submit = () => {
    cargando.value = true;

    form.post(route('ProyectosColab.EnviarCvProyecto'), {
        onSuccess: () => {
            form.reset(),
            cargando.value = false;
        },
        onError: () => {
            const firstErrorFieldId = Object.keys(form.errors)[0];
            document.getElementById(firstErrorFieldId).focus();
            cargando.value = false;
        }
    });
};

// Fecha máxima para el campo de fecha
const maxDate = ref('');

// Función para establecer la fecha máxima
const setMaxDate = () => {
    const today = new Date();
    const year = today.getFullYear();
    const month = (today.getMonth() + 1).toString().padStart(2, '0');
    const day = today.getDate().toString().padStart(2, '0');
    maxDate.value = `${year}-${month}-${day}`;
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
            Insertar datos de Proyecto
        </template>
        <div v-if="cargando" role="status">
            <svg aria-hidden="true" class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
            </svg>
            <p class="" >Enviando...</p>
            <span class="sr-only">Loading...</span>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg  ">
            <div class="p-6 mx-2 border-b border-gray-200 overflow-y-auto">
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="TituloProyecto" value="Titulo" />
                            <TextInput
                            id="TituloProyecto"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.TituloProyecto"
                            placeholder="Ingrese el Titulo del proyecto"
                            autofocus
                            disabled
                            autocomplete="TituloProyecto"
                            />
                            <InputError class="mt-2" :message="form.errors.TituloProyecto" />
                        </div>

                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="Mensaje" value="Mensaje del cv" />
                            <TextArea
                                id="Mensaje"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.Mensaje"
                                placeholder="Ingrese un mensaje para el cv"
                                autocomplete="Mensaje"
                            />
                            <InputError class="mt-2" :message="form.errors.Mensaje" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="imagenes" value="Imágenes" />
                            <input
                                id="imagenes"
                                type="file"
                                accept="application/pdf"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                @change="handleFileUpload"
                                
                            />
                            <InputError class="mt-2" :message="form.errors.imagenes" />
                        </div>
                    </div>

                    

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="flex items-center justify-left mt-4">
                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" />
                                </svg>
                                Enviar
                            </PrimaryButton>
                            <LinkRegresar class="mx-2" :href="route('ProyectosColab.PanelProyectos')">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />
                                </svg>

                                Regresar
                            </LinkRegresar> 
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
