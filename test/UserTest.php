<?php
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $baseUrl = "http://localhost/Software-Engineering-Website/api";

    private function post($endpoint, $data)
    {
        $ch = curl_init("{$this->baseUrl}/{$endpoint}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        return json_decode(curl_exec($ch), true);
    }

    public function testRegisterNewUser()
    {
        $data = [
            "full_name" => "Test User",
            "email" => "testuser@example.com",
            "password" => "TestPassword123",
            "weight" => 160,
            "height" => 70,
            "bench_press" => 185,
            "experience" => "Beginner"
        ];

        $response = $this->post("register.php", $data);
        $this->assertIsArray($response);
        $this->assertArrayNotHasKey('error', $response);
    }

    public function testLoginUser()
    {
        $data = [
            "email" => "testuser@example.com",
            "password" => "TestPassword123"
        ];

        $response = $this->post("login.php", $data);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
    }

    public function testMatchmakingAccess()
    {
        session_start();
        $_SESSION['username'] = "testuser@example.com"; // Simulate session for test

        ob_start();
        include __DIR__ . '/../api/matchmaking.php';
        $output = ob_get_clean();
        $result = json_decode($output, true);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('matches', $result);
    }
}
