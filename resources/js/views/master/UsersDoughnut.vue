<template>
    <doughnut-chart style="height: 300px" :chart-data="dataCollection" :options="options"></doughnut-chart>
</template>

<script>
    import DoughnutChart from '../../services/doughnutChart'

    export default {
        name: "UsersDoughnut",
        components: {
            DoughnutChart
        },
        data() {
            return {
                dataCollection: {},
                options: {}
            }
        },
        mounted() {
            this.fillData()
        },
        methods: {
            fillData() {
                const vm = this;
                const url = '/api/users/report/gender';

                axios.get(url)
                    .then(function (response) {
                        if (response.status === 200) {
                            vm.dataCollection = {
                                labels: ['Male', 'Female'],
                                datasets: [
                                    {
                                        label: "Data One",
                                        backgroundColor: ['#17a2b8', '#ffc107'],
                                        data: [response.data.male, response.data.female]
                                    },
                                ]
                            }
                        }
                    })
                    .catch(function (response) {
                        console.log(response);
                    });

                this.options = {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    },
                    maintainAspectRatio: false,
                }
            }
        }
    }
</script>

<style scoped>
    .doughnut {
        max-height: 300px;
    }
</style>
