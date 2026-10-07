<?php

namespace App\Enums;

enum Stage: string
{
    case Received = 'received';
    case DocumentReview = 'document_review';
    case Interview = 'interview';
    case Technical = 'technical';
    case Selected = 'selected';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Received => 'Postulación recibida',
            self::DocumentReview => 'Revisión documental',
            self::Interview => 'Entrevista',
            self::Technical => 'Evaluación técnica',
            self::Selected => 'Selección',
            self::Rejected => 'No seleccionado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Received => 'bg-slate-100 text-slate-700 ring-slate-300',
            self::DocumentReview => 'bg-sky-50 text-sky-700 ring-sky-200',
            self::Interview => 'bg-violet-50 text-violet-700 ring-violet-200',
            self::Technical => 'bg-amber-50 text-amber-700 ring-amber-200',
            self::Selected => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            self::Rejected => 'bg-rose-50 text-rose-700 ring-rose-200',
        };
    }

    public function barColor(): string
    {
        return match ($this) {
            self::Received => 'bg-slate-400',
            self::DocumentReview => 'bg-sky-500',
            self::Interview => 'bg-violet-500',
            self::Technical => 'bg-amber-500',
            self::Selected => 'bg-emerald-500',
            self::Rejected => 'bg-rose-500',
        };
    }

    /** Stages of the main pipeline, in order (excludes the rejected end state). */
    public static function pipeline(): array
    {
        return [self::Received, self::DocumentReview, self::Interview, self::Technical, self::Selected];
    }

    /** Whether the application is still being worked on. */
    public function isActive(): bool
    {
        return ! in_array($this, [self::Selected, self::Rejected], true);
    }

    /** Position within the pipeline (0-based); null for the rejected end state. */
    public function position(): ?int
    {
        $index = array_search($this, self::pipeline(), true);

        return $index === false ? null : $index;
    }
}
