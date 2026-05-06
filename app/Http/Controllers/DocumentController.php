<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * DocumentController
 * 
 * Gère toutes les opérations sur les documents archivés :
 * - Lister, créer, afficher, télécharger, supprimer
 */
class DocumentController extends Controller
{
    /**
     * GET /api/documents
     * Liste tous les documents avec pagination et recherche.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Document::query()->latest();

        // Recherche par nom de fichier
        if ($request->has('search') && $request->search) {
            $query->where('nom_fichier', 'like', '%' . $request->search . '%');
        }

        // Filtre par source
        if ($request->has('source') && $request->source) {
            $query->where('source', $request->source);
        }

        // Pagination : 12 documents par page (pour affichage en cartes 3 colonnes)
        $documents = $query->paginate(12);

        return response()->json([
            'success'    => true,
            'data'       => $documents->items(),
            'pagination' => [
                'current_page' => $documents->currentPage(),
                'last_page'    => $documents->lastPage(),
                'per_page'     => $documents->perPage(),
                'total'        => $documents->total(),
            ],
        ]);
    }

    /**
     * POST /api/documents
     * Upload et archivage d'un nouveau document.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'fichier' => 'required|file|max:51200', // max 50 MB
            'source'  => 'nullable|string|max:100',
        ]);

        $file = $request->file('fichier');
        $originalName = $file->getClientOriginalName();
        
        // Générer un nom unique pour éviter les conflits
        $uniqueName = Str::uuid() . '_' . $originalName;
        
        // Stocker dans storage/app/public/documents
        $path = $file->storeAs('documents', $uniqueName, 'public');

        $document = Document::create([
            'nom_fichier'     => $originalName,
            'chemin_stockage' => $path,
            'source'          => $request->source ?? 'upload-manuel',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document archivé avec succès.',
            'data'    => $document,
        ], 201);
    }

    /**
     * GET /api/documents/{id}
     * Récupère les détails d'un document spécifique.
     */
    public function show(Document $document): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $document,
        ]);
    }

    /**
     * DELETE /api/documents/{id}
     * Supprime un document (fichier + entrée DB).
     */
    public function destroy(Document $document): JsonResponse
    {
        // Supprimer le fichier physique du storage
        if (Storage::disk('public')->exists($document->chemin_stockage)) {
            Storage::disk('public')->delete($document->chemin_stockage);
        }

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document supprimé avec succès.',
        ]);
    }

    /**
     * GET /api/documents/{id}/download
     * Télécharger un document.
     */
    public function download(Document $document)
    {
        $filePath = storage_path('app/public/' . $document->chemin_stockage);

        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'Fichier introuvable sur le serveur.',
            ], 404);
        }

        return response()->download($filePath, $document->nom_fichier);
    }

    /**
     * GET /api/stats
     * Statistiques globales pour le dashboard.
     */
    public function stats(): JsonResponse
    {
        $total = Document::count();
        $thisMonth = Document::whereMonth('created_at', now()->month)->count();
        $sources = Document::selectRaw('source, count(*) as count')
            ->groupBy('source')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_documents'      => $total,
                'documents_ce_mois'    => $thisMonth,
                'repartition_sources'  => $sources,
            ],
        ]);
    }

    public function ingest(Request $request): JsonResponse
{
    $request->validate([
        'fichier' => 'required|file|mimes:pdf,doc,docx,xlsx,png,jpg|max:20480',
    ]);

    $file   = $request->file('fichier');
    $nom    = $file->getClientOriginalName();
    $chemin = $file->storeAs('scans', $nom, 'local');

    Document::create([
        'nom_fichier'     => $nom,
        'chemin_stockage' => $chemin,
        'source'          => 'pipeline',
    ]);

    return response()->json(['success' => true, 'message' => 'Document ingéré.']);
}
}
