document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined' || !window.usersChartsData) {
        return;
    }

    const { genderLabels, genderData, ageLabels, ageData } = window.usersChartsData;

    const genderChart = document.getElementById('genderChart');
    if (genderChart) {
        new Chart(genderChart, {
            type: 'pie',
            data: {
                labels: genderLabels,
                datasets: [{
                    data: genderData,
                    backgroundColor: ['#0d6efd', '#e83e8c', '#6c757d'],
                    borderWidth: 0,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 1,
                plugins: { legend: { position: 'bottom' } },
            },
        });
    }

    const ageChart = document.getElementById('ageChart');
    if (ageChart) {
        new Chart(ageChart, {
            type: 'bar',
            data: {
                labels: ageLabels,
                datasets: [{
                    label: 'Số người dùng',
                    data: ageData,
                    backgroundColor: '#20c997',
                    borderRadius: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            },
        });
    }
});
