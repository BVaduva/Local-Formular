<?php

namespace App\Utility;

use App\Utility\LogManager;

class SessionManager
{
    private LogManager $logManager;

    public function __construct(LogManager $logManager)
    {
        $this->logManager = $logManager;

        if (session_status() == PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0, // Session cookie
                'path' => '/',
                'domain' => '', // Set to your domain or leave empty for default
                'secure' => true, // Only send over HTTPS
                'httponly' => true, // Prevent JavaScript access
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }


    // Store Data
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }


    // Retrieve Data Of Requested Variable
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }


    // Check for Data
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }


    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }


    public function destroy(): bool
    {
        return session_destroy();
    }

    public function validateSession(): bool
    {
        $current_ip = $_SERVER['REMOTE_ADDR'];
        $current_user_agent = $_SERVER['HTTP_USER_AGENT'];

        if ($this->has('user_agent') && $this->get('user_agent') !== $current_user_agent) {
            return $this->invalidSession(); // Session hijacking detected
        }

        if ($this->has('ip_address') && $this->get('ip_address') !== $current_ip) {
            return $this->invalidSession(); // Session hijacking detected
        }

        // Store current IP and User-Agent if not set
        if (!$this->has('ip_address')) {
            $this->set('ip_address', $current_ip);
        }
        if (!$this->has('user_agent')) {
            $this->set('user_agent', $current_user_agent);
        }
        
        return true; // Session is valid
    }

    private function invalidSession(): void
{
    $this->set('error_message', 'Invalid session.');
    header('Location: ');
    exit;
}


    /*****************************************************************
     *                  ALERT MANAGEMENT
     ****************************************************************/

    // Retrieve and remove an alert from session
    public function getSessionAlert(string $key): ?array
    {
        if ($this->has($key)) {
            $alert = [
                'message' => htmlspecialchars($this->get($key)),
                'type' => ($key === 'error_message') ? 'alert-danger' : 'alert-success',
            ];
            $this->remove($key); // Remove the alert from the session after fetching
            return $alert;
        }
        return null;
    }

    // Retrieve and remove an alert from cookies
    public function getCookieAlert(string $key): ?array
    {
        if ($this->hasCookie($key)) {
            $alert = [
                'message' => htmlspecialchars($this->getCookie($key)),
                'type' => 'alert-info', // Example type, can be customized based on your use case
            ];
            $this->removeCookie($key); // Clear the cookie after fetching
            return $alert;
        }
        return null;
    }

    /*****************************************************************
     *                  RETRIEVE SESSION DATA
     ****************************************************************/

    public function getUserId(): ?int
    {
        return $this->get('id');
    }


    public function getUsername(): ?string
    {
        return $this->get('username');
    }


    public function getEmail(): ?string
    {
        return $this->get('email');
    }


    public function getRoleId(): ?int
    {
        return $this->get('role_id');
    }


    public function getPwdResetPending(): ?bool
    {
        return $this->get('pwd_reset_pending');
    }


    /*****************************************************************
     *                  COOKIE MANAGEMENT
     ****************************************************************/

    public function setCookie($name, $value, $expire = 3600)
    {
        setcookie($name, $value, [
            'expires' => time() + $expire,
            'path' => '/',
            'secure' => true, // Ensures the cookie is sent over HTTPS only
            'httponly' => true, // Prevents JavaScript access to the cookie
            'samesite' => 'Lax' // Adjust based on your needs ('Strict', 'Lax', or 'None')
        ]);
    }



    public function getCookie($name)
    {
        return $_COOKIE[$name] ?? null;
    }

    public function hasCookie(string $key): bool
    {
        return isset($_COOKIE[$key]);
    }


    public function removeCookie($name)
    {
        setcookie($name, "", time() - 3600, "/");
    }
}
