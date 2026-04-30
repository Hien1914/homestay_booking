document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined' || !window.dashboardChartsData) {
        return;
    }

    const {
        chartLabels,
        scatterRevenue,
        scatterBookings,
        chartTickLimit,
        bookingStatusLabels,
        bookingStatusData,
        bookingStatusColors,
    } = window.dashboardChartsData;

    const revenueBookingChart = document.getElementById('revenueBookingChart');
    if (revenueBookingChart) {
        new Chart(revenueBookingChart, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [
                    {
                        type: 'line',
                        label: 'Doanh thu',
                        data: scatterRevenue.map(d => d.y),
                        borderColor: '#1d4ed8',
                        backgroundColor: '#1d4ed8',
                        borderWidth: 2,
                        tension: 0.4,
                        yAxisID: 'yRevenue',
                    },
                    {
                        type: 'bar',
                        label: 'Lượng đặt phòng',
                        data: scatterBookings.map(d => d.y),
                        backgroundColor: '#f97316',
                        yAxisID: 'yBookings',
                        borderRadius: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            autoSkip: true,
                            maxTicksLimit: chartTickLimit ?? 7,
                            maxRotation: 0,
                            minRotation: 0,
                        },
                    },
                    yRevenue: {
                        type: 'linear',
                        beginAtZero: true,
                        position: 'left',
                        title: { display: true }
                    },
                    yBookings: {
                        type: 'linear',
                        beginAtZero: true,
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { precision: 0 },
                        title: { display: true }
                    },
                },
            },
        });
    }

    const bookingStatusChart = document.getElementById('bookingStatusChart');
    if (bookingStatusChart) {
        new Chart(bookingStatusChart, {
            type: 'pie',
            data: {
                labels: bookingStatusLabels,
                datasets: [{
                    data: bookingStatusData,
                    backgroundColor: bookingStatusColors ?? ['#facc15', '#22c55e', '#ef4444', '#6366f1'],
                    borderWidth: 0,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label(context) {
                                const total = context.dataset.data.reduce((sum, value) => sum + value, 0);
                                const percent = total > 0 ? ((context.raw / total) * 100).toFixed(1) : '0.0';
                                return `${context.label}: ${context.raw} (${percent}%)`;
                            },
                        },
                    },
                },
            },
        });
    }
});
