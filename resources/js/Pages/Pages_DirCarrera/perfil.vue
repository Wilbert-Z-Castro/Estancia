<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/textarea.vue';
import LinkRegresar from '@/Components/linkRegresar.vue';
import axios from 'axios';
import Swal from 'sweetalert2'
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({
    DirCarrera: { type: Object },
});

const valoresIniciales = {
    name: props.DirCarrera.user.name,
    email: props.DirCarrera.user.email,
    current_password: '', 
    password: '',
    password_confirmation: '',
    rol: props.DirCarrera.user.rol,
    username: props.DirCarrera.user.UserName,
    apellidoP: props.DirCarrera.user.ApellidoP,
    apellidoM: props.DirCarrera.user.ApellidoM,
    telefono: props.DirCarrera.user.Telefono,
    sexo: props.DirCarrera.user.Sexo,
    Descripcion: props.DirCarrera.Descripcion,
    AnioInstitucion: props.DirCarrera.AnioInstitucion,
    FechaAsignacion: props.DirCarrera.FechaAsignacion,
    MiPerfil: '1',
};

const form = useForm(valoresIniciales);

const submit = () => {
    // Mostrar alerta de carga
    Swal.fire({
        title: 'Cargando',
        text: 'Por favor espera mientras se envían los datos...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Usar axios para enviar los datos
    axios.post(route('dir_carreras.update', props.DirCarrera.idDirCarrera), form)
        .then(response => {
            // Si la solicitud es exitosa, reiniciar el formulario
            form.reset();
            Swal.close();  // Cerrar alerta de carga

            // Mostrar mensaje de éxito
            setTimeout(() => {
                Swal.fire({
                    title: "Bien!",
                    text: "Los datos se han actualizado correctamente",
                    icon: "success"
                }).then(() => {
                // Recargar la página
                window.location.reload();
            });
            }, 300);
            
        })
        .catch(error => {
            Swal.close();
            const firstErrorFieldId = Object.keys(error.response.data.errors)[0];
            document.getElementById(firstErrorFieldId).focus();

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
    <Head title="Mi perfil" />
    <AuthenticatedLayout>
        <template #header>
            Mi perfil
        </template>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
            <div class="p-6 mx-2 border-b border-gray-200">
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="name" value="Nombre" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                                autofocus
                                placeholder="Ingresa tu nombre"
                                autocomplete="name"
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.name" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="username" value="Apodo o nombre de usuario" />
                            <TextInput
                                id="username"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="Ingresa apodo o nombre de usuario"

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
                                placeholder="Ingresa tu correo electrónico"
                                required
                                autocomplete="email"
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.email" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <!-- Campo para la contraseña actual -->
                        <div class="col-span-6 mt-4">
                            <InputLabel for="current_password" value="Contraseña Actual" />
                            <TextInput
                                id="current_password"
                                type="password"
                                class="mt-1 block w-full"
                                placeholder="Ingresa tu contraseña actual"
                                v-model="form.current_password"
                                
                            />
                            <InputError class="mt-2" :message="form.errors.current_password" />
                        </div>
                        <!-- Campo para la nueva contraseña -->
                        <div class="col-span-6 mt-4">
                            <InputLabel for="password" value="Nueva Contraseña" />
                            <TextInput
                                id="password"
                                type="password"
                                placeholder="Ingresa tu nueva contraseña"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <!-- Campo para confirmar la nueva contraseña -->
                        <div class="col-span-6 mt-4">
                            <InputLabel for="password_confirmation" value="Confirmar Contraseña" />
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                                placeholder="Confirma tu nueva contraseña"
                                v-model="form.password_confirmation"
                                
                            />
                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>
                    </div>
                    <!-- Resto de los campos del formulario -->
                    <!-- ... -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="apellidoP" value="Apellido Paterno" />
                            <TextInput
                                id="apellidoP"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.apellidoP"
                                placeholder="Ingresa tu apellido paterno"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.apellidoP" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="apellidoM" value="Apellido Materno" />
                            <TextInput
                                id="apellidoM"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="Ingresa tu apellido materno"
                                v-model="form.apellidoM"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.apellidoM" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="telefono" value="Número de Teléfono" />
                            <TextInput
                                id="telefono"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.telefono"
                                placeholder="Ingresa tu número de teléfono"
                                required
                                min="1"
                                step="1"
                            />
                            <InputError class="mt-2" :message="form.errors.telefono" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="sexo" value="Selecione su sexo" />
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
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                v-model="form.Descripcion"
                                placeholder="Ingresa tu perfil profesional"
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
                                placeholder="Ingresa los años de servicio en la institución"
                                required
                                min="1"
                                :max="new Date().getFullYear()"
                            />
                            <InputError class="mt-2" :message="form.errors.AnioInstitucion" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="FechaAsignacion" value="Fecha de Asignación del Puesto" />
                            <TextInput
                            id="FechaAsignacion"
                            type="date"
                            class="mt-1 block w-full"
                            v-model="form.FechaAsignacion"
                            :max="maxDate"
                            placeholder="Ingresa la fecha de asignación"
                            required
                            />
                            <InputError class="mt-2" :message="form.errors.FechaAsignacion" />
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" />
                            </svg>

                            Actualizar
                        </PrimaryButton>
                        <LinkRegresar class="mx-2" :href="route('egresados.index')">
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
