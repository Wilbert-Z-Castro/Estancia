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

    Matricula: '',
    Carrera: '',
    AnioEgreso: '',
    Direccion: '',
    EmpresaActual: '', 
    PuestoTrabajo: '', 
    AniosLaboral: '',
    Adicional: '',
    SectorEmpresaria: '',

};

const jobPositions = [
  { value: "", label: "Selecciona un puesto de trabajo" },
  { value: "Administrativo", label: "Administrativo" },
  { value: "Analista", label: "Analista" },
  { value: "Asistente", label: "Asistente" },
  { value: "Auxiliar", label: "Auxiliar" },
  { value: "Consultor", label: "Consultor" },
  { value: "Coordinador", label: "Coordinador" },
  { value: "Contador", label: "Contador" },
  { value: "Desarrollador de Software", label: "Desarrollador de Software" },
  { value: "Director", label: "Director" },
  { value: "Diseñador Gráfico", label: "Diseñador Gráfico" },
  { value: "Ejecutivo de Ventas", label: "Ejecutivo de Ventas" },
  { value: "Especialista", label: "Especialista" },
  { value: "Gerente", label: "Gerente" },
  { value: "Ingeniero", label: "Ingeniero" },
  { value: "Jefe de Departamento", label: "Jefe de Departamento" },
  { value: "Operador", label: "Operador" },
  { value: "Recursos Humanos", label: "Recursos Humanos" },
  { value: "Representante de Servicio al Cliente", label: "Representante de Servicio al Cliente" },
  { value: "Supervisor", label: "Supervisor" },
  { value: "Técnico", label: "Técnico" },
  { value: "Desempleado", label: "Desempleado" },
];

const SectorEmpresaria=[
    { value: "", label: "Selecciona un sector " },
    { value: "Primario",label: "Primario" },
    { value: "Terciario",label: "Terciario" },
    { value: "Secundario",label: "Secundario" },
    { value: "Educativo",label: "Educativo" },
    { value: "Público",label: "Público" },
    { value: "Ganadero",label: "Ganadero" },
    { value: "Empresarial",label: "Empresarial" },
    { values:"Otro",label:"Otro"},
    
]


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
    form.post(route('egresados.store'), {
        onSuccess: () => {
            form.reset();
            Swal.close();
        },
        onError: () => {
            Swal.close();

        // Enfocar el primer campo de entrada con error
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
            Insertar datos de Egresado
        </template>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg  ">
            <div class="p-6 mx-2 border-b border-gray-200 overflow-y-auto">
                <form @submit.prevent="submit">
                    <!-- Fila 3 -->
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
                                autocomplete="name"
                                placeholder="Ingresar nombre del egresado"
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.name" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="username" value="Apodo" />
                            <TextInput
                                id="username"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.username"
                                required
                                placeholder="Ingresa el apodo o nombre de usuario del egresado"

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
                                autocomplete="email"
                                placeholder="Ingresa el correo electrónico del egresado"

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
                                placeholder="Ingresa la contraseña "
                                
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="password_confirmation" value="Confirmar Contraseña" />
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password_confirmation"
                                required
                                placeholder="Confirma la contraseña"
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
                                v-model="form.apellidoP"
                                required
                                placeholder="Ingresar apellido paterno del egresado"
                            />
                            <InputError class="mt-2" :message="form.errors.apellidoP" />
                        </div>
                    
                        <!-- Columna para 'Apellido Materno' -->
                        <div class="col-span-6 mt-4">
                            <InputLabel for="apellidoM" value="Apellido Materno" />
                            <TextInput
                                id="apellidoM"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.apellidoM"
                                required
                                placeholder="Ingresar apellido materno del egresado"
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
                                placeholder="Ingresar número de teléfono del egresado"
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
                                <option value="" disabled selected >Seleccionar genero</option>
                                <option value="Hombre">Hombre</option>
                                <option value="Mujer">Mujer</option>
                                <option value="NP">No especificado</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.sexo" />

                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 ">    
                        <div class="col-span-6 mt-4">
                            <InputLabel for="EmpresaActual" value="Empresa" />
                            <TextInput
                                id="EmpresaActual"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.EmpresaActual"
                                required
                                placeholder="Ingresa el nombre de la empresa actual del egresado"

                            />
                            <InputError class="mt-2" :message="form.errors.EmpresaActual" />
                        </div>
                    
                        <!-- Columna para 'Apellido Materno' -->
                        <div class="col-span-6 mt-4 relative">
                            <InputLabel for="PuestoTrabajo" value="Puesto de Trabajo" />
                            <v-select
                                v-model="form.PuestoTrabajo"
                                id="PuestoTrabajo"
                                class="mt-1 w-full "
                                :style="{ '--vs-font-size': '1.3rem' }"
                                :options="jobPositions"
                                :required="!form.PuestoTrabajo"
                                :reduce="jobPositions => jobPositions.value"
                                />
                            <InputError class="mt-2" :message="form.errors.PuestoTrabajo" />
                        </div>
                    </div>   
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="Direccion" value="Dirreción:" />
                            <TextInput
                                id="Direccion"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.Direccion"
                                required
                                placeholder="Ingresa la dirección del egresado"

                            />
                            <InputError class="mt-2" :message="form.errors.Direccion" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="Matricula" value="Matricula" />
                            <TextInput
                                id="Matricula"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.Matricula"
                                required
                                placeholder="Ingresa la matricula del egresado"
                            />
                            <InputError class="mt-2" :message="form.errors.Matricula" />
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="AnioEgreso" value="Año de egreso" />
                            <TextInput
                                id="AnioEgreso"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.AnioEgreso"
                                required
                                min="2007"
                                step="1"
                                placeholder="Ingresa el año de egreso del egresado"
                            />
                            <InputError class="mt-2" :message="form.errors.AnioEgreso" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="Carrera" value="Carrera" />
                            <v-select
                                v-model="form.Carrera"
                                id="Carrera"
                                class="mt-1 w-full "
                                :style="{ '--vs-font-size': '1.3rem' }"
                                :options="props.carreras"
                                label="NombreCarrera"
                                :reduce="carrera => carrera.idCarrera"
                                :required="!form.Carrera"
                                />
                            <InputError class="mt-2" :message="form.errors.Carrera" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="AniosLaboral" value="Años de experiencia laboral" />
                            <TextInput
                                id="AniosLaboral"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.AniosLaboral"
                                required
                                min="1"
                                step="1"
                                placeholder="Ingresa los años de experiencia laboral del egresado"
                            />
                            <InputError class="mt-2" :message="form.errors.AniosLaboral" />
                        </div>
                        
                        <div class="col-span-6 mt-4 relative">
                            <InputLabel for="SectorEmpresaria" value="Sector empresarial" />
                            <v-select
                                v-model="form.SectorEmpresaria"
                                id="SectorEmpresaria"
                                class="mt-1 w-full "
                                :style="{ '--vs-font-size': '1.3rem' }"
                                :options="SectorEmpresaria"
                                :required="!form.SectorEmpresaria"
                                :reduce="SectorEmpresaria => SectorEmpresaria.value"
                                />
                            <InputError class="mt-2" :message="form.errors.SectorEmpresaria" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-12 mt-4">
                            <InputLabel for="Adicional" value="Información Adicional" />
                            <TextArea
                                id="Adicional"
                                class="mt-1 block w-full"
                                v-model="form.Adicional"
                                required
                                placeholder="Ingresa información adicional del egresado"
                            />
                            <InputError class="mt-2" :message="form.errors.Adicional" />
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
                            <LinkRegresar class="mx-2" :href="route('egresados.index')">
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
