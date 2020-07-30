<template>
    <bar-chart style="height: 300px" :chart-data="dataCollection" :options="options"></bar-chart>
</template>

<script>
    import BarChart from '../../services/barChart'
    import axios from "axios";

    export default {
        name: 'UsersArea',
        components: {
            BarChart
        },
        data() {
            return {
                dataCollection: {},
                options: {},
            }
        },
        mounted() {
            this.fillData()
        },
        methods: {
            fillData() {
                const vm = this;
                const url = '/api/users/report/months';

                axios.get(url)
                    .then(function (response) {
                        if (response.status === 200) {
                            vm.dataCollection = {
                                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                datasets: [
                                    {
                                        label: 'Data One',
                                        backgroundColor: '#17a2b8',
                                        pointBackgroundColor: 'white',
                                        borderWidth: 1,
                                        pointBorderColor: '#249EBF',
                                        data: Object.values(response.data.data)
                                    }
                                ]
                            }
                        }
                    })
                    .catch(function (response) {
                        console.log(response);
                    });

                this.options = {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                            gridLines: {
                                display: true
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                            gridLines: {
                                display: false
                            }
                        }]
                    },
                    legend: {
                        display: false
                    },
                    tooltips: {
                        enabled: true,
                        mode: 'single',
                        callbacks: {
                            label: function (tooltipItems, data) {
                                return tooltipItems.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            }
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                }
            }
        }
    }
</script>

