<?php

require_once 'FCVLogbookAPI.php';

// Configuration
$clientId = 'YOUR_CLIENT_ID';
$clientSecret = 'YOUR_CLIENT_SECRET';
$redirectUri = 'YOUR_REDIRECT_URL';
$authCode = 'YOUR_AUTHORIZATION_CODE'; // Obtain this through the authorization process

// Create an instance of the API client
$fcvApi = new FCVLogbookAPI($clientId, $clientSecret, $redirectUri, $authCode);

// Get access and refresh tokens using Basic Auth
echo "Getting access token using Basic Auth:\n";
$tokens = $fcvApi->getAccessTokenBasicAuth();
if (isset($tokens['access_token']) && isset($tokens['refresh_token'])) {
    echo "Access Token: " . $tokens['access_token'] . "\n";
    echo "Refresh Token: " . $tokens['refresh_token'] . "\n";

    // Use the access token to access the API
    echo "Fetching flights data:\n";
    $flights = $fcvApi->getFlights($tokens['access_token']);
    echo "(Only printing the first flight): \n";
    if (isset($flights['flights']) && count($flights['flights']) > 0) {
        print_r($flights['flights'][0]);
    } else {
        echo "No flights found.\n";
    }

    // Simulate access token expiration and refresh it
    echo "\nSimulating token expiration for 5 seconds...\n";
    sleep(5); // Simulate token expiration

    echo "Refreshing access token:\n";
    $newTokens = $fcvApi->refreshAccessTokenBasicAuth($tokens['refresh_token']);
    if (isset($newTokens['access_token'])) {
        echo "New Access Token: " . $newTokens['access_token'] . "\n";

        // Use the new access token to access the API
        echo "Fetching flights data with new token:\n";
        $flights = $fcvApi->getFlights($newTokens['access_token']);
        echo "(Only printing the first flight): \n";
        if (isset($flights['flights']) && count($flights['flights']) > 0) {
            print_r($flights['flights'][0]);
        } else {
            echo "No flights found.\n";
        }
    } else {
        echo "Failed to refresh access token.\n";
    }

    // Revoke the refresh token
    echo "\nRevoking the refresh token:\n";
    $revokeResponse = $fcvApi->revokeToken($tokens['refresh_token']);
    if (isset($revokeResponse['success'])) {
        echo "Token successfully revoked.\n";
    } else {
        echo "Failed to revoke token.\n";
    }
} else {
    echo "Failed to obtain access token using Basic Auth.\n";
}

