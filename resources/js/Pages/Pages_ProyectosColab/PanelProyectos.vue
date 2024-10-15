

<template>
    <Head title="Proyectos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="justify-center" style="text-align: center;">
                Lista de proyectos colaborativos
            </div>
            {{   }}
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
        <br>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                
                <div v-if="proyectos.length == 0">
                    <div class="w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                        <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Lo sentimos</h5>
                        <p class="mb-5 text-base text-gray-500 sm:text-lg dark:text-gray-400">Por el momento no hay proyectos disponibles</p>
                        <div class="items-center justify-center space-y-4 sm:flex sm:space-y-0 sm:space-x-4 rtl:space-x-reverse">
                            
                        </div>
                    </div>
                </div>

                <div v-for="a in proyectos":key="a.idProyectoColab" class="flex justify-center mt-6"> 
                    <div class="flex flex-col items-center w-full bg-white border border-gray-200 rounded-lg shadow md:flex-row  dark:border-gray-700 dark:bg-gray-800">
                        <PrimaryButton v-if="a.Imagen!=null" class="flex flex-col items-center w-full md:w-48">
                           <div v-if="a.Imagen!=null" >
                                   <img :src="`/storage/${a.Imagen}`" alt="Imagen" class="object-cover  rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" />
                               </div>
                           <PrimaryButton  @click="openModalViwe(a)" class="bg-green-600 hover:bg-green-700">
                               
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
                                    {{ a.TituloProyecto }}
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ a.Tipo }}</span>


                                </h5>
                                <h5 class="text-xl font-medium text-gray-900 dark:text-white sm:ml-4 sm:mt-0 mt-2">
                                    Fecha de Publicacion: {{ formatDate(a.created_at) }}
    
                                </h5>
                            </div>
                            <p class="mb-3 font-normal text-gray-500 dark:text-gray-300 whitespace-pre-wrap break-words">Fecha limite:  {{ a.FechaPublicacion }}</p>
                            <p class="mb-3 font-normal text-gray-500 dark:text-gray-300 whitespace-pre-wrap break-words">Descripcion del proyecto:</p>
                            <pre class="mb-3 font-normal text-gray-500 dark:text-gray-300 whitespace-pre-wrap break-words">
{{ a.Descripcion }}
                            </pre>
                            
                            <div class="flex flex-col sm:flex-row  justify-left items-center sm:items-center mb-2" >
                                    <div class="">
                                        <a :href="route('ProyectosColab.EnviarSolicitud',{id:a.idProyectoColab})" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Enviar CV</a>
                                    </div>
                            </div>                         
                        </div>
                    </div>
                    <Pagination :links="links" />
                </div>
            </div>
        </div>
        <Modal :show="showModalView" @close="closeModalViwe">
            <div class="p-6"> 
                    <div  class="flex justify-center items-center">
                        <img  :src="`/storage/${v.Imagen}`" alt="Imagen" class="w-full h-auto object-contain" />
                    </div>
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
import Pagination from '@/Components/Pagination.vue';
import TextArea from '@/Components/textarea.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref,onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    proyectos:{
        type: Object,
        default: () => ({ data: [], links: [] })
    },

});

const proyectos = ref(props.proyectos.data);
const links = ref(props.proyectos.links);

const valoresIniciales = {
    idAceptacionPonencia:'',
    Mensaje:'',
};

const {props:pageProps} = usePage();   

onMounted(() => {
    if (pageProps.flash.message) {
        setTimeout(() => {
            pageProps.flash.message = null;
        }, 4000);
    }
});
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
    v.value.Imagen = a.Imagen;
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

