<?php

namespace App\Jobs;

use App\Models\Assessment;
use App\Services\AnswerNormalizer;
use App\Services\ConstraintService;
use App\Services\TopsisService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RunTopsisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $assessmentId){}

    public function handle(AnswerNormalizer $norm, ConstraintService $cons, TopsisService $svc): void
    {
        $a = Assessment::with(['answers','alternatives.route.mountain'])->findOrFail($this->assessmentId);
        $norm->normalize($a);
        $cons->apply($a);
        $svc->run($a);
    }
}