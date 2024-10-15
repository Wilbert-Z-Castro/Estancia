<template>
  <div>
    <component
      :is="chartType"
      :id="chartId"
      :options="chartOptions"
      :data="chartData"
    />
  </div>
</template>

<script>
import { Bar, Line, Pie } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, LineElement, CategoryScale, LinearScale, ArcElement } from 'chart.js'

// Registra los componentes de gráficos que vas a utilizar
ChartJS.register(Title, Tooltip, Legend, BarElement, LineElement, CategoryScale, LinearScale, ArcElement)

export default {
  name: 'BaseChart',
  components: {
    Bar,
    Line,
    Pie
  },
  props: {
    chartType: {
      type: String,
      required: true
    },
    chartId: {
      type: String,
      required: true
    },
    chartData: {
      type: Object,
      required: true
    },
    chartOptions: {
      type: Object,
      default: () => ({ responsive: true })
    }
  }
}
</script>
