import * as am5 from '@amcharts/amcharts5';
import am5geodata_worldLow from '@amcharts/amcharts5-geodata/worldLow';
import * as am5map from '@amcharts/amcharts5/map';
import am5themes_Animated from '@amcharts/amcharts5/themes/Animated';
import themeConfig, { themeColors } from '@tailwind.config';
import ApexCharts from 'apexcharts';
import colors from 'tailwindcss/colors';

const theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';

document.addEventListener('DOMContentLoaded', function () {
    // --------- Donut Chart: Usuários Ativos x Inativos ---------
    const chartData = window.activeUsersChartData;

    if (chartData) {
        const options = {
            chart: {
                type: 'donut',
                height: 350
            },
            series: chartData.series,
            labels: chartData.labels,
            colors: ['#34D399', '#F87171'],
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                formatter: function (val, opts) {
                    const count = opts.w.globals.series[opts.seriesIndex];
                    return count + ' usuários';
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#donutChart"), options);
        chart.render();
    }

    // --------- Line Chart: Logins por Dia (Últimos 7 dias) ---------
    const loginChartData = window.loginChartData;

    if (loginChartData) {
        const loginCategories = Object.keys(loginChartData);
        const loginSeriesData = Object.values(loginChartData);

        const loginChartOptions = {
            chart: {
                type: 'line',
                height: 350,
                toolbar: { show: false }
            },
            series: [{
                name: 'Logins',
                data: loginSeriesData
            }],
            xaxis: {
                categories: loginCategories,
                labels: { rotate: -45 }
            },
            colors: ['#3B82F6'],
            stroke: {
                curve: 'smooth'
            },
            dataLabels: {
                enabled: true
            }
        };

        const loginChart = new ApexCharts(document.querySelector("#store-analytics-chart"), loginChartOptions);
        loginChart.render();
    }

    // --------- Bar Chart: Documentos por Macro e Setor ---------
    const macroSectorData = window.macroSectorChartData;

    if (macroSectorData) {
        const macros = Object.keys(macroSectorData);

        const sectorsSet = new Set();
        macros.forEach(macro => {
            macroSectorData[macro].forEach(item => {
                sectorsSet.add(item.sector);
            });
        });

        const sectors = Array.from(sectorsSet);

        const series = sectors.map(sector => {
            return {
                name: sector,
                data: macros.map(macro => {
                    const found = macroSectorData[macro].find(item => item.sector === sector);
                    return found ? found.total : 0;
                })
            };
        });

        const macroSectorChartOptions = {
            chart: {
                type: 'bar',
                height: 400,
                stacked: true,
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                }
            },
            series: series,
            xaxis: {
                categories: macros,
                labels: {
                    style: {
                        colors: theme === 'dark' ? colors.slate[300] : colors.slate[600]
                    }
                }
            },
            colors: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'],
            legend: {
                position: 'top'
            },
            dataLabels: {
                enabled: true
            }
        };

        const macroSectorChart = new ApexCharts(document.querySelector("#macroSectorChart"), macroSectorChartOptions);
        macroSectorChart.render();
    }
});
