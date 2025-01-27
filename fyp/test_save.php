<?php
$data = [
    "assetId" => "TestAsset123",
    "timeCollected" => "2024-11-26 10:00:00",
    "timeToReturn" => "2024-11-27 10:00:00",
    "location" => "Test Location",
    "quantity" => 5
];

$ch = curl_init('http://localhost/save_barcode.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
