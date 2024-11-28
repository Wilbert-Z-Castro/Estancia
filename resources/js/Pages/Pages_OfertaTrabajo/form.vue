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
    carreras:{type:Object}
});
const valoresIniciales = {
    TituloOferta: '',
    Descripcion: '',
    Requisitos: '',
    Empresa: '',
    Ubicacion: '',
    imagenes: '',
    carreras: [],
    SectorEmpre: '',
};
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
    form.post(route('ofertasTrabajo.store'), {
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
    <Head title="Gestion categorias formulario" />
    <AuthenticatedLayout>
        <template #header>
            Insertar datos de la oferta
            
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
                                placeholder="Ingrese el Título de la oferta"
                                autocomplete="TituloOferta"
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.TituloOferta" />
                        </div>
                    </div>

                    <!-- Fila 2 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="Descripcion" value="Descripción" />
                            <TextArea
                                id="Descripcion"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.Descripcion"
                                required
                                placeholder="Ingrese la descripción de la oferta"
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
                                placeholder="Ingresar Lista de requsitos"  
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
                                placeholder="Nombre de la empresa"
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
                    <!-- Fila 4: Imágenes -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="imagenes" value="Seleciona la imagen" />
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
                            Registrar
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
