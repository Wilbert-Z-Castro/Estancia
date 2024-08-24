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

const props = defineProps({
    Egresado: { type: Object },
    carreras:{type: Object},
});

const valoresIniciales = {
    name: props.Egresado.user.name,
    email: props.Egresado.user.email,
    current_password: '', 
    password: '',
    password_confirmation: '',
    rol: props.Egresado.user.rol,
    username: props.Egresado.user.UserName,
    apellidoP: props.Egresado.user.ApellidoP,
    apellidoM: props.Egresado.user.ApellidoM,
    telefono: props.Egresado.user.Telefono,
    sexo: props.Egresado.user.Sexo,
    Matricula: props.Egresado.Matricula,
    Carrera: props.Egresado.Carrera,
    AnioEgreso: props.Egresado.AnioEgreso,
    Direccion: props.Egresado.Direccion,
    EmpresaActual: props.Egresado.EmpresaActual,
    PuestoTrabajo: props.Egresado.PuestoTrabajo,
    AniosLaboral: props.Egresado.AniosLaboral,
    Adicional: props.Egresado.Adicional,
    SectorEmpresaria: props.Egresado.SectorEmpresaria,
};


const form = useForm(valoresIniciales);

const submit = () => {
    form.put(route('egresados.update', props.Egresado.idEgresado), {
        onSuccess: () => form.reset(),
        onError: () => {
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
];

const SectorEmpresaria=[
    { value: "", label: "Selecciona un sector " },
    { value: "Primario",label: "Primario" },
    { value: "Terciario",label: "Terciario" },
    { value: "Secundario",label: "Secundario" },
    { value: "Educativo",label: "Educativo" },
    { value: "Publico",label: "Publico" },
    { value: "Ganadero",label: "Ganadero" },
    { value: "Empresarial",label: "Empresarial" },
    
]





</script>

<template>
    <Head title="Gestion categorias formulario" />
    <AuthenticatedLayout>
        <template #header>
            Insertar datos de Egresado
        </template>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg  ">
            <div class="p-6 mx-2 border-b border-gray-200 overflow-y-auto">
                <p>
                    {{ form.errors }}
                </p>
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
                            />
                        
                            
                            <InputError   class="mt-2 sm:col-span-2" :message="form.errors.name" />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="username" value="Apodo" />
                            <TextInput
                                id="username"
                                type="text"
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
                                
                                
                                autocomplete="email"
                            />
                            <InputError class="mt-2 sm:col-span-2" :message="form.errors.email" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="col-span-6 mt-4">
                            <InputLabel for="current_password" value="Contraseña Actual" />
                            <TextInput
                                id="current_password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.current_password"
                                :autofocus="!!form.errors.current_password"
                                
                                
                                
                            />
                            
                            <InputError  class="mt-2" :message="form.errors.current_password"  />
                        </div>
                        <div class="col-span-6 mt-4">
                            <InputLabel for="password" value="Nueva Contraseña" />
                            <TextInput
                                id="password"
                                type="password"
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
                                v-model="form.password_confirmation"
                                
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
                            />
                            <InputError class="mt-2" :message="form.errors.apellidoM" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <!-- Columna para 'Teléfono' -->
                        <div class="col-span-6 mt-4">
                            <InputLabel for="telefono" value="Teléfono" />
                            <TextInput
                                id="telefono"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.telefono"
                                required
                                min="1"
                                step="1"
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
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 ">    
                        <div class="col-span-6 mt-4">
                            <InputLabel for="EmpresaActual" value="EmpresaActual" />
                            <TextInput
                                id="EmpresaActual"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.EmpresaActual"
                                required
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
                            <InputLabel for="AniosLaboral" value="Año de laborando" />
                            <TextInput
                                id="AniosLaboral"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.AniosLaboral"
                                required
                                min="1"
                                step="1"
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

                                Registrarse
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
