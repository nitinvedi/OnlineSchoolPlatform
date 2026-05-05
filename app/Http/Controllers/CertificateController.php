<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * View the certificate in the browser.
     */
    public function show(Certificate $certificate)
    {
        $this->authorizeAccess($certificate);

        $certificate->load('user', 'course.instructor');

        return view('certificates.show', compact('certificate'));
    }

    /**
     * Download the certificate as a PDF.
     */
    public function download(Certificate $certificate)
    {
        $this->authorizeAccess($certificate);

        $certificate->load('user', 'course.instructor');

        // Load the view and render the PDF
        $pdf = Pdf::loadView('certificates.pdf', compact('certificate'))
                  ->setPaper('a4', 'landscape');

        $filename = 'Certificate-' . $certificate->certificate_number . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Ensure the user owns the certificate or is an admin.
     */
    private function authorizeAccess(Certificate $certificate): void
    {
        if ($certificate->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'You do not have permission to view this certificate.');
        }
    }
}
