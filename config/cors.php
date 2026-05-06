<?php

/**
 * Configuration CORS — ArchiveXA
 * 
 * Autorise le frontend React (port 5173 en dev, ou domaine prod)
 * à envoyer des requêtes avec cookies (credentials: 'include').
 * 
 * IMPORTANT : Ne jamais mettre '*' dans allowed_origins quand
 * supports_credentials est true — les navigateurs refuseront.
 */
return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173',   // Vite dev server
        'http://127.0.0.1:5173',
        // Ajouter ton domaine de production ici :
        // 'https://archivexa.ton-domaine.com',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // CRITIQUE : doit être true pour que les cookies de session fonctionnent
    'supports_credentials' => true,

];
