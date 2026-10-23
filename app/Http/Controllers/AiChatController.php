<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    public function chat(Request $request)
    {
        $message = trim($request->input('message', ''));

        if (empty($message)) {
            return response()->json([
                'reply' => 'Please type a question or request for assistance.',
            ]);
        }

        $apiKey = env('GEMINI_API_KEY');
        $model = env('GEMINI_MODEL', 'gemini-2.5-flash');

        if ($apiKey && !str_starts_with($apiKey, 'AQ.')) {
            try {
                $systemPrompt = "You are Servi, the helpful, polite AI virtual assistant for Barangay San Agustin, City of San Jose del Monte (SJDM), Bulacan. "
                    . "You assist residents with Barangay services, requirements, schedules, clearances, and emergency information. "
                    . "Keep answers friendly, clear, helpful, and concise (2-4 sentences where possible). "
                    . "Key info: Barangay Office is open Mon-Fri 8:00 AM - 5:00 PM. Emergency hotlines: Medical: 911, Police: 117, Fire: 160. "
                    . "Clearances require valid government ID and proof of residency.";

                $response = Http::timeout(10)->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                    'system_instruction' => [
                        'parts' => [
                            ['text' => $systemPrompt]
                        ]
                    ],
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $message]
                            ]
                        ]
                    ],
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $replyText = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($replyText) {
                        return response()->json([
                            'reply' => trim($replyText),
                            'source' => 'gemini',
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Gemini API call failed: ' . $e->getMessage());
            }
        }

        // Intelligent local knowledge base fallback
        $reply = $this->getLocalBotReply($message);

        return response()->json([
            'reply' => $reply,
            'source' => 'local',
        ]);
    }

    private function getLocalBotReply(string $message): string
    {
        $msg = strtolower($message);

        if (str_contains($msg, 'clearance')) {
            return 'To secure a Barangay Clearance, visit the Barangay Hall from Mon-Fri (8:00 AM - 5:00 PM). Please bring: 1 valid ID, Community Tax Certificate (Cedula), and ₱50 processing fee.';
        }

        if (str_contains($msg, 'indigency') || str_contains($msg, 'certificate of indigency')) {
            return 'Certificate of Indigency is issued free of charge for medical, scholarship, or legal assistance. Please present a valid ID and proof of low household income.';
        }

        if (str_contains($msg, 'residency') || str_contains($msg, 'certificate of residency')) {
            return 'Certificate of Residency requires: Valid ID with address or utility bill showing at least 6 months of residency in Barangay San Agustin.';
        }

        if (str_contains($msg, 'id') || str_contains($msg, 'barangay id')) {
            return 'To apply for a Barangay ID, complete the Resident Registration on this portal or visit our records desk with 1 valid ID and 1x1 photo.';
        }

        if (str_contains($msg, 'business') || str_contains($msg, 'permit')) {
            return 'Business Clearance requirements: DTI or SEC registration, Lease contract or proof of ownership, and Fire Safety Inspection certificate.';
        }

        if (str_contains($msg, 'emergency') || str_contains($msg, 'hotline') || str_contains($msg, 'police') || str_contains($msg, 'fire') || str_contains($msg, 'ambulance')) {
            return '🚨 24/7 Emergency Hotlines: Medical / Ambulance: 911 | Police: 117 | Fire Station: 160 | Barangay San Agustin Desk: (044) 123-4567.';
        }

        if (str_contains($msg, 'health') || str_contains($msg, 'doctor') || str_contains($msg, 'clinic') || str_contains($msg, 'vaccin')) {
            return 'The Barangay Health Center is open Monday to Friday, 8:00 AM to 5:00 PM. Immunization is every Wednesday morning, and free prenatal check-ups every Thursday.';
        }

        if (str_contains($msg, 'job') || str_contains($msg, 'trabaho') || str_contains($msg, 'career')) {
            return 'You can check local barangay openings in the Jobs tab of your Resident Hub, or visit the Barangay PESO desk for government livelihood assistance.';
        }

        if (str_contains($msg, 'scholar') || str_contains($msg, 'school') || str_contains($msg, 'allowance')) {
            return 'Barangay San Agustin offers financial educational assistance for enrolled senior high and college students. Check the Scholar tab in your dashboard!';
        }

        if (str_contains($msg, 'relief') || str_contains($msg, 'calamity') || str_contains($msg, 'tulong') || str_contains($msg, 'food pack')) {
            return 'For disaster assistance and relief goods, you can submit an assistance request directly through your Resident Dashboard under Disaster Relief.';
        }

        if (str_contains($msg, 'hello') || str_contains($msg, 'hi') || str_contains($msg, 'hey') || str_contains($msg, 'kamusta')) {
            return "Magandang araw! I'm Servi, your Barangay San Agustin digital assistant. How may I assist you today? You can ask about clearances, health services, jobs, or emergency hotlines.";
        }

        if (str_contains($msg, 'hours') || str_contains($msg, 'oras') || str_contains($msg, 'schedule')) {
            return 'Barangay Hall operating hours: Monday through Friday, 8:00 AM to 5:00 PM (except holidays). Emergency services are available 24/7.';
        }

        return "I'm Servi, Barangay San Agustin's digital assistant. You can ask me about barangay clearances, certificate of residency or indigency, health center services, job applications, or emergency hotlines!";
    }
}
