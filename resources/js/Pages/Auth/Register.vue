<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/textarea.vue';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'

import { ref } from 'vue';

const form = useForm({
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
    matricula: '',
    anioEgreso: 0,
    carrera_id: '',
    direccion: '',
    empresaActual: '',
    puestoTrabajo: '',
    aniosLaboral: 0,
    sectorEmpresarial: '',
    adicional: '',
    esEgresado: '', // Inicialmente no es egresado
});

defineProps({
    carreras: Array,
});

const selectedYear = ref(new Date().getFullYear()); // Año actual como valor inicial
const years = ref(generateYearRange(1950, new Date().getFullYear())); // Genera un rango de años desde 1950 hasta el año actual

function generateYearRange(start, end) {
    let years = [];
    for (let year = end; year >= start; year--) {
        years.push(year);
    }
    return years;
}

const submit = () => {
    Swal.fire({
        title: 'Cargando',
        text: 'Por favor espera mientras se envían los datos...',
        allowOutsideClick: false, // Deshabilita que el usuario cierre la alerta
        didOpen: () => {
            Swal.showLoading(); // Muestra el spinner
        }
    });
    form.post(route('register'), {
        onFinish: () => {
            Swal.close();
            form.reset('password', 'password_confirmation');
        },
        onError: () => {
        // Enfocar el primer campo de entrada con error
            Swal.close();
            const firstErrorFieldId = Object.keys(form.errors)[0];
            document.getElementById(firstErrorFieldId).focus();
        }
    });
};

</script>
<template>
    <GuestLayout>
        <Head title="Registro" />

        <form @submit.prevent="submit">
            <div class="grid grid-cols-2 gap-12 sm:grid-cols-2">
                <!-- Columna para 'Nombre' -->
                <div class="col-span-2 mt-4">
                    <InputLabel for="esEgresado" value="¿Qué eres?" />
                    <select
                        id="esEgresado"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        v-model="form.esEgresado"
                        
                    >
                        <option value="false">Soy alumno</option>
                        <option value="representante">Soy representante de empresa</option>
                        <option value="true">Soy egresado</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.esEgresado" />
                </div>
                <template v-if="form.esEgresado === 'false'">
                    <div class="col-span-2  mt-4">
                        <InputLabel for="name" value="Nombre" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                            placeholder="Ingrese su nombre"
                            autocomplete="name"
                        />
                        <InputError class="col-span-2 sm:col-span-1 mt-4" :message="form.errors.name" />
                    </div>
                    <!-- Columna para 'Apellido Paterno' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="apellidoP" value="Apellido Paterno" />
                        <TextInput
                            id="apellidoP"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Ingrese su apellido paterno"
                            v-model="form.apellidoP"
                            
                        />
                        <InputError class="mt-2" :message="form.errors.apellidoP" />
                    </div>

                    <!-- Columna para 'Apellido Materno' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="apellidoM" value="Apellido Materno" />
                        <TextInput
                            id="apellidoM"
                            type="text"
                            placeholder="Ingrese su apellido materno"
                            class="mt-1 block w-full"
                            v-model="form.apellidoM"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.apellidoM" />
                    </div>

                    <!-- Columna para 'Correo Electrónico' -->
                    <div class="col-span-2  mt-4">
                        <InputLabel for="email" value="Correo Electrónico" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            placeholder="Ingrese su correo electrónico"
                            required
                            autocomplete="email"
                        />
                        <InputError class="mt-2 sm:col-span-2" :message="form.errors.email" />
                    </div>

                    <!-- Columna para 'Nombre de Usuario' -->
                    <div class="col-span-2  mt-4">
                        <InputLabel for="username" value="Nombre de Usuario" />
                        <TextInput
                            id="username"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.username"
                            placeholder="Ingrese su nombre de usuario"
                            required
                        />
                        <InputError class="mt-2 sm:col-span-2" :message="form.errors.username" />
                    </div>
                    <div class="col-span-2 sm:col-span-1 mt-4">

                        <!-- Columna para 'Contraseña' -->
                        <div class="mt-3">
                            <InputLabel for="password" value="Contraseña" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                placeholder="Ingrese su contraseña"
                                required
                                autocomplete="new-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>
                    </div>

                    <!-- Columna para 'Confirmar Contraseña' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="password_confirmation" value="Confirmar Contraseña" />
                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password_confirmation"
                            required
                            placeholder="Confirme su contraseña"
                        />
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    

                    

                    <!-- Columna para 'Teléfono' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="telefono" value="Teléfono" />
                        <TextInput
                            id="telefono"
                            type="tel"
                            class="mt-1 block w-full"
                            v-model="form.telefono"
                            required
                            placeholder="Ingrese su número de teléfono"
                        />
                        <InputError class="mt-2" :message="form.errors.telefono" />
                    </div>

                    <!-- Columna para 'Sexo' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="sexo" value="Sexo" />
                        <select
                            id="sexo"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            v-model="form.sexo"
                            required
                            placeholder="Seleccione su sexo"
                        >
                            <option value="Hombre">Hombre</option>
                            <option value="Mujer">Mujer</option>
                            <option value="NP">No especificado</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.sexo" />
                    </div>
                </template>
                <template v-if="form.esEgresado === 'representante'">
                    <div class="col-span-2  mt-4">
                        <InputLabel for="name" value="Nombre" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            placeholder="Ingrese su nombre"
                            autofocus
                            autocomplete="name"
                        />
                        <InputError class="mt-2 sm:col-span-2" :message="form.errors.name" />
                    </div>
                    <!-- Columna para 'Apellido Paterno' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="apellidoP" value="Apellido Paterno" />
                        <TextInput
                            id="apellidoP"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.apellidoP"
                            placeholder="Ingrese su apellido paterno"
                            
                        />
                        <InputError class="mt-2" :message="form.errors.apellidoP" />
                    </div>

                    <!-- Columna para 'Apellido Materno' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="apellidoM" value="Apellido Materno" />
                        <TextInput
                            id="apellidoM"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.apellidoM"
                            placeholder="Ingrese su apellido materno"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.apellidoM" />
                    </div>

                    <!-- Columna para 'Correo Electrónico' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="email" value="Correo Electrónico" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            placeholder="Ingrese su correo electrónico"
                            required
                            autocomplete="email"
                        />
                        <InputError class="mt-2 sm:col-span-2" :message="form.errors.email" />
                    </div>

                    <!-- Columna para 'Nombre de Usuario' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="username" value="Nombre de la empresa" />
                        <TextInput
                            id="username"
                            type="text"
                            placeholder="Ingrese el nombre de la empresa o nombre de usuario"
                            class="mt-1 block w-full"
                            v-model="form.username"
                            required
                        />
                        <InputError class="mt-2 sm:col-span-2" :message="form.errors.username" />
                    </div>

                    <!-- Columna para 'Contraseña' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="password" value="Contraseña" />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                            placeholder="Ingrese su contraseña"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <!-- Columna para 'Confirmar Contraseña' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="password_confirmation" value="Confirmar Contraseña" />
                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password_confirmation"
                            placeholder="Confirme su contraseña"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    

                    

                    <!-- Columna para 'Teléfono' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="telefono" value="Teléfono" />
                        <TextInput
                            id="telefono"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Ingrese su número de teléfono"
                            v-model="form.telefono"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.telefono" />
                    </div>

                    <!-- Columna para 'Sexo' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
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
                </template>
                <!-- Mostrar campos adicionales solo si es egresado -->
                <template v-if="form.esEgresado === 'true'">
                    <div class="col-span-2 mt-4">
                        <InputLabel for="name" value="Nombre" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            placeholder="Ingrese su nombre"
                            autofocus
                            autocomplete="name"
                        />
                        <InputError class="mt-2 sm:col-span-2" :message="form.errors.name" />
                    </div>
                    <!-- Columna para 'Apellido Paterno' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="apellidoP" value="Apellido Paterno" />
                        <TextInput
                            id="apellidoP"
                            type="text"
                            class="mt-1 block w-full"
                            autofocus
                            placeholder="Ingrese su apellido paterno"
                            v-model="form.apellidoP"
                            
                        />
                        <InputError class="mt-2" :message="form.errors.apellidoP" />
                    </div>

                    <!-- Columna para 'Apellido Materno' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="apellidoM" value="Apellido Materno" />
                        <TextInput
                            id="apellidoM"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.apellidoM"
                            placeholder="Ingrese su apellido materno"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.apellidoM" />
                    </div>

                    <!-- Columna para 'Correo Electrónico' -->
                    <div class="col-span-2 mt-4">
                        <InputLabel for="email" value="Correo Electrónico" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            placeholder="Ingrese su correo electrónico"
                            required
                            autocomplete="email"
                        />
                        <InputError class="mt-2 sm:col-span-2" :message="form.errors.email" />
                    </div>

                    <!-- Columna para 'Nombre de Usuario' -->
                    <div class="col-span-2 mt-4">
                        <InputLabel for="username" value="Nombre de Usuario" />
                        <TextInput
                            id="username"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.username"
                            placeholder="Ingrese su nombre de usuario"
                            required
                        />
                        <InputError class="mt-2 sm:col-span-2" :message="form.errors.username" />
                    </div>

                    <!-- Columna para 'Contraseña' -->
                    <div class="mt-3">
                        <InputLabel for="password" value="Contraseña" />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            placeholder="Ingrese su contraseña"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <!-- Columna para 'Confirmar Contraseña' -->
                    <div class="mt-3">
                        <InputLabel for="password_confirmation" value="Confirmar Contraseña" />
                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password_confirmation"
                            placeholder="Confirme su contraseña"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>
                    <!-- Columna para 'Teléfono' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="telefono" value="Teléfono" />
                        <TextInput
                            id="telefono"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.telefono"
                            placeholder="Ingrese su número de teléfono"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.telefono" />
                    </div>

                    <!-- Columna para 'Sexo' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
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
                    <!-- Columna para 'Matricula escolar' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="matricula" value="Matricula escolar" />
                        <TextInput
                            id="matricula"
                            placeholder="Ingrese su matricula escolar"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.matricula"
                            
                        />
                        <InputError class="mt-2" :message="form.errors.matricula" />
                    </div>

                    <!-- Columna para 'Año de egreso' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="anioEgreso" value="Año de egreso" />
                        <select
                            id="anioEgreso"
                            v-model="form.anioEgreso"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required
                            placeholder="Seleccione su año de egreso"
                        >
                            <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.anioEgreso" />
                    </div>

                    <!-- Columna para 'Carrera' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <label for="carrera">Selecciona una carrera:</label>
                        <select
                            id="carrera"
                            v-model="form.carrera_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required
                            placeholder="Selecciona una carrera"
                        >
                            <option value="">Selecciona una carrera</option>
                            <option v-for="carrera in carreras" :key="carrera.idCarrera" :value="carrera.idCarrera">{{ carrera.NombreCarrera }}</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.carrera_id" />
                    </div>

                    <!-- Columna para 'Dirección' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="direccion" value="Dirección" />
                        <TextInput
                            id="direccion"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.direccion"
                            required
                            placeholder="Ingrese su dirección"
                        />
                        <InputError class="mt-2" :message="form.errors.direccion" />
                    </div>

                    <!-- Columna para 'Empresa actual' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="empresaActual" value="Empresa actual" />
                        <TextInput
                            id="empresaActual"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.empresaActual"
                            required
                            placeholder="Ingrese el nombre de la empresa actual en la que trabajas"
                        />
                        <InputError class="mt-2" :message="form.errors.empresaActual" />
                    </div>

                    <!-- Columna para 'Puesto de trabajo' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="puestoTrabajo" value="Puesto de trabajo" />
                        <select
                            id="puestoTrabajo"
                            v-model="form.puestoTrabajo"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required
                        >
                            <option value="">Selecciona un puesto de trabajo</option>
                            <option value="Administrativo">Administrativo</option>
                            <option value="Analista">Analista</option>
                            <option value="Asistente">Asistente</option>
                            <option value="Auxiliar">Auxiliar</option>
                            <option value="Consultor">Consultor</option>
                            <option value="Coordinador">Coordinador</option>
                            <option value="Contador">Contador</option>
                            <option value="Desarrollador de Software">Desarrollador de Software</option>
                            <option value="Director">Director</option>
                            <option value="Diseñador Gráfico">Diseñador Gráfico</option>
                            <option value="Ejecutivo de Ventas">Ejecutivo de Ventas</option>
                            <option value="Especialista">Especialista</option>
                            <option value="Gerente">Gerente</option>
                            <option value="Ingeniero">Ingeniero</option>
                            <option value="Jefe de Departamento">Jefe de Departamento</option>
                            <option value="Operador">Operador</option>
                            <option value="Recursos Humanos">Recursos Humanos</option>
                            <option value="Representante de Servicio al Cliente">Representante de Servicio al Cliente</option>
                            <option value="Supervisor">Supervisor</option>
                            <option value="Técnico">Técnico</option>
                            <option value="Desempleado">Desempleado</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.puestoTrabajo" />
                    </div>


                    <!--
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="puestoTrabajo" value="Puesto de trabajo" />
                        <TextInput
                            id="puestoTrabajo"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.puestoTrabajo"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.puestoTrabajo" />
                    </div>
                    -->
                    

                    <!-- Columna para 'Años laborales' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="aniosLaboral" value="¿Cuánto tiempo llevas desempeñándote en este puesto?" />
                        <TextInput
                            id="aniosLaboral"
                            type="number"
                            class="mt-1 block w-full"
                            v-model="form.aniosLaboral"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.aniosLaboral" />
                    </div>

                    <!-- Columna para 'Sector empresarial' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="sectorEmpresarial" value="¿A qué sector empresarial pertenece tu puesto?" />
                        <select
                            id="sectorEmpresarial"
                            v-model="form.sectorEmpresarial"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required
                        >
                        <option value="Primario">Primario</option>
                        <option value="Secundario">Secundario</option>
                        <option value="Terciario">Terciario</option>
                        <option value="Educativo">Educativo</option>
                        <option value="Público">Público</option>
                        <option value="Ganadero">Ganadero</option>
                        <option value="Empresarial">Empresarial</option>
                        <option value="Privado">Privado</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.sectorEmpresarial" />
                    </div>

                    <!-- Columna para 'Información adicional' -->
                    <div class="col-span-2 sm:col-span-1 mt-4">
                        <InputLabel for="adicional" value="¿Me puedes contar un poco sobre ti?" />
                        <TextArea
                                id="Adicional"
                                class="mt-1 block w-full"
                                v-model="form.adicional"
                                required
                                cols="50"
                                placeholder="Ingresa información adicional sobre ti"
                            />
                        <InputError class="mt-2" :message="form.errors.adicional" />
                    </div>
                </template>
            </div>

            <!-- Botón de registro -->
            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    ¿Esta registrado?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Registrarse
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>