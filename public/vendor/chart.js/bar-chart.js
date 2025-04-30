// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// function number_format(number, decimals, dec_point, thousands_sep) {
//     // *     example: number_format(1234.56, 2, ',', ' ');
//     // *     return: '1 234,56'
//     number = (number + '').replace(',', '').replace(' ', '');
//     var n = !isFinite(+number) ? 0 : +number,
//         prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
//         sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
//         dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
//         s = '',
//         toFixedFix = function (n, prec) {
//             var k = Math.pow(10, prec);
//             return '' + Math.round(n * k) / k;
//         };
//     // Fix for IE parseFloat(0.55).toFixed(0) = 0;
//     s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
//     if (s[0].length > 3) {
//         s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
//     }
//     if ((s[1] || '').length < prec) {
//         s[1] = s[1] || '';
//         s[1] += new Array(prec - s[1].length + 1).join('0');
//     }
//     return s.join(dec);
// }

// Bar Chart Example
// var ctx = document.getElementById("myBarChart");
// var myBarChart = new Chart(ctx, {
//     type: 'bar',
//     data: {
//         labels: ["Class 1", "Class 2", "Class 3", "Class 4", "Class 5", "Class 6"],
//         datasets: [{
//             label: "Revenue",
//             backgroundColor: "#4e73df",
//             hoverBackgroundColor: "#2e59d9",
//             borderColor: "#4e73df",
//             data: [4215, 5312, 6251, 7841, 9821, 14984],
//         }],
//     },
//     options: {
//         maintainAspectRatio: false,
//         layout: {
//             padding: {
//                 left: 10,
//                 right: 25,
//                 top: 25,
//                 bottom: 0
//             }
//         },
//         scales: {
//             xAxes: [{
//                 time: {
//                     unit: 'month'
//                 },
//                 gridLines: {
//                     display: false,
//                     drawBorder: false
//                 },
//                 ticks: {
//                     maxTicksLimit: 6
//                 },
//                 maxBarThickness: 25,
//             }],
//             yAxes: [{
//                 ticks: {
//                     min: 0,
//                     max: 15000,
//                     maxTicksLimit: 5,
//                     padding: 10,
//                     // Include a dollar sign in the ticks
//                     callback: function (value, index, values) {
//                         return '$' + number_format(value);
//                     }
//                 },
//                 gridLines: {
//                     color: "rgb(234, 236, 244)",
//                     zeroLineColor: "rgb(234, 236, 244)",
//                     drawBorder: false,
//                     borderDash: [2],
//                     zeroLineBorderDash: [2]
//                 }
//             }],
//         },
//         legend: {
//             display: false
//         },
//         tooltips: {
//             titleMarginBottom: 10,
//             titleFontColor: '#6e707e',
//             titleFontSize: 14,
//             backgroundColor: "rgb(255,255,255)",
//             bodyFontColor: "#858796",
//             borderColor: '#dddfeb',
//             borderWidth: 1,
//             xPadding: 15,
//             yPadding: 15,
//             displayColors: false,
//             caretPadding: 10,
//             callbacks: {
//                 label: function (tooltipItem, chart) {
//                     var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
//                     return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
//                 }
//             }
//         },
//     }
// });

async function fetchDashBoardGraphData() {

    try {
        const response = await fetch('admin/show-chart');
        const data = await response.json();

        if (typeof data === 'object') {
            drawDashBoardGraph(data);
        }
    } catch (error) {
        console.log(error);
    }

}

function drawDashBoardGraph(data) {
    const classNames = Object.keys(data);
    const divisions = Array.from(new Set(
        classNames.flatMap(cls => Object.keys(data[cls]))
    ));

    const colors = {
        'A': '#4CAF50',
        'B': '#2196F3',
        'C': '#FF9800',
        'D': '#E91E63',
        'E': '#9C27B0',
        'F': '#00BCD4',
    };

    const datasets = divisions.map(division => {

        return {
            label: division,
            data: classNames.map(cls => data[cls][division] || 0),
            backgroundColor: colors[division] || '#9e9e9e',
            stack: 'combined'
        };
    });

    const ctx = document.getElementById('stackedChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: classNames,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Students per Class (Stacked by Division)',
                    font: {
                        size: 18
                    }
                },
                legend: {
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                },
                datalabels: {
                    display: true,
                    color: 'white'
                }
            },
            scales: {
                x: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Classes'
                    }
                },
                y: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Number of Students'
                    },
                    beginAtZero: true
                }
            }
        }
    });
}


fetchDashBoardGraphData();
