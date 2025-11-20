<?php
// create_order.php
// This script receives a phone number, creates a payment order,
// and returns a JSON response.

require 'config.php'; // Includes your API key

// 1. SET THE URL FOR YOUR SUCCESS PAGE
// This is where ZenoPay will send the user AFTER they pay.
// Replace 'https://your-domain.com/success.html' with your actual link.
$redirect_url = 'https://upwiru.site/success.html';


// 2. READ THE PHONE NUMBER FROM THE FRONTEND
// We read the JSON data sent from the <script> in salespage.html
$input = json_decode(file_get_contents('php://input'), true);
$phone_number = $input['phone'] ?? null;

// Basic validation
if (empty($phone_number) || strlen($phone_number) < 9) {
    // Send an error response back to the JavaScript
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid phone number.']);
    exit;
}

// 3. PREPARE THE ORDER DATA FOR ZENOCO-PAY
$orderData = [
    'order_id'    => uniqid('zeno-', true), // Create a unique ID
    'buyer_email' => 'customer@example.com',  // Optional: You can add this field to your form
    'buyer_name'  => 'Video Customer',        // Optional: You can add this field to your form
    'buyer_phone' => $phone_number,           // The phone number from the form
    'amount'      => 1000,                    // The amount in TZS
    'redirect_url' => $redirect_url,          // The all-important success page URL
    // 'webhook_url' => '', // We don't need this anymore
];

// 4. SEND THE REQUEST TO ZENOPAY
$ch = curl_init(ZP_BASE_URL . '/mobile_money_tanzania');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'x-api-key: ' . ZP_API_KEY,
    ],
    CURLOPT_POSTFIELDS     => json_encode($orderData),
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 5. SEND A RESPONSE BACK TO THE FRONTEND
header('Content-Type: application/json');

if ($httpCode !== 200) {
    // ZenoPay server returned an error
    echo json_encode(['status' => 'error', 'message' => 'API request failed.', 'details' => $response]);
    exit;
}

$data = json_decode($response, true);

if (!empty($data['status']) && $data['status'] === 'success') {
    // SUCCESS! The USSD push is being sent.
    echo json_encode(['status' => 'success', 'order_id' => $data['order_id']]);
} else {
    // ZenoPay returned a 'fail' status
    echo json_encode(['status' => 'error', 'message' => $data['message'] ?? 'Unknown error from ZenoPay.']);
}

?>