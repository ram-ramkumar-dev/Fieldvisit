var chart; // Declare chart variable globally
var barChart;

function initializeChart() {
    if (document.querySelector("#chart-apex-column-03")) {
        var options = {
            series: [chartData03.pending, chartData03.completed, chartData03.assign, chartData03.aborted],
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

        const body = document.querySelector('body');
        if (body.classList.contains('dark')) {
            apexChartUpdate(chart, {
                dark: true
            });
        }

        document.addEventListener('ChangeColorMode', function (e) {
            apexChartUpdate(chart, e.detail);
        });
    }

    if (document.querySelector("#chart-apex-column-02")) {
        var options = {
            series: [
                {
                    name: 'Completed',
                    data: chartData.completed
                },
                {
                    name: 'Pending',
                    data: chartData.pending
                },
                {
                    name: 'Assigned',
                    data: chartData.assigned
                }
            ],
            chart: {
                height: 183,
                type: 'bar',
                toolbar: { show: false },
                sparkline: { enabled: true }
            },
            plotOptions: {
                bar: {
                    columnWidth: '30%',
                    distributed: false,
                    borderRadius: 5
                }
            },
            colors: ['#1f77b4', '#ff7f0e', '#2ca02c'], // Colors for Completed, Pending, and Assigned
            dataLabels: { enabled: false },
            legend: { show: true },
            grid: {
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: false } }
            },
            xaxis: {
                categories: chartData.dates,
                labels: {
                    minHeight: 20,
                    maxHeight: 20,
                    style: { fontSize: '12px' }
                }
            },
            yaxis: {
                labels: {
                    offsetY: 0,
                    minWidth: 10,
                    maxWidth: 10
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return value.toFixed(0); // Converts to integer by removing decimal points
                    }
                }
            }
        };

        barChart = new ApexCharts(document.querySelector("#chart-apex-column-02"), options);
        barChart.render();
    }
}

// Initialize the chart when the document is ready
document.addEventListener('DOMContentLoaded', initializeChart);

function updateChart(newData) {
    if (chart) {
        chart.updateOptions({
            series: [newData.pending, newData.completed, newData.assign, newData.aborted],
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
        success: function (response) { 
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

            // Update the chart with the new data
            updateChart(newData);
        },
        error: function (xhr, status, error) {
            console.error('Error fetching chart data:', error);
        }
    });
}

function apexChartUpdate(chart, detail) {
    let color = getComputedStyle(document.documentElement).getPropertyValue('--dark');
    if (detail.dark) {
        color = getComputedStyle(document.documentElement).getPropertyValue('--white');
    }

    chart.updateOptions({
        chart: {
            foreColor: color
        }
    });
}

function updateBarChart(newData) {
    if (barChart) { // Assuming barChart is declared globally
        barChart.updateOptions({
            series: [
                {
                    name: 'Completed',
                    data: newData.completed
                },
                {
                    name: 'Pending',
                    data: newData.pending
                },
                {
                    name: 'Assigned',
                    data: newData.assigned
                }
            ],
            xaxis: {
                categories: newData.dates
            }
        });
    } else {
        console.error('Bar chart instance is not available');
    }
}
function fetchvisitsperday() {
    var selectElement = document.getElementById("visitsperday");
    var selectedValue = selectElement.value;
    $.ajax({
        url: getVisitsPerDay,
        type: 'GET',
        data: { driverid: selectedValue },
        success: function(response) {
            var response = response.data;
            console.log(response);
            var newData = {
                completed: response.completed,
                pending: response.pending,
                assigned: response.assigned,
                dates: response.dates
            };

            // Update the bar chart with the new data
            updateBarChart(newData);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching batch counts:', error);
        }
    });
}


function campaigncounts(filter){ 
    var color;
    if(filter == 'completed'){
       color = '#38982c';
    }else if(filter == 'pending'){
       color = '#d46018';
    }else if(filter == 'abort'){
       color = '#e60000';
    }else{
       color = '';
    } 
    $.ajax({
        url: getCampaignPerformance, // Your API endpoint
        type: 'GET',
        data: { filter: filter },
        success: function(response) {
            updateBatchList(response.batches, color);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching batch counts:', error);
        }
    });
}

function updateBatchList(batches, color) { 
   // Hide the dropdown menu
   var dropdownMenu = document.getElementById('CampaignMenu');
    if (dropdownMenu) {
        dropdownMenu.classList.remove('show');
    }
    $('#batchList').empty(); 
    batches.forEach(function(batch, index) { 
    var listItem = `
       <li class="p-3 list-item d-flex flex-column align-items-start" data-batch-id="${batch.id}">
             <div class="d-flex justify-content-start align-items-center w-100">
                <div class="list-style-detail mr-2">
                   <p class="mb-0" style="color: ${color};">${batch.batch_no.charAt(0).toUpperCase() + batch.batch_no.slice(1)}</p>
                </div>
                <div class="list-style-action ml-auto">
                   <h6 class="font-weight-bold" style="color: ${color};" id="campaignnumbers">${batch.count}</h6>
                </div>
             </div>
             <div class="w-100">
                <progress style="accent-color: ${color}; width: 100%; height: 5px;" id="file" value="${batch.count}" max="${batch.batch_details_count}">${batch.count}%</progress>
             </div>
       </li>`;
    
    $('#batchList').append(listItem);
 });
}