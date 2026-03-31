<?php
// Set header to return JSON to our frontend
header('Content-Type: application/json');


// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userInput = $_POST['prompt'] ?? '';
   
    if (empty(trim($userInput))) {
        echo json_encode(['error' => 'Prompt cannot be empty']);
        exit;
    }


    // Replace with your actual Gemini API Key
    $apiKey = 'AIzaSyB4of80WqG2wWN-v5ia-d4aZEP_0uphJQk';
   
    // We are using the gemini-2.5-flash model for fast, standard text generation
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey;


    // $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent?key=' . $apiKey;
    // $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-pro-preview:generateContent?key=' . $apiKey;


    // Format the payload exactly as the Gemini API expects
    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $userInput]
                ]
            ]
        ]
    ];


    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));


    // Execute the request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);


    // Handle the response
    if ($httpCode === 200) {
        $responseData = json_decode($response, true);
       
        // Traverse the JSON response to extract the generated text
        $generatedText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'No response generated.';
       
        echo json_encode(['reply' => $generatedText]);
    } else {
        // If something goes wrong, return the error
        echo json_encode(['error' => 'API Error: ' . $response]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>

