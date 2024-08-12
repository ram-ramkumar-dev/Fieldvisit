
var chart; // Declare chart variable globally

function initializeChart() {
    if(jQuery("#chart-apex-column-03").length){ var options = {
        series: [chartData03.pending, chartData03.completed,chartData03.assign, chartData03.aborted],
        chart: {
            height: 330,
            type: 'donut'
        },
        labels: ["Progress", "Completed", "Assigned", "Aborted"],
        colors: ['#ffbb33', '#8080ff', '#04237D', '#e60000'],
        plotOptions: {
            pie: {
                startAngle: -90,
                endAngle: 270,
                donut: {
                    size: '80%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            color: '#BCC1C8',
                            fontSize: '18px',
                            fontFamily: 'DM Sans',
                            fontWeight: 600,
                            label: 'Total',
                            formatter: function (w) {
                                return chartData03.total;
                            }
                        },
                        value: {
                            show: true,
                            fontSize: '25px',
                            fontFamily: 'DM Sans',
                            fontWeight: 700,
                            color: '#8F9FBC'
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            lineCap: 'round'
        },
        grid: {
            padding: {
                bottom: 0
            }
        },
        legend: {
            position: 'bottom',
            offsetY: 8,
            show: true
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    height: 268
                }
            }
        }]
    };

    chart = new ApexCharts(document.querySelector("#chart-apex-column-03"), options);
    chart.render();
    const body = document.querySelector('body')
    if (body.classList.contains('dark')) {
    apexChartUpdate(chart, {
        dark: true
    })
    }

    document.addEventListener('ChangeColorMode', function (e) {
    apexChartUpdate(chart, e.detail)
    })
}
}

// Initialize the chart when the document is ready
document.addEventListener('DOMContentLoaded', initializeChart);


function updateChart(newData) {
    if (window.chart) {
        chart.updateOptions({
            series: [newData.pending, newData.completed,newData.assign, newData.aborted],
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            total: {
                                formatter: function (w) {
                                    return newData.total;
                                }
                            }
                        }
                    }
                }
            }
        });
    } else {
        console.error('Chart instance is not available');
    }
}

// Example of fetching data and updating the chart
function fetchDataAndUpdateChart(batchId) {
    $.ajax({
        url: getBatchforchartData03, // Update with your endpoint URL
        type: 'GET',
        data: { value: batchId },
        success: function(response) {
         console.log(response);
            var newData = {
                total: response.total, // Adjust based on your response structure
                completed: response.completed,
                assign: response.assign,
                pending: response.pending,
                aborted: response.aborted
            };

            // Hide the dropdown menu
            var dropdownMenu = document.getElementById('batchDropdownMenu');
            if (dropdownMenu) {
                dropdownMenu.classList.remove('show');
            }
            updateChart(newData);
        }
    });

} 
