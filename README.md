
# Flight Crew View Logbook API Example

This repository contains a simple example of how to interact with the Flight Crew View Logbook API. The provided PHP script demonstrates obtaining access and refresh tokens, accessing flight data, and revoking a token using OAuth 2.0.

## Files

- `FCVLogbookAPI.php`: A PHP class that encapsulates the API interactions.
- `test.php`: A script to test the API interactions using the `FCVLogbookAPI` class.

## Prerequisites

1. PHP installed on your system.
2. An active Flight Crew View Logbook API account.
3. Client ID, Client Secret, and Redirect URI obtained from your Flight Crew View Logbook API account.

## Usage

### Step 1: Clone the repository

Clone this repository to your local machine.

```sh
git clone https://github.com/yourusername/FCVLogbookAPI.git
cd FCVLogbookAPI
```

### Step 2: Update Configuration

Open `test.php` and update the configuration variables with your actual Client ID, Client Secret, Redirect URI, and Authorization Code.

These can be found in the [FCV Logbook API Client Portal](https://flightcrewview2.com/logbook/logbookapiclientportal/)

```php
// Configuration
$clientId = 'YOUR_CLIENT_ID';
$clientSecret = 'YOUR_CLIENT_SECRET';
$redirectUri = 'YOUR_REDIRECT_URI';
$authCode = 'YOUR_AUTHORIZATION_CODE'; // Obtain this through the authorization process
```

### Step 3: Generate Authorization Code

1. Follow the authorization process to generate an Authorization Code. 
2. You can find the url to the user authorization page in step 5 in "How to Access the API" in the [FCV Logbook API Client Portal](https://flightcrewview2.com/logbook/logbookapiclientportal/).
3. Go to that url and enter the test Passkey (found in that same client portal under the Testing section).
4. It will redirect you to your Redirect URI along with a "&code=YOUR_AUTHORIZATION CODE"
5. Copy that Authorization Code and enter it into this test file.
  - NOTE: Authorization Codes have a 5 minute lifetime and should be used immediately and discarded.
  - TESTING ONLY: Authorization Codes generated with the test Passkey will have a 1 hour lifetime.

### Step 4: Run the Test Script

1. Ensure you have set the Client ID, Client Secret, Redirect URI, and Authorization Code in `test.php`.
2. Run the test script within 5 minutes of generating the authorization code.

```sh
php test.php
```

### Expected Output

The script will:

1. Obtain access and refresh tokens using Basic Auth.
2. Use the access token to fetch flight data and print the first flight.
3. Simulate token expiration and refresh the access token.
4. Fetch and print flight data again using the new access token.
5. Revoke the refresh token and print the response.

### Example Output

```
Getting access token using Basic Auth:
Access Token: YOUR_ACCESS_TOKEN
Refresh Token: YOUR_REFRESH_TOKEN
Fetching flights data:
(Only printing the first flight): 
Array
(
    [fcv_flight_id] => FCV_FLT_ID_8572488_TEST
    [flight_number] => 2748
    [is_deadhead] => 0
    ...
)
Simulating token expiration for 5 seconds...
Refreshing access token:
New Access Token: YOUR_NEW_ACCESS_TOKEN
Fetching flights data with new token:
(Only printing the first flight): 
Array
(
    [fcv_flight_id] => FCV_FLT_ID_8572488_TEST
    [flight_number] => 2748
    [is_deadhead] => 0
    ...
)
Revoking the refresh token:
Array
(
    [success] => token_revoked
)
```

## `FCVLogbookAPI` Class

The `FCVLogbookAPI` class provides methods to:

1. Obtain an Access Token and Refresh Tokens using Basic Auth.
2. Refresh the Access Token using Basic Auth.
3. Access the flights endpoint.
4. Revoke a Refresh Token.

### Methods

- `getAccessTokenBasicAuth()`: Exchanges Authorization Code for Access and Refresh Tokens using Basic Auth.
- `refreshAccessTokenBasicAuth($refreshToken)`: Refreshes the Access Token using Basic Auth.
- `getFlights($accessToken)`: Accesses the flights endpoint using the Access Token.
- `revokeToken($refreshToken)`: Revokes a Refresh Token.

### Example Usage

```php
require_once 'FCVLogbookAPI.php';

$clientId = 'YOUR_CLIENT_ID';
$clientSecret = 'YOUR_CLIENT_SECRET';
$redirectUri = 'YOUR_REDIRECT_URI';
$authCode = 'YOUR_AUTHORIZATION_CODE'; // Obtain this through the authorization process

$fcvApi = new FCVLogbookAPI($clientId, $clientSecret, $redirectUri, $authCode);

$tokens = $fcvApi->getAccessTokenBasicAuth();
if (isset($tokens['access_token']) && isset($tokens['refresh_token'])) {
    $flights = $fcvApi->getFlights($tokens['access_token']);
    print_r($flights);
}
```

## License

This project is licensed under the MIT License.
