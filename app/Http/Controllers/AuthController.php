<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

/**
 * AuthController
 * 
 * Gère l'authentification via sessions Laravel (session-based auth).
 * Le frontend React enverra les cookies de session automatiquement
 * grâce à la config CORS + credentials.
 */
class AuthController extends Controller
{
    /**
     * POST /api/login
     * Connexion de l'utilisateur.
     * Retourne les infos user + token de session.
     */
    public function login(Request $request): JsonResponse
    {
        // Validation des champs obligatoires
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Tentative de connexion avec les credentials
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Retourner 401 avec message d'erreur clair
            return response()->json([
                'success' => false,
                'message' => 'Identifiants incorrects. Veuillez réessayer.',
            ], 401);
        }

        // Régénérer la session pour éviter la fixation de session
        $request->session()->regenerate();

        $user = Auth::user();

        return response()->json([
    'success' => true,
    'message' => 'Connexion réussie. Bienvenue sur ArchiveXA !',
    'data' => [
        'user' => [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
        ],
    ],
]);
    }

    /**
     * POST /api/logout
     * Déconnexion de l'utilisateur courant.
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie.',
        ]);
    }

    /**
     * GET /api/me
     * Retourne les infos de l'utilisateur connecté.
     * Protégé par le middleware 'auth'.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
    'success' => true,
    'data' => [
        'user' => [
            'id'    => $request->user()->id,
            'name'  => $request->user()->name,
            'email' => $request->user()->email,
        ],
    ],
]);
    }
}
