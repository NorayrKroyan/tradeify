<?php

namespace App\Http\Controllers;

use App\Models\VerificationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VerificationDocumentController extends Controller
{
    public function download(Request $request, VerificationDocument $document): StreamedResponse
    {
        $user = $request->user();
        $ownsCompany = $user->primary_company_id === $document->company_id;
        $canReview = $user->can('review companies');

        abort_unless($ownsCompany || $canReview, 403);
        abort_unless(Storage::disk($document->disk)->exists($document->path), 404);

        return Storage::disk($document->disk)->download(
            $document->path,
            $document->original_name,
            ['Content-Type' => $document->mime_type]
        );
    }
}
