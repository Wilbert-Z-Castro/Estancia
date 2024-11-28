

<template>
    <Head title="Mis ponencias" />

    <AuthenticatedLayout>
        <template #header>
            <div class="justify-center" style="text-align: center;">
                Ponencias
            </div>
        </template>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div v-for="a in ponencias":key="a.Id_Ponencia"  class="flex justify-center mt-6"> 
                    <div class="flex flex-col items-center w-full bg-white border border-gray-200 rounded-lg shadow md:flex-row  dark:border-gray-700 dark:bg-gray-800">
                        <PrimaryButton v-if="a.ponencia.imagen!=null" class="flex flex-col items-center w-full md:w-48">
                           
                            <PrimaryButton  @click="openModalViwe(a)" class="bg-green-600 hover:bg-green-700">
                                <div v-if="a.ponencia.imagen!=null" >
                                    <img :src="`/storage/${a.ponencia.imagen}`" alt="Imagen" class="object-cover  rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" />
                                </div>
                                ver mas
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>

                            </PrimaryButton>
                        </PrimaryButton>
                        
                        <div class="flex flex-col justify-between p-4 leading-normal w-full ">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    {{ a.ponencia.TituloPonencia }}
                                    <span class="bg-purple-100 text-purple-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300">{{a.Estado}}</span>

                                </h5>
                                <h5 class="text-xl font-medium text-gray-900 dark:text-white sm:ml-4 sm:mt-0 mt-2">
                                    Invitación enviada el: {{ formatDate(a.ponencia.created_at) }}
    
                                </h5>
                            </div>
                            <p v-if="a.ponencia.dir_carrera!=null" class="mb-3 text-xl font-medium text-gray-500 dark:text-gray-300 whitespace-pre-wrap break-words">Invitación enviada por el director:  {{ a.ponencia.dir_carrera.user.name }}</p>
                            <p v-else class="mb-3 text-xl font-medium text-gray-500 dark:text-gray-300 whitespace-pre-wrap break-words">
                                Director de carrera no asignado
                            </p>
                            <p class="mb-3 font-normal text-gray-500 dark:text-gray-300 whitespace-pre-wrap break-words">Lugar:  {{ a.ponencia.Lugar }}</p>
                            <p class="mb-3 font-normal text-gray-500 dark:text-gray-300 whitespace-pre-wrap break-words">Fecha y Hora:  {{ (a.ponencia.Fecha) }}</p>
                            <p class="mb-3 font-normal text-gray-500 dark:text-gray-300 whitespace-pre-wrap break-words">Asunto de la ponenecia:</p>
                            <pre class="mb-3 font-normal text-gray-500 dark:text-gray-300 whitespace-pre-wrap break-words">{{ a.ponencia.DescripcionPonencia }}
                            </pre>
                            <div class="flex flex-col sm:flex-row  justify-left items-center sm:items-center mb-2" >
                                <div class="">
                                    <button v-show="a.Estado=='Pendiente'" @click="aceptar(a)" type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Aceptar</button>
                                    <button v-show="a.Estado=='Pendiente'"   @click="OpenModalRechazar(a)" type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Rechazar</button>
                                    <a v-show="a.Estado=='Terminado'" target="_blank"  :href="route('Ponencias.Reconocimiento',{id:a.idAceptacionPonencia})"  class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">Imprimir Reconocimiento</a>
                                </div>
                            </div>
                        </div>                      
                    </div>
                </div>
            </div>
        </div>
        <Modal :show="showModalView" @close="closeModalViwe">
            <div class="p-6">
                <p > Título: <span  class="text-lg font-medium text-gray-900">{{ v.Titulo }}</span></p>
                <p > Categoría: <span  class="text-lg font-medium text-gray-900">{{ v.Categoria }}</span></p>
                <p > Contenido: <span  class="text-lg font-medium text-gray-900">{{ v.Contenido }}</span></p>
                <p > Imagen: 
                    <div v-for="imagen in  v.Imagen" :key="imagen.idImagen" class="flex justify-center items-center">
                        <img  :src="`/storage/${imagen.URL}`" alt="Imagen" class="w-full h-auto object-contain" />
                    </div>
                </p>
            </div>
            <div class="m-6 flex justify-end">
                <PrimaryButton @click="closeModalViwe">
                    Cerrar
                </PrimaryButton>
            </div>

        </Modal>
        <Modal :show="ModalRechazar" @close="CloseModalRechazar">
            <form @submit.prevent="rechazar()">
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                    <div class="col-span-12 mt-4 p-6">
                        <InputLabel for="Mensaje" value="Mensaje de rechazo" />
                            <TextArea
                            id="Mensaje"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            v-model="form.Mensaje"
                            placeholder="¿Desea ingresar un mensaje de rechazo?"
                            autocomplete="Mensaje"
                            />
                            <InputError class="mt-2" :message="form.errors.Mensaje" />
                    </div>
                </div>
                    
                <div class="m-6 flex justify-end">
                    <button  class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Rechazar</button>
                    <button  @click="CloseModalRechazar" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Cerrar</button>
                    
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
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref,onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    ponencias:{
        type: Object,
        default: () => ({ data: [], links: [] })
    },

});

const valoresIniciales = {
    idAceptacionPonencia:'',
    Mensaje:'',
};

const form = useForm(valoresIniciales);

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return `${date.getDate().toString().padStart(2, '0')}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getFullYear()}`;
}


const v =ref({id:'',Titulo:'',Categoria:'',Contenido:'',Imagen:''});
const mensaje =ref({id:'',Mensaje:''});
const showModalView = ref(false);
const ModalRechazar = ref(false);

const openModalViwe = (a) => {
    v.value.id = a.idAnuncio;
    v.value.Titulo = a.Titulo;
    v.value.Contenido = a.Contenido;
    v.value.Categoria = a.categoria.Nombre;
    v.value.Imagen = a.imagenes
    showModalView.value = true;

}

const closeModalViwe = () => {
   
    showModalView.value = false;

}

const OpenModalRechazar = (a) => {
    form.idAceptacionPonencia = a.idAceptacionPonencia;
    ModalRechazar.value = true;

}

const CloseModalRechazar = () => {
   
    ModalRechazar.value = false;

}


const aceptar = (a) =>{
    form.get(route('Ponencias.AceptarPonencia',a.idAceptacionPonencia),{
        onSuccess: () => {
            fetchData();
        },
        onError: () => {
            cargando.value = false;
            const firstErrorFieldId = Object.keys(form.errors)[0];
            document.getElementById(firstErrorFieldId).focus();
        }
    });
}

const rechazar = () => {
    CloseModalRechazar();
    form.post(route('Ponencias.RechazarPonencia'), {
        onSuccess: () => {
            // Vuelve a cargar todos los datos usando fetchData
            fetchData();
            CloseModalRechazar();
        },
        onError: () => {
            console.error("Error al rechazar la ponencia");
        }
    });
};


const load = ref(false);
const showingTwoLevelMenu = ref(false);
</script>

