<?php

class FCVLogbookAPI
{
    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;
    private string $authCode;

    public function __construct(string $clientId, string $clientSecret, string $redirectUri, string $authCode)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->authCode = $authCode;
    }

    // Function to make HTTP requests
    private function makeRequest(string $url, string $method = 'GET', $headers = [], $data = [])
    {
        $options = [
            'http' => [
                'header' => $headers,
                'method' => $method,
            ]
        ];
        if ($method === 'POST') {
            $options['http']['content'] = http_build_query($data);
        }
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        return json_decode($response, true);
    }

    // Exchange authorization code for access token and refresh token using Basic Auth
    public function getAccessTokenBasicAuth()
    {
        $url = 'https://www.flightcrewview2.com/logbook/api/token/';
        $auth = base64_encode("{$this->clientId}:{$this->clientSecret}");
        $headers = [
            'Authorization: Basic ' . $auth,
            'Content-Type: application/x-www-form-urlencoded'
        ];
        $data = [
            'grant_type' => 'authorization_code',
            'code' => $this->authCode,
            'redirect_uri' => $this->redirectUri
        ];
        return $this->makeRequest($url, 'POST', $headers, $data);
    }

    // Refresh the access token using Basic Auth
    public function refreshAccessTokenBasicAuth(string $refreshToken)
    {
        $url = 'https://www.flightcrewview2.com/logbook/api/token/';
        $auth = base64_encode("{$this->clientId}:{$this->clientSecret}");
        $headers = [
            'Authorization: Basic ' . $auth,
            'Content-Type: application/x-www-form-urlencoded'
        ];
        $data = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken
        ];
        return $this->makeRequest($url, 'POST', $headers, $data);
    }

    // Access the flights endpoint
    public function getFlights(string $accessToken)
    {
        $date = '2024-05-01 12:34:56'; // Example date
        $url = 'https://www.flightcrewview2.com/logbook/api/flights/?start_datetime_utc=' . urlencode($date);
        $headers = ["Authorization: Bearer $accessToken"];
        return $this->makeRequest($url, 'GET', $headers);
    }

    // Revoke a refresh token
    public function revokeToken(string $refreshToken)
    {
        $url = 'https://www.flightcrewview2.com/logbook/api/revokeToken/';
        $auth = base64_encode("{$this->clientId}:{$this->clientSecret}");
        $headers = [
            'Authorization: Basic ' . $auth,
            'Content-Type: application/x-www-form-urlencoded'
        ];
        $data = ['refreshToken' => $refreshToken];
        return $this->makeRequest($url, 'POST', $headers, $data);
    }
}
