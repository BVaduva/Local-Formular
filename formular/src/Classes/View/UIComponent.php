<?php

namespace App\View;

use App\Utility\SessionManager;
use Twig\Environment;
use App\Utility\LogManager;

class UIComponent
{

    private SessionManager $sessionManager;
    private $twig;
    private LogManager $logManager;

    public function __construct(Environment $twig, SessionManager $sessionManager, LogManager $logManager)
    {
        $this->twig = $twig;
        $this->sessionManager = $sessionManager;
        $this->logManager = $logManager;
    }

    public function getNavbarData(): string
    {
        $username = $this->sessionManager->get('username');
        $roleId = $this->sessionManager->get('role_id');

        $navbarData = [
            'current_user' => $username,
            'role_id' => $roleId,
            // Add other necessary data
        ];
        
        return $this->twig->render('navbar.twig', $navbarData);
    }

    // Docs like the one below offer given information in the tooltip when hovering over method.

    /**
     * This PHP function retrieves alert data from session and cookie storage, then renders an alerts
     * template with the retrieved data.
     * 
     * @return array An array is being returned with a key 'alerts_html' containing the rendered content
     * of the 'alerts.twig' template. The rendered content includes 'alert_message' and 'alert_type'
     * values fetched from session alerts or cookie alerts. If no alerts are found, default values are
     * used ('alert_message' set to null and 'alert_type' set to 'alert-info').
     */
    public function getAlertsData(): string
    {
        $alerts = [];

        foreach (['delete_state', 'update_state', 'error_message'] as $key) {
            $alert = $this->sessionManager->getSessionAlert($key);
            if ($alert) {
                $alerts['alert_message'] = $alert['message'];
                $alerts['alert_type'] = $alert['type'];
                break;
            }
        }

        if (empty($alerts)) {
            $cookieAlert = $this->sessionManager->getCookieAlert('logout_message');
            if ($cookieAlert) {
                $alerts['alert_message'] = $cookieAlert['message'];
                $alerts['alert_type'] = $cookieAlert['type'];
            }
        }

        $alerts = [
            'alert_message' => $alerts['alert_message'] ?? null,
            'alert_type' => $alerts['alert_type'] ?? 'alert-info'
        ];

        return $this->twig->render('alerts.twig', $alerts);
    }

    public function renderTable(array $columnNames, array $userData, int $role_Id, bool $singleResult = false)
    {
        $tableData = [
            'columnNames' => $columnNames,
            'users' => $userData,
            'singleResult' => $singleResult,
            'role_id' => $role_Id,
        ];

        return $this->twig->render('table_user.twig', $tableData);
    }

    public function renderTableFilter(): string
    {
        // Render the Twig template for the table filter
        return $this->twig->render('table_filter.twig');
    }

    public function getRandomQuote()
    {
        // Initialize a cURL session
        $ch = curl_init();

        // Set the URL for the request to the ZenQuotes API
        curl_setopt($ch, CURLOPT_URL, "https://zenquotes.io/api/random");

        // Set options to return the response as a string instead of outputting it directly
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Execute the cURL request and store the response
        $response = curl_exec($ch);

        // Close the cURL session to free up resources
        curl_close($ch);

        // Decode the JSON response into an associative array
        $quoteData = json_decode($response, true);

        // Check if the expected fields 'q' (quote) and 'a' (author) exist in the response
        if (isset($quoteData[0]['q'], $quoteData[0]['a'])) {
            $data = [
                'quote' => $quoteData[0]['q'],
                'author' => $quoteData[0]['a'],
            ];
        } else {
            $data = [
                'quote' => 'No quote available at the moment.',
                'author' => '',
            ];
        }

        return $data;
    }

    public function parseTooltips($content, $link_color) {
        // Regular expression to match the pattern: %% "Text"("tooltip_text", position) %%
        $pattern = '/%%\s*"([^"]+)"\s*\(\s*"([^"]+)"\s*,\s*(top|bottom|left|right)\s*\)\s*%%/';
    
        // Replace the pattern with HTML for tooltip
        $content = preg_replace_callback($pattern, function ($matches) use ($link_color) {
            $text = htmlspecialchars($matches[1]); // Captured text for the tooltip
            $tooltipText = htmlspecialchars($matches[2]); // Captured tooltip text
            $position = $matches[3]; // Captured position without extra quotes
    
            // Return the HTML for the tooltip as a link
            return '<a href="#" class="link-' . $link_color . '" data-bs-toggle="tooltip" data-bs-title="' . $tooltipText . '" data-bs-placement="' . $position . '">' . $text . '</a>';
    
        }, $content);
    
        return $content;
    }
    
}
