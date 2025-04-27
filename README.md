# Blacklister

The Blacklister API service allows users to efficiently check if a username, email, domain, IP address, or URL is present on a blacklist. 

This service enhances security by helping organizations identify potentially harmful entities and prevent unauthorized access.

[Online Demo](https://k24.ing/BlackLister)


### Usage

```php

<?php

declare(strict_types=1);

final class Client
{
    public function __construct(
        private string          $url,
        private readonly string $bearerToken,
        private readonly string $userAgent,
    ) {
    }

    public function isOnBlackList(string $path): bool
    {
        $this->url = $this->url . $path;
        $response = $this->get();

        return $response->success;
    }

    private function get(): stdClass
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $headers = [
            "Authorization: Bearer $this->bearerToken",
            "User-Agent: $this->userAgent"
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);

        return json_decode($response);
    }
}

$url = 'http://bler.khalilleo.com/api/v1/';
$bearerToken = 'your-bearer-token';
$userAgent = 'your-user-agent';

$client = new Client($url, $bearerToken, $userAgent);
//$isUsernameOnBlackList = $client->isOnBlackList('username?username=John_doe');
//$isDomainOnBlackList = $client->isOnBlackList('domain?domain=example.com');
$isEmailOnBlackList = $client->isOnBlackList('email?email=j.doe@example.com');
//$isIpAddressOnBlackList = $client->isOnBlackList('ip?ip=172.0.01');
//$isURLOnBlackList = $client->isOnBlackList('url?url=https://example.com');

echo $isEmailOnBlackList ? 'TRUE' : 'FALSE';
```