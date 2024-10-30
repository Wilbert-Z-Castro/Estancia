<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BotonAgregar from '@/Components/BotonAgregar.vue';
import BotonEditar from '@/Components/BotonEditar.vue';
import BotonEliminar from '@/Components/BotonEliminar.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import linkAgregar from '@/Components/linkAgregar.vue';
import { Head, useForm,usePage } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';

import Pagination from '@/Components/Pagination.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import axios from 'axios';

import Swal from 'sweetalert2'

const props = defineProps({
    categorias:{type:Object,
        default: () => ({ data: [], links: [] })
    }
});

const {props:pageProps} = usePage();    

const categorias = ref(props.categorias.data);
const links = ref(props.categorias.links);


const v =ref({id:'',Nombre:'',Descripcion:'',Color:''});
const showModalView = ref(false);

const openModalViwe = (a) => {
    v.value.id = a.idCatAnuncio;
    v.value.Nombre = a.Nombre;
    v.value.Descripcion = a.Descripcion;
    v.value.Color = a.Color;
    showModalView.value = true;

}

const closeModalViwe = () => {
   
    showModalView.value = false;

}

const valoresIniviales = {
    id:0,
};
const form = useForm(valoresIniviales);

const submit = (a) => {
    Swal.fire({
        title: "¿Deseas eliminar  "+a.Nombre+"?",
        text: "No podras revertir este proceso "+a.idCatAnuncio,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar"
        }).then((result) => {
            
        if (result.isConfirmed) {
            
            v.id = a.idCatAnuncio;
            form.delete(route('cat_anuncios.destroy', v.id), {

                onSuccess: () => {
                    categorias.value = categorias.value.filter((item) => item.idCatAnuncio !== v.id);
                    Swal.fire({
                        title: "Eliminado!",
                        text: "El regsitro fue eliminado exitosamente ",
                        icon: "success"
                        });
                },
                onError: () => {
                    Swal.fire({
                        title: "Error!",
                        text: "Ocurrio un error al eliminar el registro",
                        icon: "error"
                        });
                }

            });

        }
        });
    
    
};

onMounted(() => {
    if (pageProps.flash.message) {
        setTimeout(() => {
            pageProps.flash.message = null;
        }, 4000);
    }
});
const valoresBusqueda = {
    search: '',
};
const formBuscar = useForm(valoresBusqueda);

const Buscar = () => {
    formBuscar.get(route('cat_anuncios.index'));
};




</script>


<template>
    <Head title="Gestion categorias" />

    <AuthenticatedLayout>
        <template #header>
            Categoria de anuncios
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
        <form @submit.prevent="Buscar">
        <div class="flex flex-wrap items-center">             
            <!-- Campo de búsqueda para carreras -->
            <linkAgregar :href="route('cat_anuncios.create')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Agregar
            </linkAgregar>

                <TextInput
                    type="text" 
                    class="flex-grow w-full sm:w-auto mx-2" 
                    placeholder="Buscar Carrera" 
                    v-model="formBuscar.search" 
                />
                <br>
                <br>
                <br>
                <PrimaryButton>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
    
                    Buscar
                </PrimaryButton>
            </div>
        </form>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="w-full overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b ">
                                <th class="px-4 py-3">#Numero</th>
                                <th class="px-4 py-3">Nombre</th>
                                <th class="px-4 py-3">Descripción</th>
                                <th class="px-4 py-3">Color</th>
                                <th class="px-4 py-3">Editar</th>
                                <th class="px-4 py-3">Ver</th>
                                <th class="px-4 py-3">Borrar</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y ">
                            <tr v-for="a,i in categorias":key="a.idCatAnuncio" class="text-gray-700">
                                <td class="px-4 py-3 text-sm">
                                    {{(i+1)}}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{a.Nombre}}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{a.Descripcion}}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{a.Color}}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <BotonEditar :href="route('cat_anuncios.edit', { id: a.idCatAnuncio })" >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>

                                        Editar
                                    </BotonEditar>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <form @submit.prevent="submit(a)" >
                                        <BotonEliminar >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>

                                        Eliminar
                                        </BotonEliminar>
                                    </form>
                                    
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <PrimaryButton @click="openModalViwe(a)">
                                        Ver
                                    </PrimaryButton>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <Pagination :links="links" />
                </div>
            </div>
        </div>
        <Modal :show="showModalView" @close="closeModalViwe">
            <div class="p-6">
                <p > Nombre: <span  class="text-lg font-medium text-gray-900">{{ v.Nombre }}</span></p>
                <p > Descripción: <span  class="text-lg font-medium text-gray-900">{{ v.Descripcion }}</span></p>
                <p > Color: <span  class="text-lg font-medium text-gray-900">{{ v.Color }}</span></p>
            </div>
            <div class="m-6 flex justify-end">
                <PrimaryButton @click="closeModalViwe">
                    Cerrar
                </PrimaryButton>
            </div>

        </Modal>
    </AuthenticatedLayout>
   
</template>