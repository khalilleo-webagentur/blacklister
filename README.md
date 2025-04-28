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

    public function isUsernameOnBlackList(string $username): bool
    {
        $this->url = $this->url .'username?username='. $username;
        $response = $this->getResponse();

        return $response->success;
    }

    public function isEmailOnBlackList(string $email): bool
    {
        $this->url = $this->url .'email?email='. $email;
        $response = $this->getResponse();

        return $response->success;
    }

    public function isDomainOnBlackList(string $domain): bool
    {
        $this->url = $this->url .'domain?domain='. $domain;
        $response = $this->getResponse();

        return $response->success;
    }

    public function isIPAddressOnBlackList(string $ip): bool
    {
        $this->url = $this->url .'ip?ip='. $ip;
        $response = $this->getResponse();

        return $response->success;
    }

    public function isURLOnBlackList(string $url): bool
    {
        $this->url = $this->url .'url?url='. $url;
        $response = $this->getResponse();

        return $response->success;
    }

    private function getResponse(): stdClass
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'getResponse');

        $headers = [
            "Authorization: Bearer $this->bearerToken",
            'Accept: application/json',
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
// $isUsernameOnBlackList = $client->isUsernameOnBlackList('John_doe');
// $isDomainOnBlackList = $client->isDomainOnBlackList('example.com');
$isEmailOnBlackList = $client->isEmailOnBlackList('j.doe@example.com');
// $isIpAddressOnBlackList = $client->isIPAddressOnBlackList('172.0.01');
// $isURLOnBlackList = $client->isURLOnBlackList('https://example.com');

echo $isEmailOnBlackList ? 'TRUE' : 'FALSE';
```