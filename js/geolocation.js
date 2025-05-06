function initializeGeolocation() {
    const storeSelect = document.querySelector('select[name="store_id"]');
    if (!storeSelect) return;

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            const { latitude, longitude } = position.coords;

            // Mock store locations (replace with real data in production)
            const stores = [
                { id: 1, name: "Domino's Dhaka", lat: 23.8103, lon: 90.4125 },
                { id: 2, name: "Domino's Chittagong", lat: 22.3569, lon: 91.7832 }
            ];

            // Find the nearest store
            let nearestStore = stores[0];
            let minDistance = calculateDistance(latitude, longitude, stores[0].lat, stores[0].lon);

            stores.forEach(store => {
                const distance = calculateDistance(latitude, longitude, store.lat, store.lon);
                if (distance < minDistance) {
                    minDistance = distance;
                    nearestStore = store;
                }
            });

            // Auto-select the nearest store
            storeSelect.value = nearestStore.id;
            showToast(`Nearest store selected: ${nearestStore.name}`);
        }, error => {
            console.error('Geolocation error:', error);
            showToast('Unable to detect location. Please select a store manually.', 'error');
        });
    } else {
        showToast('Geolocation is not supported by your browser.', 'error');
    }
}

// Haversine formula to calculate distance between two points
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Earth's radius in km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}