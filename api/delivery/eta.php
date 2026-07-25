<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../services/TrackingService.php';

$dist = (float)($_GET['distance'] ?? 5.0);
$ts = new TrackingService();
$etaMin = $ts->estimateETA($dist);
echo json_encode(['success' => true, 'eta_minutes' => $etaMin]);
