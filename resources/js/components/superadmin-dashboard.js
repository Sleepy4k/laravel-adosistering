/**
 * Superadmin Dashboard Alpine.js Component
 * Extracted from inline script in home/user.blade.php (admin/superadmin section)
 *
 * Usage in Blade: x-data="superadminDashboard()"
 */

window.superadminDashboard = function () {
    return {
        searchTerm: '',
        selectedSensorStatus: '',
        selectedPumpStatus: '',

        filterCards() {
            const cards = document.querySelectorAll('.iot-card');
            let visibleCount = 0;

            cards.forEach((card) => {
                const sensorStatus = card.dataset.sensorStatus;
                const pumpStatus = card.dataset.pumpStatus;
                const userName = card.dataset.userName?.toLowerCase() || '';
                const iotName = card.dataset.iotName?.toLowerCase() || '';

                // Search filter
                const matchesSearch =
                    this.searchTerm === '' ||
                    userName.includes(this.searchTerm.toLowerCase()) ||
                    iotName.includes(this.searchTerm.toLowerCase());

                // Sensor status filter
                const matchesSensorStatus =
                    this.selectedSensorStatus === '' || sensorStatus === this.selectedSensorStatus;

                // Pump status filter
                const matchesPumpStatus =
                    this.selectedPumpStatus === '' || pumpStatus === this.selectedPumpStatus;

                // Show/hide card
                if (matchesSearch && matchesSensorStatus && matchesPumpStatus) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show/hide empty state
            const emptyState = document.getElementById('empty-state');
            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        },

        init() {
            this.$watch('searchTerm', () => this.filterCards());
            this.$watch('selectedSensorStatus', () => this.filterCards());
            this.$watch('selectedPumpStatus', () => this.filterCards());
        },
    };
};
