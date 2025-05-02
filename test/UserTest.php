<?php
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $baseUrl = "http://localhost/Software-Engineering-Website/api";
    private static $createdEmail = ''; // shared across tests
    private static $testPassword = 'testpassword123';

    public function testGet_UserList()
    {
        $url = $this->baseUrl . "/users.php";
        $response = $this->makeGetRequest($url);
        $this->assertEquals(200, $response['status']);
    }

    public function testPost_CreateUser()
    {
        $url = $this->baseUrl . "/signup.php";
        $randomEmail = 'testuser' . rand(1, 99999) . '@example.com';
        self::$createdEmail = $randomEmail; // save email for login test

        $data = [
            'full_name' => 'Test User',
            'email' => $randomEmail,
            'password' => self::$testPassword,
            'weight' => 180,
            'height' => 70,
            'bench_press' => 200,
            'experience' => 'Beginner'
        ];

        $response = $this->makePostRequest($url, $data);
        $this->assertEquals(201, $response['status']);
    }

    public function testPost_LoginUser()
    {
        $postData = json_encode([
            'email' => '1234@test.com',
            'password' => '1234567890'
        ]);

        $options = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/json\r\n",
                'content' => $postData
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($this->baseUrl . '/login.php', false, $context);
        $status_code = explode(' ', $http_response_header[0])[1];

        $this->assertEquals(201, (int)$status_code);
    }


    public function testPost_FailedLogin()
    {
        $url = $this->baseUrl . "/login.php";
        $data = [
            'email' => 'nonexistentuser@example.com',
            'password' => 'wrongpassword'
        ];

        $response = $this->makePostRequest($url, $data);
        $this->assertEquals(401, $response['status']);
    }

    private function makeGetRequest($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ['status' => $status];
    }

    private function makePostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ['status' => $status];
    }
}
