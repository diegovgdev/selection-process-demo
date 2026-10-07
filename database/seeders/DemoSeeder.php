<?php

namespace Database\Seeders;

use App\Enums\Stage;
use App\Models\ActivityLog;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Evaluation;
use App\Models\SelectionProcess;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Deterministic, fully fictitious dataset. Running it twice on a clean
 * database always produces exactly the same records.
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $processes = collect([
            ['Asistente de Operaciones', 'Apoyo administrativo y logístico a las operaciones diarias (proceso ficticio).', 2, 'open'],
            ['Analista de Datos Junior', 'Análisis y reportería de indicadores con SQL y hojas de cálculo (proceso ficticio).', 2, 'open'],
            ['Desarrollador/a Web Junior', 'Desarrollo de funcionalidades web con PHP y JavaScript (proceso ficticio).', 2, 'open'],
        ])->map(fn (array $p) => SelectionProcess::create([
            'name' => $p[0], 'description' => $p[1], 'slots' => $p[2], 'status' => $p[3],
        ]));

        // name, process index, stage, applied_at, documents, interview, technical, comment
        $rows = [
            ['Valentina Arce Montt', 0, 'selected', '2025-03-03', 92, 88, 90, 'Excelente organización y comunicación. Resultados sólidos en todas las etapas.'],
            ['Matías Corvalán Ibarra', 0, 'selected', '2025-03-04', 85, 90, 84, 'Buen manejo de prioridades; destaca en la entrevista.'],
            ['Isidora Pino Valdés', 0, 'technical', '2025-03-05', 78, 82, null, 'Perfil prometedor, pendiente de cerrar la evaluación técnica.'],
            ['Joaquín Sepúlveda Lira', 0, 'interview', '2025-03-06', 74, null, null, 'Documentación completa y ordenada.'],
            ['Antonella Fuentes Rey', 0, 'rejected', '2025-03-06', 55, 60, null, 'No alcanza el mínimo requerido en la entrevista.'],
            ['Benjamín Ortúzar Vega', 1, 'selected', '2025-03-07', 90, 86, 94, 'Muy buen razonamiento analítico y dominio de SQL.'],
            ['Catalina Moraga Soto', 1, 'technical', '2025-03-08', 81, 79, 76, 'Prueba técnica correcta; falta profundizar en visualización.'],
            ['Sebastián Olmos Tapia', 1, 'interview', '2025-03-09', 88, null, null, 'Buen perfil documental, entrevista agendada.'],
            ['Florencia Lagos Duarte', 1, 'document_review', '2025-03-10', null, null, null, 'Documentos recibidos, en revisión.'],
            ['Tomás Quiroga Bravo', 1, 'rejected', '2025-03-10', 48, null, null, 'No cumple los requisitos mínimos del cargo.'],
            ['Emilia Barrera Núñez', 2, 'selected', '2025-03-11', 95, 91, 93, 'Portafolio destacado y gran capacidad técnica.'],
            ['Lucas Meneses Aravena', 2, 'technical', '2025-03-12', 83, 85, 72, 'Buena actitud; la prueba técnica muestra espacio de mejora.'],
            ['Josefa Carrasco Ibáñez', 2, 'interview', '2025-03-13', 79, null, null, 'Buena base técnica, pendiente de entrevista.'],
            ['Agustín Zamora Pizarro', 2, 'received', '2025-03-14', null, null, null, 'Postulación recién ingresada.'],
            ['Renata Escobar Villalobos', 2, 'rejected', '2025-03-14', 62, 58, 51, 'Resultado técnico inferior al mínimo exigido.'],
            ['Ignacio Bustos Carvajal', 0, 'received', '2025-03-15', null, null, null, 'Postulación recién ingresada.'],
        ];

        foreach ($rows as $i => [$name, $processIndex, $stage, $date, $docs, $interview, $technical, $comment]) {
            $candidate = Candidate::create([
                'code' => sprintf('DEMO-%03d', $i + 1),
                'name' => $name,
            ]);

            $application = Application::create([
                'selection_process_id' => $processes[$processIndex]->id,
                'candidate_id' => $candidate->id,
                'stage' => $stage,
                'applied_at' => $date,
                'comment' => $comment,
            ]);

            Evaluation::create([
                'application_id' => $application->id,
                'documents_score' => $docs,
                'interview_score' => $interview,
                'technical_score' => $technical,
            ]);

            $this->seedActivity($application, $candidate, Stage::from($stage), Carbon::parse($date));
        }
    }

    private function seedActivity(Application $application, Candidate $candidate, Stage $stage, Carbon $appliedAt): void
    {
        ActivityLog::create([
            'application_id' => $application->id,
            'message' => "{$candidate->name} ({$candidate->code}) envió su postulación.",
            'occurred_at' => $appliedAt->copy()->setTime(9, 0),
        ]);

        if ($stage !== Stage::Received) {
            ActivityLog::create([
                'application_id' => $application->id,
                'message' => "{$candidate->name} ({$candidate->code}) pasó a «{$stage->label()}».",
                'occurred_at' => $appliedAt->copy()->addDays(5)->setTime(15, 30),
            ]);
        }
    }
}
