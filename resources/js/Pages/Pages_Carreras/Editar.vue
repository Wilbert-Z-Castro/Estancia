<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/textarea.vue';
import LinkRegresar from '@/Components/linkRegresar.vue';
import { Head, useForm } from '@inertiajs/vue3';
import BotonEliminar from '@/Components/BotonEliminar.vue';
import Swal from 'sweetalert2'

const props = defineProps({
    DirCarrera: Array,
    carrera:{type:Object},
});

const valoresIniciales = {
    NombreCarrera: props.carrera.NombreCarrera,
    Descripcion: props.carrera.Descripcion,
    dirCarrera_id: props.carrera.id_DirCarrera,
    UbicacionOficinas: props.carrera.UbicacionOficinas,
    PlanEstudios: props.carrera.PlanEstudios,
    imagenes: [], // Asegúrate de que esto sea un array vacío para manejar múltiples imágenes
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

const Eliminar = (a) => {
    event.preventDefault(); 
    Swal.fire({
        title: `¿Deseas eliminar esta imagen? ${a}`,
        text: `No podrás revertir este proceso`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar"

    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('carreras.destroyImage', a), {
                onSuccess: () => {
                    console.log('Imagen eliminada');
                    Swal.fire(
                        'Eliminado!',
                        'El registro ha sido eliminado.',
                        'success'
                    );
                    form.PlanEstudios = null;
                },
                onError: (error) => {
                    console.error('Error al eliminar:', error);
                }
            });
        }
    });
};
const imageError = (event) => {
    event.target.src = '/img/image0_0.jpg'; // Ruta de la imagen por defecto
};

const submit = () => {
    Swal.fire({
        title: 'Cargando',
        text: 'Por favor espera mientras se envían los datos...',
        allowOutsideClick: false, // Deshabilita que el usuario cierre la alerta
        didOpen: () => {
            Swal.showLoading(); // Muestra el spinner
        }
    });

    let formData = new FormData();
    formData.append('NombreCarrera', form.NombreCarrera);
    formData.append('Descripcion', form.Descripcion);
    formData.append('dirCarrera_id', form.dirCarrera_id);
    formData.append('UbicacionOficinas', form.UbicacionOficinas);
    formData.append('imagenes[]', form.imagenes[0]); 
    form.post(route('carreras.update',props.carrera.idCarrera), {
        onSuccess: () =>{
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


</script>


<template>
    <Head title="Gestion categorias formulario" />
    <AuthenticatedLayout>
        <template #header>
            Editar datos del Carrera
        </template>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 mx-2 border-b border-gray-200">
                <form @submit.prevent="submit">
                    <!-- Fila 1 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="NombreCarrera" value="Abreviatura de la carrera" />
                            <TextInput
                                id="NombreCarrera"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.NombreCarrera"
                                required
                                placeholder="Ingresa la abreviatura de la carrera"
                                autocomplete="NombreCarrera"
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.NombreCarrera" />
                        </div>
                    </div>

                    <!-- Fila 2 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="Descripcion" value="Nombre completo de la carrera" />
                            <TextArea
                                id="Descripcion"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.Descripcion"
                                required
                                placeholder="Ingresa el Nombre completo de la carrera"
                                autocomplete="Descripcion"
                            />
                            <InputError class="mt-2" :message="form.errors.Descripcion" />
                        </div>
                    </div>

                    <!-- Fila 3 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="UbicacionOficinas" value="Ubicación de las oficinas" />
                            <TextInput
                                id="UbicacionOficinas"
                                type="text"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.UbicacionOficinas"
                                required
                                placeholder="Ingresa la ubicación de las oficinas"
                                autocomplete="UbicacionOficinas"
                            />
                            <InputError class="mt-2" :message="form.errors.UbicacionOficinas" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="UbicacionOficinas" value="Ubicación de las oficinas" />
                            <select
                                id="dirCarrera_id"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.dirCarrera_id"
                                required
                                autocomplete="id_DirCarrera"
                                
                            >
                            
                            
                            <option v-for="director in DirCarrera":key="director.dirCarrera_id":value="director.dirCarrera_id"
                            >
                                {{ director.user_name }}
                            </option>
                            </select>
                            
                        </div>
                    </div>

                    <!-- Fila 4: Imágenes -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="imagenes" value="Ingresa el plan de estudio" />
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
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 mt-4" v-if="form.PlanEstudios">
                        <div class="col-span-12">
                            <div class="grid grid-cols-2 gap-2">
                                <img @error="imageError" :src="`/storage/${form.PlanEstudios}`" alt="Imagen" class="w-25 h-25  object-cover" />
                            </div>
                            <BotonEliminar @click="Eliminar(props.carrera.idCarrera)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                Eliminar imagen
                            </BotonEliminar>
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
                        <LinkRegresar class="mx-2" :href="route('carreras.indexGestion')">
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
