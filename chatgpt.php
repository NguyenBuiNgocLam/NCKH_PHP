<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Nhận dữ liệu từ client
$rawInput = file_get_contents("php://input");
$data = json_decode($rawInput, true);
$question = trim(strip_tags($data['question'] ?? ''));

if (empty($question)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing question data']);
    exit;
}

$groqApiKey = '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'; 
$groqUrl = 'https://api.groq.com/openai/v1/chat/completions';

$payload = [
    'model' => 'llama3-70b-8192',
    'messages' => [
        ['role' => 'user', 'content' => "Reply shortly in English, no explanations, no conversation: \"$question\""]
    ],
    'temperature' => 0.7
];

$ch = curl_init($groqUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $groqApiKey
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo json_encode(['status' => 'error', 'message' => 'Groq API error: ' . $httpCode, 'response' => $response]);
    exit;
}

$data = json_decode($response, true);
$answer = $data['choices'][0]['message']['content'] ?? 'No response.';

echo json_encode([
    'status' => 'success',
    'answer' => trim($answer)
]);
