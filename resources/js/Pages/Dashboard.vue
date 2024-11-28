<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm  } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import BaseChart from '@/Components/BaseChart.vue';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2'

const errors = ref({
    importacion:'',
    expotacion:'',
});

let imagenes =null;
const valoresIniviales = {
    imagenes :null,
};
const loaded = ref(false);

const form = useForm(valoresIniviales);
const fileupdate = (event) => {
    imagenes = event.target.files[0];
    form.imagenes = imagenes;
}

const charRol = ref({
    labels: [], // Nombres de las carreras
    datasets: [
        {
            data: [], // Totales de ponencias por carrera
            backgroundColor: ['#F1E3F3','#C2BBF0','#8FB8ED','#62BFED','#3590F3'],
        }
    ],
});
const chartEgresadosCar = ref({
    labels: [], // Nombres de las carreras
    datasets: [
        {
            data: [], // Totales de ponencias por carrera
            backgroundColor: [],
        }
    ],
});

const charAniEgreso = ref({
    labels: [], // Años de egreso
    datasets: [
        {
            label: 'Carreras',
            data: [], // Totales de egresados por año
        }
    ],
});
const charCarrerasTrabajo = ref({
    labels: [], // Años de egreso
    datasets: [
        {
            label: 'Carreras',
            data: [], // Totales de egresados por año
        }
    ],
});

const chartOptionsPie = {
    responsive: true,
    maintainAspectRatio: false,
};


const submit= (a) => {
    Swal.fire({
        title: 'Cargando',
        text: 'Por favor espera mientras se envían los datos...',
        allowOutsideClick: false, // Deshabilita que el usuario cierre la alerta
        didOpen: () => {
            Swal.showLoading(); // Muestra el spinner
        }
    });
    form.post(route('ResturacionDB'), {
        onSuccess: (response) => {
            form.reset();
            Swal.close();
            setTimeout(() => {
                Swal.fire({
                    title: "Exito!",
                    text: "Base de datos restaurada correctamente",
                    icon: "success"
                });
            }, 300);
            console.log(response);
        },
        onError: (errors) => {
            form.reset();
            Swal.close();
            setTimeout(() => {
                Swal.fire({
                    title: "Error!",
                    text: "Ocurrio un error al restaurar la base de datos",
                    icon: "error"
                });
            }, 300);
            console.log(errors);    
        },
    });

}


const dataUsuarios = async () =>{
    const response = await axios.get('/api/Usuarios',
    {   
        withCredentials: true
    });
    const labelsRol = [];
    const dataRol = [];

    response.data.usuariosPorRol.forEach((usuario) => {
        labelsRol.push(usuario.Rol);
        dataRol.push(usuario.total);
    });

    charRol.value.labels = labelsRol;
    charRol.value.datasets[0].data = dataRol;
    console.log(response);

    const labelsEgresados = [];
    const dataEgresados = [];
    const backgroundColors = [];

    response.data.egresados.forEach((egresado) => {
        labelsEgresados.push(egresado.carrera.NombreCarrera);
        dataEgresados.push(egresado.total);
        backgroundColors.push(`rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.6)`);
    });

    chartEgresadosCar.value.labels = labelsEgresados;
    chartEgresadosCar.value.datasets[0].data = dataEgresados;
    chartEgresadosCar.value.datasets[0].backgroundColor = backgroundColors;

    const labelsAniEgreso = [];
    const dataAniEgreso = [];
    const backgroundColorsAniEgreso = [];
    response.data.anioEgreso.forEach((anio)=>{
        labelsAniEgreso.push(anio.AnioEgreso);
        dataAniEgreso.push(anio.total);
        backgroundColorsAniEgreso.push(`rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.6)`);
    });
    charAniEgreso.value.labels = labelsAniEgreso;
    charAniEgreso.value.datasets[0].data = dataAniEgreso;
    charAniEgreso.value.datasets[0].backgroundColor = backgroundColorsAniEgreso;

    const labelsCarrerasTrabajo = [];
    const dataCarrerasTrabajo = [];
    response.data.TrabajoPorCarrera.forEach((carrera)=>{
        labelsCarrerasTrabajo.push(carrera.carrera.NombreCarrera);
        dataCarrerasTrabajo.push(carrera.total);
    });
    charCarrerasTrabajo.value.labels = labelsCarrerasTrabajo;
    charCarrerasTrabajo.value.datasets[0].data = dataCarrerasTrabajo;
    charCarrerasTrabajo.value.datasets[0].backgroundColor = backgroundColors;

    loaded.value = true;

    
};

onMounted(() => {
    dataUsuarios();

});

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            Panel principal
        </template>
        <br>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <p>Respaldo y restauración de DB</p>
                <p>En esta sección podrás realizar respaldos y restauraciones de la base de datos</p>

                <br>
                <form @submit.prevent="submit">

                    <InputLabel for="imagenes" value="Seleccionar archivo SQL" />
                        <input
                        id="imagenes"
                        type="file"
                        accept=".sql"
                        @change="fileupdate"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        />
                    <InputError class="mt-2":message="form.errors.imagenes"  />
                    <br>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Hacer restauración</button>

                    <br>
                </form>
                <h3>Exporta Base de datos</h3>
                <br>
                <a :href="route('RespaldoDB')" target="_blank" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Hacer respaldo</a>

            </div>
        </div>
        <br>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <p>Información generar del sistema</p>
                <div class="flex flex-wrap justify-between">
                    <div class="w-full sm:w-1/2 p-2 ">
                        <h3 class="text-center font-semibold mb-2">Número de Usuarios</h3>
                        <div class="relative " style="padding-bottom: 50%;">
                            <BaseChart
                                v-if="loaded"
                                :chartType="'Pie'"
                                chart-id="bar-chart-1"
                                :chart-data="charRol"
                                :chart-options="chartOptionsPie"
                                class="absolute inset-0 w-full h-full"
                            />
                        </div>
                        
                    </div>
                    <div class="w-full sm:w-1/2 p-2 ">
                        <h3 class="text-center font-semibold mb-2">Número de egresados por carrera</h3>
                        <div class="relative " style="padding-bottom: 50%;">
                            <BaseChart
                                v-if="loaded"
                                :chartType="'Pie'"
                                chart-id="bar-chart-1"
                                :chart-data="chartEgresadosCar"
                                :chart-options="chartOptionsPie"
                                class="absolute inset-0 w-full h-full"
                            />
                        </div>
                        
                    </div>
                </div>
                <div class="flex flex-wrap justify-between">
                    <div class="w-full sm:w-1/2 p-2 ">
                        <h3 class="text-center font-semibold mb-2">Años de egreso</h3>
                        <div class="relative " style="padding-bottom: 50%;">
                            <BaseChart
                                v-if="loaded"
                                :chartType="'Pie'"
                                chart-id="bar-chart-1"
                                :chart-data="charAniEgreso"
                                :chart-options="chartOptionsPie"
                                class="absolute inset-0 w-full h-full"
                            />
                        </div>
                        
                    </div>
                    <div class="w-full sm:w-1/2 p-2 ">
                        <h3 class="text-center font-semibold mb-2">Ofertas de trabajo por carreras</h3>
                        <div class="relative " style="padding-bottom: 50%;">
                            <BaseChart
                                v-if="loaded"
                                :chartType="'Pie'"
                                chart-id="bar-chart-1"
                                :chart-data="charCarrerasTrabajo"
                                :chart-options="chartOptionsPie"
                                class="absolute inset-0 w-full h-full"
                            />
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
