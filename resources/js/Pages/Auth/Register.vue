<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
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
    esEgresado: 'false', // Inicialmente no es egresado
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
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
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
                    <InputLabel for="name" value="Nombre" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2 sm:col-span-2" :message="form.errors.name" />
                </div>
                <!-- Columna para 'Apellido Paterno' -->
                <div class="mt-4">
                    <InputLabel for="apellidoP" value="Apellido Paterno" />
                    <TextInput
                        id="apellidoP"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.apellidoP"
                        
                    />
                    <InputError class="mt-2" :message="form.errors.apellidoP" />
                </div>

                <!-- Columna para 'Apellido Materno' -->
                <div class="mt-4">
                    <InputLabel for="apellidoM" value="Apellido Materno" />
                    <TextInput
                        id="apellidoM"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.apellidoM"
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
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>

                

                

                <!-- Columna para 'Teléfono' -->
                <div class="mt-4">
                    <InputLabel for="telefono" value="Teléfono" />
                    <TextInput
                        id="telefono"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.telefono"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.telefono" />
                </div>

                <!-- Columna para 'Sexo' -->
                <div class="mt-4">
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
                <div class="col-span-2 mt-4">
                    <InputLabel for="esEgresado" value="¿Eres egresado?" />
                    <select
                        id="esEgresado"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        v-model="form.esEgresado"
                        
                    >
                        <option value="false">No soy egresado</option>
                        <option value="true">Soy egresado</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.esEgresado" />
                </div>
                <!-- Mostrar campos adicionales solo si es egresado -->
                <template v-if="form.esEgresado === 'true'">
                    <!-- Columna para 'Matricula escolar' -->
                    <div class="mt-4">
                        <InputLabel for="matricula" value="Matricula escolar" />
                        <TextInput
                            id="matricula"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.matricula"
                            
                        />
                        <InputError class="mt-2" :message="form.errors.matricula" />
                    </div>

                    <!-- Columna para 'Año de egreso' -->
                    <div class="mt-4">
                        <InputLabel for="anioEgreso" value="Año de egreso" />
                        <select
                            id="anioEgreso"
                            v-model="form.anioEgreso"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required
                        >
                            <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.anioEgreso" />
                    </div>

                    <!-- Columna para 'Carrera' -->
                    <div class="mt-4">
                        <label for="carrera">Selecciona una carrera:</label>
                        <select
                            id="carrera"
                            v-model="form.carrera_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required
                        >
                            <option value="">Selecciona una carrera</option>
                            <option v-for="carrera in carreras" :key="carrera.idCarrera" :value="carrera.idCarrera">{{ carrera.NombreCarrera }}</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.carrera_id" />
                    </div>

                    <!-- Columna para 'Dirección' -->
                    <div class="mt-4">
                        <InputLabel for="direccion" value="Dirección" />
                        <TextInput
                            id="direccion"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.direccion"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.direccion" />
                    </div>

                    <!-- Columna para 'Empresa actual' -->
                    <div class="mt-4">
                        <InputLabel for="empresaActual" value="Empresa actual" />
                        <TextInput
                            id="empresaActual"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.empresaActual"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.empresaActual" />
                    </div>

                    <!-- Columna para 'Puesto de trabajo' -->
                    <div class="mt-4">
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
                        </select>
                        <InputError class="mt-2" :message="form.errors.puestoTrabajo" />
                    </div>


                    <!--
                    <div class="mt-4">
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
                    <div class="mt-4">
                        <InputLabel for="aniosLaboral" value="Años laborales" />
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
                    <div class="mt-4">
                        <InputLabel for="sectorEmpresarial" value="Sector empresarial" />
                        <select
                            id="sectorEmpresarial"
                            v-model="form.sectorEmpresarial"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required
                        >
                            <option value="Privado">Privado</option>
                            <option value="Público">Público</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.sectorEmpresarial" />
                    </div>

                    <!-- Columna para 'Información adicional' -->
                    <div class="mt-4">
                        <InputLabel for="adicional" value="Cuentame sobre ti" />
                        <TextInput
                            id="adicional"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.adicional"
                            required
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