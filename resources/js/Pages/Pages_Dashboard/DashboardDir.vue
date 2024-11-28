<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import BaseChart from '@/Components/BaseChart.vue';


// Definir las referencias
const chartType = ref('Bar');

//valores de fechas para la grafica de egresados
const fecha_inicio = ref('');
const fecha_fin = ref('');
const fecha_inicioP = ref('');
const fecha_finP = ref('');
const errors = ref({
    fecha_fin: '',
    fecha_inicio: '',
});

const loaded = ref(false);
const loaded2 = ref(false);
const loaded3 = ref(false);
const chartData = ref({
    labels: [], // Nombres de las carreras
    datasets: [{
        label: 'Número de Egresados',
        data: [], // Totales de egresados por carrera
        backgroundColor: '#42A5F5',
    }]
});
const chartData2 = ref({
    labels: [], // en eje x
    datasets: [],
});
const chartData3 = ref({
    labels: [], // en eje x
    datasets: [],
});
const chartPonencias = ref({
    labels: [], // Nombres de las carreras
    datasets: [
        {
            data: [], // Totales de ponencias por carrera
            backgroundColor: [],
        }
    ],
})

const chartAnuncios = ref({
    labels: [], // Nombres de las carreras
    datasets: [
        {
            label: 'Anuncios publicados por categoría',
            data: [], // Totales de anuncios por carrera
            backgroundColor: '#42A5F5',
        }
    ],
});

const chartData1 = ref({
    labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'], // Los días en el eje X
        datasets: [
          {
            label: 'Semana 1', // Primer dataset (semana)
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1,
            data: [5, 10, 15, 7, 12]  // Valores para cada día en la primera semana
          },
          {
            label: 'Semana 2', // Segundo dataset (semana)
            backgroundColor: 'rgba(255, 99, 132, 0.6)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1,
            data: [8, 11, 14, 9, 13]  // Valores para cada día en la segunda semana
          }
        ]
});

// Opciones de la gráfica
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            beginAtZero: true // Comenzar en cero
        }
    }
};
const chartOptionsPie = {
    responsive: true,
    maintainAspectRatio: false,
};

// Función para obtener los datos de la gráfica
const fetchChartData = async () => {
    try {
        
        const response = await axios.post('/api/GraficaNEgresados',
        {   withCredentials: true

        });
        chartData.value.labels = response.data.egresados.map(egresado => egresado.carrera.NombreCarrera);
        chartData.value.datasets[0].data = response.data.egresados.map(egresado => egresado.total);

        chartData2.value.labels = response.data.egresadosanios.map(egresado => egresado.NombreCarrera);
        const numeroCarreras=response.data.egresados.length;
        for(let i=0; i<numeroCarreras; i++){
            chartData2.value.datasets.push({
                label: response.data.egresadosanios[i].AnioEgreso,
                data: response.data.egresadosanios.map(egresado => 
                    egresado.AnioEgreso === response.data.egresadosanios[i].AnioEgreso ? egresado.total : 0
                ),
                backgroundColor: `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.6)`,
            });

        }
        chartData3.value.labels = response.data.puestoDetrabajos.map(puesto => puesto.PuestoTrabajo);
        response.data.egresados.map(egresado => 
            chartData3.value.datasets.push({
                label: egresado.carrera.NombreCarrera,
                data: response.data.puestoDetrabajos.map(puesto => 
                    puesto.NombreCarrera === egresado.carrera.NombreCarrera ? puesto.total : 0
                ),
                backgroundColor: `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.6)`,

            })    

        );
        chartAnuncios.value.labels = response.data.anuncios.map(anuncio => anuncio.Nombre);
        chartAnuncios.value.datasets[0].data = response.data.anuncios.map(anuncio => anuncio.total);
        
        chartPonencias.value.labels = response.data.TopPonencias.map(ponencia => ponencia.name);
        chartPonencias.value.datasets[0].data = response.data.TopPonencias.map(ponencia => ponencia.total);
        chartPonencias.value.datasets[0].backgroundColor = response.data.TopPonencias.map(ponencia => `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.6)`);
        console.log(response.data);
        loaded2.value = true;
        loaded.value = true;
        loaded3.value = true;
    } catch (error) {
        console.error('Error al obtener datos:', error);
    }
};

const AnunciosData = async () => {
    try {
        
        const response2 = await axios.post('/api/GraficaAnunciosPonencias',{
            fecha_inicio: fecha_inicio.value,
            fecha_fin: fecha_fin.value,
        }, 
        {   withCredentials: true

        });
        chartAnuncios.value.labels = response2.data.anuncios.map(anuncio => anuncio.Nombre);
        chartAnuncios.value.datasets[0].data = response2.data.anuncios.map(anuncio => anuncio.total);
        console.log(response2.data);
        loaded2.value = true;
    } catch (error) {
        console.error('Error al obtener datos:', error);
    }
};

const PonenciaData = async () => {
    try {
        
        const response = await axios.post('/api/GraficaPonencias',{
            fecha_inicioP: fecha_inicioP.value,
            fecha_finP: fecha_finP.value,
        }, 
        {   withCredentials: true

        });
        chartPonencias.value.labels = response.data.anuncios.map(ponencia => ponencia.name);
        chartPonencias.value.datasets[0].data = response.data.anuncios.map(ponencia => ponencia.total);
        chartPonencias.value.datasets[0].backgroundColor = response.data.anuncios.map(ponencia => `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.6)`);
        
        console.log(response.data);
        loaded3.value = true;
    } catch (error) {
        console.error('Error al obtener datos:', error);
    }
};



// Llamada a las gráficas
onMounted(() => {
    fetchChartData();

});
watch([fecha_inicio, fecha_fin], () => {
    
    const inicio = new Date(fecha_inicio.value);
    const fin = new Date(fecha_fin.value);
    errors.value.fecha_inicio = '';
    errors.value.fecha_fin = '';

    // Verificar que ambas fechas estén presentes
    if (fecha_inicio.value && fecha_fin.value) {
        if (inicio > fin) {
             errors.value.fecha_fin = 'La fecha de inicio no puede ser mayor a la fecha de fin';
         } else if (fin < inicio) {
             errors.value.fecha_inicio = 'La fecha de fin no puede ser menor a la fecha de inicio';
         } else {
             // Si las fechas son válidas, llama a AnunciosData
             AnunciosData();
             loaded2.value = false; // Actualiza el estado
         }
    }
});
watch([fecha_inicioP, fecha_finP], () => {
    
    const inicio = new Date(fecha_inicioP.value);
    const fin = new Date(fecha_finP.value);
    errors.value.fecha_inicioP = '';
    errors.value.fecha_finP = '';

    // Verificar que ambas fechas estén presentes
    if (fecha_inicioP.value && fecha_finP.value) {
        if (inicio > fin) {
             errors.value.fecha_finP = 'La fecha de inicio no puede ser mayor a la fecha de fin';
         } else if (fin < inicio) {
             errors.value.fecha_inicioP = 'La fecha de fin no puede ser menor a la fecha de inicio';
         } else {
             // Si las fechas son válidas, llama a AnunciosData
             PonenciaData();
             loaded3.value = false; // Actualiza el estado
         }
    }
});
</script>

<template>
    <Head title="Dashboard De director" />
    <AuthenticatedLayout>
        <template #header>
            Dashboard de director
        </template>
        <br>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div >
                    <a :href="route('Egresados.ReporteEgresados')" target="_blank" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 me-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        Crear PDF de egresados
                    </a>
                    <a :href="route('Ponencias.ReportePonencias')" target="_blank" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 me-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        Crear PDF de anuncios y ponencias
                    </a>
                    <a :href="route('Carreras.ReporteCarreras')" target="_blank" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 me-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        Crear PDF de carreras
                    </a>

                </div>
                <br>
                    <h2 class="text-lg font-bold mb-4">Gráficas sobre datos de egresados</h2>

                <div class="flex flex-wrap justify-between">
                    <!-- Primera gráfica -->
                    <div class="w-full sm:w-1/2 p-2 ">
                        <h3 class="text-center font-semibold mb-2">Número de egresados en cada carrera</h3>
                        <div class="relative " style="padding-bottom: 50%;">
                            <BaseChart
                                v-if="loaded"
                                :chartType="'Bar'"
                                chart-id="bar-chart-1"
                                :chart-data="chartData"
                                :chart-options="chartOptions"
                                class="absolute inset-0 w-full h-full"
                            />
                        </div>
                    </div>

                    <!-- Segunda gráfica -->
                    <div class="w-full sm:w-1/2 p-2">
                        <h3 class="text-center font-semibold mb-2">Número de egresados por año de egreso</h3>
                        <div class="relative" style="padding-bottom: 50%;">
                            <BaseChart
                                v-if="loaded"
                                :chartType="'Bar'"
                                chart-id="bar-chart-2"
                                :chart-data="chartData2"
                                :chart-options="chartOptions"
                                class="absolute inset-0 w-full h-full"
                            />
                        </div>
                    </div>

                    <!-- Tercera gráfica -->
                    <div class="w-full sm:w-1/2 p-2">
                        <h3 class="text-center font-semibold mb-2">Principales puestos de trabajo</h3>
                        <div class="relative" style="padding-bottom: 50%;">
                            <BaseChart
                                v-if="loaded"
                                :chartType="'Bar'"
                                chart-id="bar-chart-3"
                                :chart-data="chartData3"
                                :chart-options="chartOptions"
                                class="absolute inset-0 w-full h-full"
                            />
                        </div>
                    </div>
                    <br>
                </div>
                
                <br>
                <h2 class="text-lg font-bold mb-4">Datos sobre los anuncios y las ponencias</h2>
                <div class="flex flex-wrap justify-between">
                    <!-- Primera gráfica -->
                    <div class="w-full sm:w-1/2 p-2 ">
                        <div class="grid grid-cols-1 sm:grid-cols-6 gap-4">
                            <div class="col-span-6 mt-4">                                
                                <h2>Modificador de las fechas</h2>
                            </div>
                            <div class="col-span-6 sm:col-span-3 mt-4"> <!-- Cambiado a col-span-6 -->
                                <InputLabel for="fecha_inicio" value="Fecha de Inicio:"/>
                                <TextInput type="date" id="fecha_inicio" v-model="fecha_inicio"/>
                            </div>
                            <div class="col-span-6 sm:col-span-3 mt-4"> <!-- Cambiado a col-span-6 -->
                                <InputLabel for="fecha_fin" value="Fecha de Fin:"/>
                                <TextInput type="date" id="fecha_fin" v-model="fecha_fin"/>
                            </div>
                            <div class="col-span-6 mt-4">
                                <InputError class="mt-2" :message="errors.fecha_fin" />
                            </div>
                        </div>

                        <h3 class="text-center font-semibold mb-2">Número de anuncios publicados</h3>
                        <div class="relative " style="padding-bottom: 50%;">
                            <BaseChart
                                v-if="loaded2"
                                :chartType="'Bar'"
                                chart-id="bar-chart-1"
                                :chart-data="chartAnuncios"
                                :chart-options="chartOptions"
                                class="absolute inset-0 w-full h-full"
                            />
                        </div>
                    </div>
                    <div class="w-full sm:w-1/2 p-2 ">
                        <div class="grid grid-cols-1 sm:grid-cols-6 gap-4">
                            <div class="col-span-6 mt-4">                                
                                <h2>Modificador de las fechas</h2>
                            </div>
                            <div class="col-span-6 sm:col-span-3 mt-4"> <!-- Cambiado a col-span-6 -->
                                <InputLabel for="fecha_inicio" value="Fecha de Inicio:"/>
                                <TextInput type="date" id="fecha_inicio" v-model="fecha_inicioP"/>
                            </div>
                            <div class="col-span-6 sm:col-span-3 mt-4"> <!-- Cambiado a col-span-6 -->
                                <InputLabel for="fecha_fin" value="Fecha de Fin:"/>
                                <TextInput type="date" id="fecha_fin" v-model="fecha_finP"/>
                            </div>
                            <div class="col-span-6 mt-4">
                                <InputError class="mt-2" :message="errors.fecha_finP" />
                            </div>
                        </div>

                        <h3 class="text-center font-semibold mb-2">Principales ponentes</h3>
                        <div class="relative " style="padding-bottom: 50%;">
                            <BaseChart
                                v-if="loaded3"
                                :chartType="'Pie'"
                                chart-id="bar-chart-1"
                                :chart-data="chartPonencias"
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

