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
import Swal from 'sweetalert2'




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
    Swal.fire({
        title: 'Cargando',
        text: 'Por favor espera mientras se envían los datos...',
        allowOutsideClick: false, // Deshabilita que el usuario cierre la alerta
        didOpen: () => {
            Swal.showLoading(); // Muestra el spinner
        }
    });

    form.post(route('ProyectosColab.EnviarCvProyecto'), {
        onSuccess: () => {
            form.reset(),
            cargando.value = false;
            Swal.close();

        },
        onError: () => {
            const firstErrorFieldId = Object.keys(form.errors)[0];
            document.getElementById(firstErrorFieldId).focus();
            cargando.value = false;
            Swal.close();

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
    <Head title="Envio de cv de postulación" />
    <AuthenticatedLayout>
        <template #header>
            Enviar cv de postulación
        </template>
        
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
                                placeholder="ingrese un mensaje junto con su cv"
                                autocomplete="Mensaje"
                            />
                            <InputError class="mt-2" :message="form.errors.Mensaje" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="imagenes" value="CV" />
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
