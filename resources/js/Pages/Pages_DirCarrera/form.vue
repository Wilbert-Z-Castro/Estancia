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

const valoresIniciales = {
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    rol: '',
    username: '',
    apellidoP: '',
    apellidoM: '',
    telefono: '',
    sexo: '',
    Descripcion: '',
    AnioInstitucion: '',
    FechaAsignacion: '',

};

const props = defineProps({
    carreras:{type:Object}
});

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
    form.post(route('dir_carrera.store'), {
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
            Insertar datos del Directo de carrera
        </template>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
            <div class="p-6 mx-2 border-b border-gray-200">
                <form @submit.prevent="submit">
                    <!-- Fila 3 -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="name" value="Nombre" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="Ingrese el nombre del director de carrera"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.name" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="username" value="Apodo o nombre de usuario" />
                            <TextInput
                                id="username"
                                type="text"
                                placeholder="Ingrese el nombre de usuario"
                                class="mt-1 block w-full"
                                v-model="form.username"
                                required
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.username" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="email" value="Correo Electrónico" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                required
                                placejholder="Ingrese el correo electrónico"
                                autocomplete="email"
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.email" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="password" value="Contraseña" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                required
                                placeholder="Ingrese la contraseña"
                                
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="password_confirmation" value="Confirmar Contraseña" />
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                                placeholder="Confirme la contraseña"	
                                v-model="form.password_confirmation"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>

                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                    
                        <div class="col-span-6 mt-4">
                            <InputLabel for="apellidoP" value="Apellido Paterno" />
                            <TextInput
                                id="apellidoP"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="Ingrese el apellido paterno"
                                v-model="form.apellidoP"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.apellidoP" />
                        </div>
                    
                        <!-- Columna para 'Apellido Materno' -->
                        <div class="col-span-6 mt-4">
                            <InputLabel for="apellidoM" value="Apellido Materno" />
                            <TextInput
                                id="apellidoM"
                                type="text"
                                placeholder="Ingrese el apellido materno"
                                class="mt-1 block w-full"
                                v-model="form.apellidoM"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.apellidoM" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <!-- Columna para 'Teléfono' -->
                        <div class="col-span-6 mt-4">
                            <InputLabel for="telefono" value="Número de teléfono" />
                            <TextInput
                                id="telefono"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.telefono"
                                required
                                min="1"
                                step="1"
                                placeholder="Ingrese el número de teléfono"
                            />
                            <InputError class="mt-2" :message="form.errors.telefono" />
                        </div>
                    
                        <!-- Columna para 'Sexo' -->
                        <div class="col-span-6 mt-4">
                            <InputLabel for="sexo" value="Sexo" />
                            <select
                                id="sexo"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                v-model="form.sexo"
                                required
                            >
                                <option value="Hombre">Hombre</option>
                                <option value="Mujer">Mujer</option>
                                <option value="NP">No especificado</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.sexo" />

                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="Descripcion" value="Perfil profesional del director" />
                            <TextArea
                                id="Descripcion"
                                class="mt-1 block w-full"
                                v-model="form.Descripcion"
                                required
                                placeholder="Ingresa el perfil profesional del director"

                            />
                            <InputError class="mt-2" :message="form.errors.Descripcion" />
                        </div>
                    </div>   
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="AnioInstitucion" value="Años de servicio en la institución" />
                            <TextInput
                                id="AnioInstitucion"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.AnioInstitucion"
                                required
                                min="1"
                                step="1"
                                placeholder="Ingrese los años de servicio en la institución"
                            />
                            <InputError class="mt-2" :message="form.errors.AnioInstitucion" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="FechaAsignacion" value="Fecha de Asignación" />
                            <TextInput
                            id="FechaAsignacion"
                            type="date"
                            class="mt-1 block w-full"
                            v-model="form.FechaAsignacion"
                            :max="maxDate"
                            required
                            />
                            <InputError class="mt-2" :message="form.errors.FechaAsignacion" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="flex items-center justify-left mt-4">
                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" />
                                </svg>

                                Registrar
                            </PrimaryButton>
                            <LinkRegresar class="mx-2" :href="route('dir_carreras.index')">
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
