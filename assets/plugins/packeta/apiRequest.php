<?php
$apiUrl = "https://www.zasilkovna.cz/api/rest";
$apiPassword = '92283e7127768ce43d8acfa5ba4d1b29';

$packetData = [
    'number' => '123456',
    'name' => 'John',
    'surname' => 'Doe',
    'email' => 'example@packetatest.com',
    'phone' => '+420777123456',
    'addressId' => '9063',
    'cod' => '', // cash on delivery
    'value' => '145',
    'weight' => '2',
    'eshop' => 'Sklad'
];

$xml = new SimpleXMLElement('<createPacket/>');
$xml->addChild('apiPassword', $apiPassword);
$packetAttributes = $xml->addChild('packetAttributes');

foreach ($packetData as $key => $value) {
    $packetAttributes->addChild($key, htmlspecialchars($value));
}

$xmlBody = $xml->asXML();

$apiUrl = "https://www.zasilkovna.cz/api/rest";
$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/xml\r\n" .
            "Accept: application/xml\r\n",
        'content' => $xmlBody,
        'timeout' => 30, // Set timeout
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($apiUrl, false, $context);

// Check if response is valid
if ($response === false) {
    echo "❌ Error sending request!";
} else {
    // need to send back the response 
    $xmlArray = json_decode(json_encode($response), true);

    echo "Status: " . $xmlArray['status'] . "<br>"; // Output: ok
    echo "ID: " . $xmlArray['result']['id'] . "<br>"; // Output: 2676583867
    echo "Barcode: " . $xmlArray['result']['barcode'] . "<br>"; // Output: Z2676583867
    echo "Barcode Text: " . $xmlArray['result']['barcodeText'] . "<br>"; // Output: Z 267 6583 867

   // echo "✅ Response: " . htmlspecialchars($response);
}
