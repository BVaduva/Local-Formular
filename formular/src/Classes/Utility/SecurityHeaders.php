<?php
namespace App\Utility;

class SecurityHeaders
{
    public function setHeaders(): void
    {
        // Content-Security-Policy (CSP) - Condensed to avoid line breaks
        // @ ignores php warning for multiple headers
        @header("Content-Security-Policy: 
        default-src 'self'; 
        script-src 'self' 'unsafe-inline' 'unsafe-eval' https://stackpath.bootstrapcdn.com; 
        style-src 'self' 'unsafe-inline' https://stackpath.bootstrapcdn.com; 
        img-src 'self'; 
        object-src 'none';");

        // Other headers with trim applied
        header(trim("X-Content-Type-Options: nosniff"));
        header(trim("X-Frame-Options: DENY"));
        header(trim("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload"));
        header(trim("X-XSS-Protection: 1; mode=block"));

        header(trim("Referrer-Policy: no-referrer"));
        // Control access to browser features
        header(trim("Permissions-Policy: geolocation=(), microphone=()"));
    }
}
?>