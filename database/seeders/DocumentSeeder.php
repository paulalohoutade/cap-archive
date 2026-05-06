<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $documents = [
            [
                'nom_fichier'     => 'Rapport_annuel_2024.pdf',
                'chemin_stockage' => 'public/documents/Rapport_annuel_2024.pdf',
                'source'          => 'upload-manuel',
                'created_at'      => $now->copy()->subDays(60),
                'updated_at'      => $now->copy()->subDays(60),
            ],
            [
                'nom_fichier'     => 'Budget_previsionnel_2025.xlsx',
                'chemin_stockage' => 'public/documents/Budget_previsionnel_2025.xlsx',
                'source'          => 'import-batch',
                'created_at'      => $now->copy()->subDays(45),
                'updated_at'      => $now->copy()->subDays(45),
            ],
            [
                'nom_fichier'     => 'Proces_verbal_CA_mars2025.docx',
                'chemin_stockage' => 'public/documents/Proces_verbal_CA_mars2025.docx',
                'source'          => 'upload-manuel',
                'created_at'      => $now->copy()->subDays(30),
                'updated_at'      => $now->copy()->subDays(30),
            ],
            [
                'nom_fichier'     => 'Liste_etudiants_L3_2025.pdf',
                'chemin_stockage' => 'public/documents/Liste_etudiants_L3_2025.pdf',
                'source'          => 'pipeline',
                'created_at'      => $now->copy()->subDays(20),
                'updated_at'      => $now->copy()->subDays(20),
            ],
            [
                'nom_fichier'     => 'Circulaire_rentree_2025.pdf',
                'chemin_stockage' => 'public/documents/Circulaire_rentree_2025.pdf',
                'source'          => 'upload-manuel',
                'created_at'      => $now->copy()->subDays(15),
                'updated_at'      => $now->copy()->subDays(15),
            ],
            [
                'nom_fichier'     => 'Contrat_prestataire_IT.pdf',
                'chemin_stockage' => 'public/documents/Contrat_prestataire_IT.pdf',
                'source'          => 'import-batch',
                'created_at'      => $now->copy()->subDays(10),
                'updated_at'      => $now->copy()->subDays(10),
            ],
            [
                'nom_fichier'     => 'Note_service_05_2025.docx',
                'chemin_stockage' => 'public/documents/Note_service_05_2025.docx',
                'source'          => 'pipeline',
                'created_at'      => $now->copy()->subDays(5),
                'updated_at'      => $now->copy()->subDays(5),
            ],
            [
                'nom_fichier'     => 'Planning_examens_juin2025.pdf',
                'chemin_stockage' => 'public/documents/Planning_examens_juin2025.pdf',
                'source'          => 'upload-manuel',
                'created_at'      => $now->copy()->subDays(2),
                'updated_at'      => $now->copy()->subDays(2),
            ],
        ];

        DB::table('documents')->insert($documents);

        $this->command->info('✅ ' . count($documents) . ' documents insérés.');
    }
}