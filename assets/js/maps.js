/**
 * maps.js — FoodHub Google Maps Integration
 * Provides helpers for: restaurant map pins, delivery tracking map,
 * address autocomplete, and delivery zone drawing.
 *
 * Requires: Google Maps JS API loaded with &libraries=places,drawing
 * Usage:    window.FoodHubMaps.initTrackingMap(lat, lng, riderLat, riderLng)
 *           window.FoodHubMaps.initRestaurantMap(lat, lng)
 *           window.FoodHubMaps.initAddressAutocomplete('inputId')
 */

window.FoodHubMaps = (() => {

    const DEFAULT_CENTER = { lat: 20.5937, lng: 78.9629 }; // India center
    const MARKER_COLORS  = { restaurant: '#f7931e', rider: '#00b09b', customer: '#6c5ce7' };

    /* ── Shared map style (dark theme) ─────────────────── */
    const DARK_STYLE = [
        { elementType: 'geometry',         stylers: [{ color: '#1a1a2e' }] },
        { elementType: 'labels.text.fill', stylers: [{ color: '#8a8a8a' }] },
        { elementType: 'labels.text.stroke',stylers:[{ color: '#1a1a2e' }] },
        { featureType: 'road',             elementType: 'geometry', stylers: [{ color: '#2d2d44' }] },
        { featureType: 'road',             elementType: 'geometry.stroke', stylers: [{ color: '#1a1a2e' }] },
        { featureType: 'water',            elementType: 'geometry', stylers: [{ color: '#0d0d1a' }] },
        { featureType: 'poi',              stylers: [{ visibility: 'off' }] },
        { featureType: 'transit',          stylers: [{ visibility: 'off' }] },
    ];

    /* ── Create SVG marker icon ─────────────────────────── */
    function svgMarker(color) {
        return {
            path: 'M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z',
            fillColor: color, fillOpacity: 1, strokeColor: '#fff',
            strokeWeight: 1.5, scale: 2,
            anchor: new google.maps.Point(12, 22)
        };
    }

    /* ── Restaurant detail page map ─────────────────────── */
    function initRestaurantMap(lat, lng, restaurantName = 'Restaurant') {
        const el = document.getElementById('restaurantMap');
        if (!el || typeof google === 'undefined') return;

        const map = new google.maps.Map(el, {
            center: { lat: parseFloat(lat), lng: parseFloat(lng) },
            zoom: 15, styles: DARK_STYLE,
            disableDefaultUI: true,
            zoomControl: true,
        });

        new google.maps.Marker({
            position: { lat: parseFloat(lat), lng: parseFloat(lng) },
            map, title: restaurantName,
            icon: svgMarker(MARKER_COLORS.restaurant)
        });
    }

    /* ── Live delivery tracking map ─────────────────────── */
    function initTrackingMap(restLat, restLng, custLat, custLng) {
        const el = document.getElementById('trackingMap');
        if (!el || typeof google === 'undefined') return;

        const map = new google.maps.Map(el, {
            center: { lat: parseFloat(custLat), lng: parseFloat(custLng) },
            zoom: 13, styles: DARK_STYLE,
            disableDefaultUI: true, zoomControl: true,
        });

        // Restaurant marker
        new google.maps.Marker({ position: { lat: parseFloat(restLat), lng: parseFloat(restLng) }, map, title: 'Restaurant', icon: svgMarker(MARKER_COLORS.restaurant) });
        // Customer marker
        new google.maps.Marker({ position: { lat: parseFloat(custLat), lng: parseFloat(custLng) }, map, title: 'Your Location', icon: svgMarker(MARKER_COLORS.customer) });

        // Rider marker (initially at restaurant, updates via polling)
        const riderMarker = new google.maps.Marker({ position: { lat: parseFloat(restLat), lng: parseFloat(restLng) }, map, title: 'Delivery Rider', icon: svgMarker(MARKER_COLORS.rider) });

        // Route line
        new google.maps.Polyline({
            path: [
                { lat: parseFloat(restLat), lng: parseFloat(restLng) },
                { lat: parseFloat(custLat), lng: parseFloat(custLng) }
            ],
            map, strokeColor: MARKER_COLORS.rider, strokeOpacity: 0.7, strokeWeight: 3,
            geodesic: true
        });

        return { map, riderMarker };
    }

    /* ── Update rider marker position ──────────────────── */
    function updateRiderPosition(marker, lat, lng) {
        if (!marker) return;
        marker.setPosition(new google.maps.LatLng(parseFloat(lat), parseFloat(lng)));
    }

    /* ── Address autocomplete ───────────────────────────── */
    function initAddressAutocomplete(inputId) {
        if (typeof google === 'undefined') return;
        const input = document.getElementById(inputId);
        if (!input) return;
        const ac = new google.maps.places.Autocomplete(input, { types: ['address'] });
        ac.addListener('place_changed', () => {
            const place = ac.getPlace();
            const lat   = place.geometry?.location?.lat();
            const lng   = place.geometry?.location?.lng();
            if (lat) document.getElementById(inputId + '_lat').value = lat;
            if (lng) document.getElementById(inputId + '_lng').value = lng;
        });
    }

    /* ── Delivery zone drawing (admin/restaurant) ────────── */
    function initZoneDrawing(canvasId) {
        const el = document.getElementById(canvasId);
        if (!el || typeof google === 'undefined') return;

        const map = new google.maps.Map(el, {
            center: DEFAULT_CENTER, zoom: 12, styles: DARK_STYLE,
            disableDefaultUI: true, zoomControl: true,
        });

        const drawMgr = new google.maps.drawing.DrawingManager({
            drawingMode: google.maps.drawing.OverlayType.CIRCLE,
            drawingControl: true,
            drawingControlOptions: { drawingModes: ['circle', 'polygon'] },
            circleOptions: { fillColor: '#6c5ce7', fillOpacity: 0.3, strokeColor: '#a29bfe', strokeWeight: 2, editable: true },
            polygonOptions: { fillColor: '#6c5ce7', fillOpacity: 0.3, strokeColor: '#a29bfe', strokeWeight: 2, editable: true },
        });
        drawMgr.setMap(map);

        google.maps.event.addListener(drawMgr, 'overlaycomplete', e => {
            const hidden = document.getElementById('zone_data');
            if (e.type === google.maps.drawing.OverlayType.CIRCLE && hidden) {
                hidden.value = JSON.stringify({ type: 'circle', radius: e.overlay.getRadius(), center: { lat: e.overlay.getCenter().lat(), lng: e.overlay.getCenter().lng() } });
            }
        });
    }

    return { initRestaurantMap, initTrackingMap, updateRiderPosition, initAddressAutocomplete, initZoneDrawing };
})();
