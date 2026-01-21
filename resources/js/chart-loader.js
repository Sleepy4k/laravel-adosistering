/**
 * Chart.js Lazy Loader with Tree-Shaking
 * Only imports necessary Chart.js components to reduce bundle size
 * ~187KB CDN → ~50KB optimized bundle
 */

// Import only the components we need (tree-shaking)
import {
    Chart,
    LineController,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
    Filler,
    Tooltip,
    Legend
} from 'chart.js';

// Register only the components we use
Chart.register(
    LineController,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
    Filler,
    Tooltip,
    Legend
);

/**
 * Initialize charts for statistik page
 * @param {Array} bloksData - Array of blok data with chart information
 */
export function initializeCharts(bloksData) {
    if (!bloksData || !Array.isArray(bloksData)) {
        console.warn('Chart data not provided or invalid');
        return;
    }

    bloksData.forEach(blok => {
        createKelembabanChart(blok);
        createPenggunaanAirChart(blok);
    });
}

/**
 * Create soil moisture chart
 */
function createKelembabanChart(blok) {
    const ctx = document.getElementById(`chartKelembaban${blok.id}`);
    if (!ctx) return;

    const labels = blok.chart_kelembaban.map(item => item.waktu);
    const data = blok.chart_kelembaban.map(item => item.nilai);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Kelembaban Tanah',
                data: data,
                borderColor: '#67B744',
                backgroundColor: 'rgba(103, 183, 68, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#67B744',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + '%';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

/**
 * Create water usage chart
 */
function createPenggunaanAirChart(blok) {
    const ctx = document.getElementById(`chartPenggunaanAir${blok.id}`);
    if (!ctx) return;

    const labels = blok.chart_penggunaan_air.map(item => item.waktu);
    const data = blok.chart_penggunaan_air.map(item => item.nilai);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Penggunaan Air',
                data: data,
                borderColor: '#0F92F0',
                backgroundColor: 'rgba(15, 146, 240, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#0F92F0',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' Liter';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value;
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Expose to window for browser dynamic import compatibility
if (typeof window !== 'undefined') {
    window.ChartModule = { initializeCharts };
}

export default { initializeCharts };
