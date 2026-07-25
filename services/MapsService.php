<?php
/**
 * services/MapsService.php
 * Server-side wrapper for Google Maps / Geocoding / Distance Matrix APIs.
 * All HTTP calls use file_get_contents with stream context (no cURL dependency).
 */
require_once __DIR__ . '/../config/constants.php';
if (file_exists(__DIR__ . '/../config/app.php')) require_once __DIR__ . '/../config/app.php';

class MapsService {

    private string $apiKey;
    private string $baseUrl = 'https://maps.googleapis.com/maps/api/';

    public function __construct() {
        $this->apiKey = defined('GOOGLE_MAPS_API_KEY') ? GOOGLE_MAPS_API_KEY : '';
    }

    /**
     * Geocode a text address to lat/lng.
     *
     * @param string $address
     * @return array|null ['lat' => float, 'lng' => float, 'formatted_address' => string]
     */
    public function geocode(string $address): ?array {
        $url  = $this->baseUrl . 'geocode/json?' . http_build_query(['address' => $address, 'key' => $this->apiKey]);
        $data = $this->fetch($url);
        if (!$data || $data['status'] !== 'OK') return null;
        $loc = $data['results'][0]['geometry']['location'];
        return [
            'lat'               => $loc['lat'],
            'lng'               => $loc['lng'],
            'formatted_address' => $data['results'][0]['formatted_address'],
        ];
    }

    /**
     * Reverse geocode lat/lng to a human-readable address.
     */
    public function reverseGeocode(float $lat, float $lng): ?string {
        $url  = $this->baseUrl . 'geocode/json?' . http_build_query(['latlng' => "$lat,$lng", 'key' => $this->apiKey]);
        $data = $this->fetch($url);
        return ($data && $data['status'] === 'OK') ? $data['results'][0]['formatted_address'] ?? null : null;
    }

    /**
     * Get distance and duration between two points.
     *
     * @return array|null ['distance_km' => float, 'duration_min' => int, 'text_distance' => string]
     */
    public function getDistance(string $origin, string $destination): ?array {
        $url  = $this->baseUrl . 'distancematrix/json?' . http_build_query([
            'origins'      => $origin,
            'destinations' => $destination,
            'key'          => $this->apiKey,
            'units'        => 'metric',
        ]);
        $data = $this->fetch($url);
        if (!$data || $data['status'] !== 'OK') return null;
        $element = $data['rows'][0]['elements'][0] ?? null;
        if (!$element || $element['status'] !== 'OK') return null;
        return [
            'distance_km'  => round($element['distance']['value'] / 1000, 2),
            'duration_min' => (int)ceil($element['duration']['value'] / 60),
            'text_distance'=> $element['distance']['text'],
        ];
    }

    /**
     * Build a static map URL for embedding as an <img> tag.
     *
     * @param float  $lat
     * @param float  $lng
     * @param int    $zoom  1-21
     * @param string $size  WxH e.g. '600x300'
     */
    public function staticMapUrl(float $lat, float $lng, int $zoom = 15, string $size = '600x300'): string {
        return $this->baseUrl . 'staticmap?' . http_build_query([
            'center'  => "$lat,$lng",
            'zoom'    => $zoom,
            'size'    => $size,
            'markers' => "color:red|$lat,$lng",
            'key'     => $this->apiKey,
            'style'   => 'feature:all|element:geometry|color:0x1a1a2e',
        ]);
    }

    /* ── Private fetch helper ───────────────────────────── */

    private function fetch(string $url): ?array {
        $ctx    = stream_context_create(['http' => ['timeout' => 5, 'ignore_errors' => true]]);
        $result = @file_get_contents($url, false, $ctx);
        if ($result === false) return null;
        return json_decode($result, true);
    }
}
