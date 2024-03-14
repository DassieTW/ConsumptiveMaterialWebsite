<template>
    <Line :data="data" :options="options" />
</template>

<script>
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    PointElement,
    BarElement,
    LineElement,
    CategoryScale,
    LinearScale,
    Colors,
    Filler
} from 'chart.js'
import { Line } from 'vue-chartjs'

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, BarElement, Title, Tooltip, Legend, Colors, Filler)

export default {
    name: 'App',
    components: {
        Line
    },
    data() {
        return {
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [
                    {
                        label: 'D3',
                        borderColor: 'rgb(9, 116, 230)',
                        backgroundColor: 'rgba(9, 116, 230, 0.5)',
                        pointStyle: 'rect',
                        // fill: '1',
                        data: [19, 67, 88, 21, 90, 90, 78, 5, 50, 60, 10, 90],
                    },
                    {
                        label: 'D4',
                        borderColor: 'rgb(245, 44, 44)',
                        backgroundColor: 'rgba(245, 44, 44, 0.5)',
                        pointStyle: 'rect',
                        fill: '-1',
                        data: [50, 60, 10, 40, 90, 100, 78, 5, 50, 60, 10, 90],
                    },
                    // {
                    //     type: 'bar',
                    //     label: 'Bar',
                    //     backgroundColor: '#ff9991',
                    //     data: [10, 20, 30, 40, 90, 100, 50, 1, 40, 90, 100, 5]
                    // },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        }
    }
}
</script>