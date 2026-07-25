<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Inertia\Inertia;
use Inertia\Response;

class AuditLogController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('audit/index', [
            'logs' => AuditLog::query()
                ->with('actor:id,name')
                ->latest()
                ->paginate(25),
        ]);
    }
}
