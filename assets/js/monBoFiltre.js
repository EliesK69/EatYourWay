document.addEventListener('DOMContentLoaded', function() {
    const specialtyInput = document.querySelector('input[name="specialty"]');
    const cityInput = document.querySelector('input[name="city"]');
    const rows = document.querySelectorAll('tbody tr');

    function filterRows() {
        const specialtyFilter = specialtyInput.value.toLowerCase();
        const cityFilter = cityInput.value.toLowerCase();

        rows.forEach(row => {
            const specialty = row.getAttribute('data-specialty').toLowerCase();
            const city = row.getAttribute('data-city').toLowerCase();
            const matchesSpecialty = specialtyFilter === '' || specialty.includes(specialtyFilter);
            const matchesCity = cityFilter === '' || city.includes(cityFilter);
            
            if (matchesSpecialty && matchesCity) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    specialtyInput.addEventListener('input', filterRows);
    cityInput.addEventListener('input', filterRows);
});