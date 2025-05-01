// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

document.getElementById('stackedChart').onclick = function (evt) {
    const chart = window.stackedChart;
    const points = chart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
    if (!points.length) return;

    const datasetIndex = points[0].datasetIndex;
    const index = points[0].index;

    const clickedDivision = chart.data.datasets[datasetIndex].label.replace('Division ', '');
    const clickedClass = chart.data.labels[index];

    // fetch(`/students/details?class=${encodeURIComponent(clickedClass)}&division=${encodeURIComponent(clickedDivision)}`)
    //     .then(res => res.json())
    //     .then(students => {
    //         showDrillDownChart(students, clickedClass, clickedDivision);
    //     });
};


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
    window.stackedChart = new Chart(ctx, {
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
