<?php

// Init: $twig, $sessionManager, $requestHandler, $uiComponent
include __DIR__ . '/../config/twig_init.php';
// Init: $queryHandler, $userManager
include __DIR__ . '/../src/Classes/Utility/User_Manager_Loader.php';

$authController = new App\Controller\AuthController($userManager, $sessionManager, $queryHandler, $logManager);
$accountController = new \App\Controller\AccountController($userManager, $sessionManager, $logManager);

if ($requestHandler->isPost() && $requestHandler->getAction() === 'logout') {
    $authController->logout();
    exit;
}

if ($requestHandler->isPost() && $requestHandler->getAction() === 'edit') {
    $accountController->updateProfile();
}

$alerts = $uiComponent->getAlertsData();
$navbar = $uiComponent->getNavbarData();
$user = $queryHandler->getUserDataByUsername($sessionManager->getUsername());
$quote = $uiComponent->getRandomQuote();

$input_data = [
    ['type' => 'hidden', 'name' => 'id', 'value' => $user['id']],
    ['type' => 'hidden', 'name' => 'role_id', 'value' => $user['role_id']],
    ['type' => 'hidden', 'name' => 'comment', 'value' => $user['comment']],
    ['type' => 'hidden', 'name' => 'pwd_reset_pending', 'value' => $user['pwd_reset_pending']],
    ['type' => 'text', 'id' => 'username', 'name' => 'username', 'label' => 'Username', 'value' => $user['username'], 'required' => true],
    ['type' => 'email', 'id' => 'email', 'name' => 'email', 'label' => 'Email', 'value' => $user['email'], 'required' => true],
];

$buttons = [
    [
        'text' => 'Update',
        'hidden' => [
            'action' => 'edit',
        ],
    ],
    [
        'text' => 'Reset Password',
        'class' => 'btn btn-danger',
        'href' => 'reset_password.php'
    ]
];

$accordion = [
    'items' => [
        ['title' => 'Random Quote', 'content' => "<blockquote>{$quote['quote']}</blockquote><footer>- {$quote['author']}</footer>"],
        [
            'title' => 'How to protect your Data',
            'content' => '<a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">Watch Video</a>',
        ],
    ]
];

$card = [
    [
        'title' => 'Definition',
        'content' => 'to give something as an honor or present.'
    ],
    [
        'title' => 'Word',
        'content' => 'Bestow'
    ],
    [
        'title' => 'Example',
        'content' => "'The country's highest medal was bestowed upon him for heroism.'"
    ],
    [
        'title' => 'Synonyms',
        'content' => 'give, offer, provide, supply, donate',
    ]
];

$carousel = [
    [
        'image' => './img/dog1.jpg',
        'alt' => 'dog picture',
    ],
    [
        'image' => './img/dog2.jpg',
        'alt' => 'dog picture',
    ],
    [
        'image' => './img/dog3.jpg',
        'alt' => 'dog picture',
    ],

];


$data = [
    'form_title' => 'Edit',
    'form_action' => '',
    'form_method' => 'POST',
    'input_data' => $input_data,
    'buttons' => $buttons,
    'navbar' => $navbar,
    'alerts' => $alerts,
    'items' => $accordion,
    'card' => $card,
    'carousel' => $carousel,
];

echo $twig->render('user_dashboard.html.twig', $data);
