<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/textarea.vue';
import LinkRegresar from '@/Components/linkRegresar.vue';
import BotonEliminar from '@/Components/BotonEliminar.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

import Swal from 'sweetalert2';
const props = defineProps({
    oferta:{type:Object},
    carreras:{type:Object}
});
const valoresIniciales = {
    TituloOferta: props.oferta.TituloOferta,
    Descripcion: props.oferta.Descripcion,
    Requisitos: props.oferta.Requisitos,
    Empresa: props.oferta.Empresa,
    Ubicacion: props.oferta.Ubicacion,
    imagenes: '',
    Imagen: props.oferta.Imagen,
    carreras: [],
    SectorEmpre: props.oferta.SectorEmpre,
};

onMounted(() => {
    form.carreras = props.oferta.ofertas_carreras.map(carrera => carrera.idcarrera);
});

const imagePreviews = ref([]);
const form = useForm(valoresIniciales);

const submit = () => {
    Swal.fire({
        title: 'Cargando',
        text: 'Por favor espera mientras se envían los datos...',
        allowOutsideClick: false, // Deshabilita que el usuario cierre la alerta
        didOpen: () => {
            Swal.showLoading(); // Muestra el spinner
        }
    });
    form.post(route('ofertasTrabajo.update',props.oferta.idOfertaTrabajo), {
        onSuccess: () => {
            form.reset();
            Swal.close();
        },
        onError: () => {
            Swal.close();
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


const Eliminar = (a) => {
    event.preventDefault(); 
    Swal.fire({
        title: `¿Deseas eliminar esta imagen? `,
        text: `No podrás revertir este proceso`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, Eliminar"
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('ofertasTrabajo.destroyImageOferta', a), {
                onSuccess: () => {
                    console.log('Imagen eliminada');
                    Swal.fire(
                        'Eliminado!',
                        'El registro ha sido eliminado.',
                        'success'
                    );
                    form.Imagen = null;
                    
                },
                onError: (error) => {
                    console.error('Error al eliminar:', error);
                }
            });
        }
    });
};

const SectorEmpresaria=[
    { value: "", label: "Selecciona un sector " },
    { value: "Primario",label: "Primario" },
    { value: "Terciario",label: "Terciario" },
    { value: "Secundario",label: "Secundario" },
    { value: "Educativo",label: "Educativo" },
    { value: "Publico",label: "Publico" },
    { value: "Ganadero",label: "Ganadero" },
    { value: "Empresarial",label: "Empresarial" },
    
];
</script>

<template>
    <Head title="Actualizar oferta" />
    <AuthenticatedLayout>
        <template #header>
            Actualizar datos de la oferta
        </template>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
            <div class="p-6 mx-2 border-b border-gray-200 overflow-y-auto">
                <form @submit.prevent="submit">
                    <!-- Fila 1 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="TituloOferta" value="Título de la oferta" />
                            <TextInput
                                id="TituloOferta"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.TituloOferta"
                                required
                                placeholder="Ingrese el Titulo de la oferta"
                                autocomplete="TituloOferta"
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.TituloOferta" />
                        </div>
                    </div>

                    <!-- Fila 2 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="Descripcion" value="Descripción de la oferta" />
                            <TextArea
                                id="Descripcion"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.Descripcion"
                                required
                                placeholder="Ingrese la descripción de la carrera"
                                autocomplete="Descripcion"
                            />
                            <InputError class="mt-2" :message="form.errors.Descripcion" />
                        </div>
                    </div>

                    <!-- Fila 3 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="Requisitos" value="Requisitos" />
                            <TextArea
                                id="Requisitos"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.Requisitos"
                                required
                                placeholder="Lista de requsitos"  
                            />
                            <InputError class="mt-2" :message="form.errors.Requisitos" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="carreras" value="carreras" />
                            <v-select
                                v-model="form.carreras"
                                id="carreras"
                                class="mt-1 w-full "
                                :style="{ '--vs-font-size': '1.3rem' }"
                                :options="props.carreras"
                                label="NombreCarrera"
                                :reduce="carrera => carrera.idCarrera"
                                :required="!form.carreras"
                                
                                multiple
                                />
                            <InputError class="mt-2" :message="form.errors.carreras" />
                        </div>
                        <div class="col-span-6 mt-4 relative">
                            <InputLabel for="SectorEmpre" value="Sector empresarial" />
                            <v-select
                                v-model="form.SectorEmpre"
                                id="SectorEmpre"
                                class="mt-1 w-full "
                                :style="{ '--vs-font-size': '1.3rem' }"
                                :options="SectorEmpresaria"
                                :required="!form.SectorEmpre"
                                :reduce="SectorEmpresaria => SectorEmpresaria.value"
                                />
                            <InputError class="mt-2" :message="form.errors.SectorEmpre" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="Empresa" value="Empresa" />
                            <TextInput
                                id="Empresa"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.Empresa"
                                required
                                placeholder="Ingrese el nombre de la empresa"
                                autocomplete="Empresa"
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.Empresa" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="Ubicacion" value="Ubicación" />
                            <TextInput
                                id="Ubicacion"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.Ubicacion"
                                required
                                placeholder="Ingrese la Ubicación"
                                autocomplete="Ubicacion"
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.Ubicacion" />
                        </div>
                    </div>
                    <!-- Sección para mostrar previsualización de imágenes -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 mt-4" v-if="form.Imagen">
                        <div class="col-span-12">
                            <div class="grid grid-cols-2 gap-2">
                                <img  :src="`/storage/${form.Imagen}`" alt="Imagen" class="w-25 h-25  object-cover" />
                            </div>
                            <BotonEliminar @click="Eliminar(props.oferta.idOfertaTrabajo)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                Eliminar imagen
                            </BotonEliminar>
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
                            Actualizar
                        </PrimaryButton>
                        <LinkRegresar class="mx-2" :href="route('ofertasTrabajo.index')">
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
 