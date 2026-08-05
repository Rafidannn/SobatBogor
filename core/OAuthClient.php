<?php
/**
 * core/OAuthClient.php
 * Lightweight OAuth 2.0 client tanpa dependency eksternal.
 * Mendukung Google dan Facebook Authorization Code Flow.
 */
class OAuthClient {

    /**
     * Bangun URL authorization redirect untuk Google.
     */
    public static function buildGoogleAuthUrl(array $cfg): string {
        $params = http_build_query([
            'client_id'     => $cfg['client_id'],
            'redirect_uri'  => $cfg['redirect_uri'],
            'response_type' => 'code',
            'scope'         => $cfg['scope'],
            'access_type'   => 'online',
            'state'         => self::generateState(),
        ]);
        return 'https://accounts.google.com/o/oauth2/v2/auth?' . $params;
    }

    /**
     * Tukar authorization code Google menjadi access token.
     */
    public static function getGoogleToken(array $cfg, string $code): ?array {
        $response = self::httpPost('https://oauth2.googleapis.com/token', [
            'code'          => $code,
            'client_id'     => $cfg['client_id'],
            'client_secret' => $cfg['client_secret'],
            'redirect_uri'  => $cfg['redirect_uri'],
            'grant_type'    => 'authorization_code',
        ]);
        return $response;
    }

    /**
     * Ambil profil user dari Google menggunakan access token.
     */
    public static function getGoogleUser(string $accessToken): ?array {
        $response = self::httpGet(
            'https://www.googleapis.com/oauth2/v3/userinfo',
            ['Authorization: Bearer ' . $accessToken]
        );
        if (!$response) return null;
        return [
            'provider'    => 'google',
            'provider_id' => $response['sub'] ?? null,
            'name'        => $response['name'] ?? 'Pengguna Google',
            'email'       => $response['email'] ?? null,
            'avatar'      => $response['picture'] ?? null,
        ];
    }

    /**
     * Bangun URL authorization redirect untuk Facebook.
     */
    public static function buildFacebookAuthUrl(array $cfg): string {
        $params = http_build_query([
            'client_id'     => $cfg['app_id'],
            'redirect_uri'  => $cfg['redirect_uri'],
            'scope'         => $cfg['scope'],
            'response_type' => 'code',
            'state'         => self::generateState(),
        ]);
        return 'https://www.facebook.com/v19.0/dialog/oauth?' . $params;
    }

    /**
     * Tukar authorization code Facebook menjadi access token.
     */
    public static function getFacebookToken(array $cfg, string $code): ?array {
        $params = http_build_query([
            'client_id'     => $cfg['app_id'],
            'client_secret' => $cfg['app_secret'],
            'redirect_uri'  => $cfg['redirect_uri'],
            'code'          => $code,
        ]);
        return self::httpGet(
            'https://graph.facebook.com/v19.0/oauth/access_token?' . $params
        );
    }

    /**
     * Ambil profil user dari Facebook menggunakan access token.
     */
    public static function getFacebookUser(string $accessToken): ?array {
        $params = http_build_query([
            'access_token' => $accessToken,
            'fields'       => 'id,name,email,picture',
        ]);
        $response = self::httpGet('https://graph.facebook.com/v19.0/me?' . $params);
        if (!$response) return null;
        return [
            'provider'    => 'facebook',
            'provider_id' => $response['id'] ?? null,
            'name'        => $response['name'] ?? 'Pengguna Facebook',
            'email'       => $response['email'] ?? null,
            'avatar'      => $response['picture']['data']['url'] ?? null,
        ];
    }

    // ── Private Helpers ────────────────────────────────────────────────

    private static function generateState(): string {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $state = bin2hex(random_bytes(16));
        $_SESSION['oauth_state'] = $state;
        return $state;
    }

    private static function httpPost(string $url, array $data): ?array {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 15,
        ]);
        $body = curl_exec($ch);
        $err  = curl_error($ch);
        curl_close($ch);
        if ($err || !$body) return null;
        return json_decode($body, true);
    }

    private static function httpGet(string $url, array $headers = []): ?array {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 15,
        ]);
        $body = curl_exec($ch);
        $err  = curl_error($ch);
        curl_close($ch);
        if ($err || !$body) return null;
        return json_decode($body, true);
    }
}
