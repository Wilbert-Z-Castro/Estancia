<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BotonAgregar from '@/Components/BotonAgregar.vue';
import BotonEditar from '@/Components/BotonEditar.vue';
import BotonEliminar from '@/Components/BotonEliminar.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import linkAgregar from '@/Components/linkAgregar.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import { ref } from 'vue';
import Swal from 'sweetalert2'

const props = defineProps({
    carreras:{
        type: Object,
        default: () => ({ data: [], links: [] })
    }
});
const carreras = ref(props.carreras.data);
const links = ref(props.carreras.links);


const v =ref({id:'',NombreCarrera:'',Descripcion:'',PlanEstudios:'',UbicacionOficinas:'',nombreDir:'',descripciondir:'',fechaAsginacion:''});
const showModalView = ref(false);

const openModalViwe = (a) => {
    v.value.id = a.idCarrera;
    v.value.NombreCarrera = a.NombreCarrera;
    v.value.Descripcion = a.Descripcion;
    v.value.PlanEstudio = a.PlanEstudio;
    v.value.UbicacionOficinas = a.UbicacionOficinas;
    v.value.nombreDir=a.dir_carrera.user.name;
    v.value.descripciondir=a.dir_carrera.Descripcion;
    v.value.fechaAsginacion=a.dir_carrera.FechaAsignacion;
    v.value.PlanEstudios = a.PlanEstudios;
   

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
        title: `¿Deseas eliminar ${a.NombreCarrera}?`,
        text: `No podrás revertir este proceso ${a.idCarrera}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, Eliminar"
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('carreras.destroy', a.idCarrera), {
                onSuccess: () => {
                   
                    carreras.value = carreras.value.filter(item => item.idCarrera !== a.idCarrera);
                    
                    Swal.fire({
                        title: "Eliminado!",
                        text: "El registro fue eliminado exitosamente",
                        icon: "success"
                    });
                },
                onError: () => {
                    Swal.fire({
                        title: "Error!",
                        text: "Hubo un problema al eliminar el registro",
                        icon: "error"
                    });
                }
            });
        }
    });
};

</script>

<template>
    <Head title="Gestion Carreras" />

    <AuthenticatedLayout>
        <template #header>
            Carreras
        </template>
        <div v-if="$page.props.flash.message" class="inline-flex max-w-sm w-full bg-white shadow-md rounded-lg overflow-hidden ">
            <div class="flex justify-center items-center w-12 bg-green-500">
                    <svg class="h-6 w-6 fill-current text-white" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z"/>
                    </svg>
            </div>        
            <div class="-mx-3 py-2 px-4">
                <div class="mx-3">
                    <span class="text-green-500 font-semibold">Success</span>
                        <p class="text-gray-600 text-sm">{{ $page.props.flash.message }}</p>
                </div>
            </div>
        </div>
        <br>
        <linkAgregar :href="route('carreras.create')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-2 size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Agregar
        </linkAgregar> 
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="w-full overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b ">
                                <th class="px-4 py-3">#Numero</th>
                                <th class="px-4 py-3">Nombre</th>
                                <th class="px-4 py-3">Descripcion</th>
                                <th class="px-4 py-3">Plan de Estudio</th>
                                <th class="px-4 py-3">UbicacionOficinas</th>
                                <th class="px-4 py-3">Director</th>
                                <th class="px-4 py-3">Editar</th>
                                <th class="px-4 py-3">Ver</th>
                                <th class="px-4 py-3">Borrar</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y ">
                            <tr v-for="a,i in carreras":key="a.idCarrera" class="text-gray-700">
                                <td class="px-4 py-3 text-sm">
                                    {{(i+1)}}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{a.NombreCarrera}}
                                </td>
                                <td class="px-4 py-3 text-sm">{{a.Descripcion}}</td>
                                <td  class="px-4 py-3 text-sm">
                                        <img v-if="a.PlanEstudios" :src="`/storage/${a.PlanEstudios}`" alt="Imagen" class="w-25 h-25  object-cover" />
                                </td>
                                <td class="px-4 py-3 text-sm">{{a.UbicacionOficinas}}</td>
                                <td class="px-4 py-3 text-sm">{{a.dir_carrera.user.name }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <BotonEditar :href="route('carreras.edit', { id: a.idCarrera })" >
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
                <p > Carrera: <span  class="text-lg font-medium text-gray-900">{{ v.NombreCarrera }}</span></p>
                <p > Descripcion: <span  class="text-lg font-medium text-gray-900">{{ v.Descripcion }}</span></p>
                <p > Plan de Estudio: <span  class="text-lg font-medium text-gray-900">{{ v.PlanEstudio }}</span></p>
                <p > Ubicacion de Oficinas: <span  class="text-lg font-medium text-gray-900">{{ v.UbicacionOficinas }}</span></p>
                <p > Director: <span  class="text-lg font-medium text-gray-900">{{ v.nombreDir }}</span></p>
                <p > Info del Director: <span  class="text-lg font-medium text-gray-900">{{ v.descripciondir }}</span></p>
                <p > Fecha de asginacion del director: <span  class="text-lg font-medium text-gray-900">{{ v.fechaAsginacion }}</span></p>
                <img :src="`/storage/${v.PlanEstudios}`" alt="Imagen" class="w-25 h-25 object-cover" />

            </div>
            <div class="m-6 flex justify-end">
                <PrimaryButton @click="closeModalViwe">
                    Cerrar
                </PrimaryButton>
            </div>

        </Modal>

    </AuthenticatedLayout>
</template>