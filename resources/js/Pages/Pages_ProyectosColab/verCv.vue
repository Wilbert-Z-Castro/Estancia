

<template>
    <Head title="Cv's enviados" />
    <AuthenticatedLayout>
        <template #header>
            <div class="justify-center" style="text-align: center;">
                Cv's recibidos
            </div>
        </template>
        <div v-if="pageProps.flash.message" class="inline-flex max-w-sm w-full bg-white shadow-md rounded-lg overflow-hidden ">
            <div class="flex justify-center items-center w-12 bg-green-500">
                    <svg class="h-6 w-6 fill-current text-white" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z"/>
                    </svg>
            </div>        
            <div class="-mx-3 py-2 px-4">
                <div class="mx-3">
                    <span class="text-green-500 font-semibold">Success</span>
                        <p class="text-gray-600 text-sm">{{ pageProps.flash.message }}</p>
                </div>
            </div>
        </div>
        <br>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg w-full">
            <div class="p-6 border-b border-gray-200">
                <h1 class="mb-2 text-2xl font-bold tracking-tight text-gray-800">Mis Proyectos</h1>

                <div>
                    <div class="flex justify-center mt-6 w-full">
                        <div class="flex flex-col items-center w-full bg-white border border-gray-200 rounded-lg shadow md:flex-row dark:border-gray-700 dark:bg-gray-800 flex-grow">
                            <div v-for="a in solicitudes" :key="a.idProyectoColab" class="w-full">
                                <div class="flex flex-col justify-between p-4 leading-normal w-full">
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
                                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                            Proyecto: {{ a.TituloProyecto }}
                                        </h5>
                                    </div>

                                    <p class="mb-3 font-normal text-gray-300 whitespace-pre-wrap break-words">
                                        Cv's enviados por alumnos:
                                    </p>
                                    <div v-for="i in a.solicitudes" class="w-full">
                                        <div class="flex flex-col sm:flex-row gap-4 p-4 justify-left items-center w-full sm:items-center mb-2">
                                            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow w-full">
                                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
                                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Nombre del Alumno:  {{ i.name }} {{ i.ApellidoP }} {{ i.ApellidoM }}</h5>
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded  border border-yellow-400">{{i.pivot.Estado  }}</span>
                                                </div>
                                                <p class="mb-3 font-normal text-gray-700"></p>
                                                <p class="mb-3 font-normal text-gray-700">Archivo enviado: {{ NombreArchivo(i.pivot.Cv) }}</p>
                                                <p class="mb-3 font-normal text-gray-700">Mensaje:</p>
                                                <pre class="mb-3 font-normal text-gray-700 whitespace-pre-wrap break-words">{{ i.pivot.Mensaje }}</pre>
                                                <a :href="route('ProyectosColab.show',{id:i.pivot.idPostulacionProyecto})" target="_blank" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                                    Ver curriculum
                                                    <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                                    </svg>
                                                </a>
                                                <div class="">
                                                    <br>
                                                    <hr/>
                                                    <br>
                                                    <div class="" v-show="i.pivot.Estado=='Enviado'">
                                                        <button  @click="openModalViwe(i.pivot)" type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                            Aceptar
                                                        </button>
                                                        <button @click="OpenModalRechazar(i.pivot)" type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                                            Rechazar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>

        <Modal :show="showModalView" @close="closeModalViwe">
                <form @submit.prevent="aceptar()">
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                    <div class="col-span-12 mt-4 p-6">
                        <InputLabel for="Mensaje" value="Mensaje informativo para el alumno" />
                            <TextArea
                            id="Mensaje"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            rows="10"
                            v-model="form.MensajeEgresado"
                            placeholder="Ingrese un mensaje para el alumno"
                            autocomplete="Mensaje"
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.MensajeEgresado" />

                    </div>
                </div>
                    
                <div class="m-6 flex justify-end">
                    <button class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-500 to-pink-500 group-hover:from-purple-500 group-hover:to-pink-500 hover:text-white  focus:ring-4 focus:outline-none focus:ring-purple-200 ">
                        <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white  rounded-md group-hover:bg-opacity-0">
                        Enviar mensaje
                        </span>
                    </button>                    
                    <button  @click="closeModalViwe" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Cerrar</button>
                                        
                </div>
            </form>
        </Modal>
        
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import linkAgregar from '@/Components/linkAgregar.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/textarea.vue';
import { usePage } from '@inertiajs/vue3';
import { Head, useForm} from '@inertiajs/vue3';
import { ref,onMounted } from 'vue';
import Swal from 'sweetalert2'

const cargando = ref(false);


const props = defineProps({
    solicitudes:{type: Object}
});
const {props:pageProps} = usePage();    

onMounted(() => {
    if (pageProps.flash.message) {
        setTimeout(() => {
            pageProps.flash.message = null;
        }, 4000);
    }
});

const aceptar = () =>{
    closeModalViwe();
    Swal.fire({
        title: 'Cargando',
        text: 'Por favor espera mientras se envían los datos...',
        allowOutsideClick: false, // Deshabilita que el usuario cierre la alerta
        didOpen: () => {
            Swal.showLoading(); // Muestra el spinner
        }
    });

    form.post(route('ProyectosColab.ReponderSolicitud'),{
        onSuccess: () => {
            form.reset();
            Swal.close();
            setTimeout(() => {
                Swal.fire({
                    title: "Mensaje enviado!",
                    text: "El mensaje fue enviado exitosamente",
                    icon: "success"
                });
            }, 300);
        },
        onError: () => {
            Swal.close();
            cargando.value = false;
            const firstErrorFieldId = Object.keys(form.errors)[0];
            document.getElementById(firstErrorFieldId).focus();
        }
    });
}

function NombreArchivo(archivo) {
    const partes = archivo.split('_');
    
    // Devuelve el nombre original (segundo elemento)
    return partes.length > 1 ? partes[1] : archivo;
}

const valoresIniciales = {
    MensajeEgresado: '',
    idPostulacionProyecto: '',
    Estado: '',

};
const form = useForm(valoresIniciales);
const showModalView = ref(false);

const openModalViwe = (a) => {

    form.idPostulacionProyecto = a.idPostulacionProyecto;
    form.Estado = 'Aceptado';
    form.MensajeEgresado = '';
    showModalView.value = true;

}

const closeModalViwe = () => {
   
    showModalView.value = false;

}

const OpenModalRechazar = (a) => {
    form.MensajeEgresado = '';

    form.idPostulacionProyecto = a.idPostulacionProyecto;
    form.Estado = 'Rechazado';
    showModalView.value = true;

}



</script>

