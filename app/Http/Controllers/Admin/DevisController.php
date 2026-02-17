<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use App\Exports\DevisExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Devis;
use App\Models\CompanyInfo;
use Barryvdh\DomPDF\Facade\Pdf;


class DevisController extends Controller
{
    /**
     * Generate next devis reference ID
     */
    private function nextId(): string 
    { 
        $lastDevis = Devis::orderBy('ref_id', 'desc')->first();
        $max = 0;
        
        if ($lastDevis && preg_match('/^ALC-DEV-(\d{5})$/', $lastDevis->ref_id, $m)) {
            $max = (int)$m[1];
        }
        
        $n = $max + 1;
        return 'ALC-DEV-'.str_pad((string)$n, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Display devis listing page with all devis data.
     */
    public function index(Request $request)
    {
        // Get all devis ordered by ref_id ascending
        $allDevis = Devis::orderBy('ref_id', 'asc')->get();
        
        // Separate standard vs specific
        $standardDevis = $allDevis->where('type', 'standard')->values();
        $specificDevis = $allDevis->where('type', 'specific')->values();
        
        // Calculate DYNAMIC stats
        $totalDevis = $allDevis->count();
        $statusCounts = $allDevis->groupBy('status')->map->count()->toArray();
        
        // Ensure all status keys exist (with 0 if missing)
        $allStatuses = ['nouveau', 'en_cours', 'envoye', 'confirme', 'annule'];
        foreach ($allStatuses as $status) {
            $statusCounts[$status] = $statusCounts[$status] ?? 0;
        }

        $pendingCount = ($statusCounts['nouveau'] ?? 0) + 
                        ($statusCounts['en_cours'] ?? 0) + 
                        ($statusCounts['envoye'] ?? 0);

        $stats = [
            'totalDevis' => $totalDevis,
            'standard' => $standardDevis->count(),
            'specific' => $specificDevis->count(),
            'status' => $statusCounts,
            'pending' => $pendingCount
        ];

        return view('admin.devis', compact(
            'standardDevis', 
            'specificDevis', 
            'stats', 
            'totalDevis'
        ));
    }

    /**
     * Get current stats for real-time updates (AJAX endpoint)
     */
    public function stats(Request $request)
    {
        if (!$request->ajax() && !$request->wantsJson()) {
            abort(404);
        }
        
        $allDevis = Devis::orderBy('ref_id', 'asc')->get();
        
        $standardDevis = $allDevis->where('type', 'standard')->values();
        $specificDevis = $allDevis->where('type', 'specific')->values();
        
        $totalDevis = $allDevis->count();
        $statusCounts = $allDevis->groupBy('status')->map->count()->toArray();
        
        $allStatuses = ['nouveau', 'en_cours', 'envoye', 'confirme', 'annule'];
        foreach ($allStatuses as $status) {
            $statusCounts[$status] = $statusCounts[$status] ?? 0;
        }
        
        $pendingCount = ($statusCounts['nouveau'] ?? 0) + 
                        ($statusCounts['en_cours'] ?? 0) + 
                        ($statusCounts['envoye'] ?? 0);

        $stats = [
            'totalDevis' => $totalDevis,
            'standard' => $standardDevis->count(),
            'specific' => $specificDevis->count(),
            'status' => $statusCounts,
            'pending' => $pendingCount
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Show the form for creating a new devis.
     */
    public function create()
    {
        $clients = Devis::select('name', 'email', 'phone', 'company')
                        ->distinct()
                        ->get();

        return view('admin.devis-create', compact('clients'));
    }

    /**
     * Store a newly created devis.
     */
    public function store(Request $request)
    {
        $requirements = $request->input('requirements');
        if (is_array($requirements)) {
            $request->merge(['requirements' => implode("\n", $requirements)]);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:40',
            'company' => 'nullable|string|max:120',
            'type' => 'required|in:standard,specific',
            'product' => 'required_if:type,standard|string|max:200',
            'quantity' => 'nullable|integer|min:1',
            'message' => 'required_if:type,standard|string|max:3000',
            'requirements' => 'required_if:type,specific|string|max:5000',
            
            // Technical fields
            'type_exchangeur' => 'nullable|string|max:100',
            'cuivre_diametre' => 'nullable|numeric',
            'pas_ailette' => 'nullable|numeric',
            'hauteur_mm' => 'nullable|numeric',
            'largeur_mm' => 'nullable|numeric',
            'longueur_mm' => 'nullable|numeric',
            'longueur_totale_mm' => 'nullable|numeric',
            'collecteur1' => 'nullable|string|max:100',
            'collecteur1_diametre' => 'nullable|numeric',
            'collecteur2' => 'nullable|string|max:100',
            'collecteur2_diametre' => 'nullable|numeric',
            'nombre_tubes' => 'nullable|integer',
            'geometrie_x_mm' => 'nullable|numeric',
            'geometrie_y_mm' => 'nullable|numeric',
            'collecteur1_nb' => 'nullable|integer',
            'collecteur2_nb' => 'nullable|integer',
        ]);

        try {
            $refId = $this->nextId();

            $devis = Devis::create([
                'ref_id' => $refId,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'company' => $validated['company'] ?? null,
                'type' => $validated['type'],
                'product' => $validated['product'] ?? null,
                'quantity' => $validated['quantity'] ?? 1,
                'message' => $validated['message'] ?? null,
                'requirements' => $validated['requirements'] ?? null,
                
                // Technical fields
                'type_exchangeur' => $validated['type_exchangeur'] ?? null,
                'cuivre_diametre' => $validated['cuivre_diametre'] ?? null,
                'pas_ailette' => $validated['pas_ailette'] ?? null,
                'hauteur_mm' => $validated['hauteur_mm'] ?? null,
                'largeur_mm' => $validated['largeur_mm'] ?? null,
                'longueur_mm' => $validated['longueur_mm'] ?? null,
                'longueur_totale_mm' => $validated['longueur_totale_mm'] ?? null,
                'collecteur1' => $validated['collecteur1'] ?? null,
                'collecteur1_diametre' => $validated['collecteur1_diametre'] ?? null,
                'collecteur2' => $validated['collecteur2'] ?? null,
                'collecteur2_diametre' => $validated['collecteur2_diametre'] ?? null,
                'nombre_tubes' => $validated['nombre_tubes'] ?? null,
                'geometrie_x_mm' => $validated['geometrie_x_mm'] ?? null,
                'geometrie_y_mm' => $validated['geometrie_y_mm'] ?? null,
                'collecteur1_nb' => $validated['collecteur1_nb'] ?? null,
                'collecteur2_nb' => $validated['collecteur2_nb'] ?? null,
                
                'status' => 'nouveau',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Devis créé avec succès!',
                'id' => $refId
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create devis', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du devis.'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified devis.
     */
    public function edit($id)
    {
        $devis = Devis::where('ref_id', $id)->first();
        
        if (!$devis) {
            abort(404, 'Devis introuvable');
        }

        // Convert to array format for backward compatibility with view
        $devisArray = [
            'id' => $devis->ref_id,
            'name' => $devis->name,
            'email' => $devis->email,
            'phone' => $devis->phone,
            'company' => $devis->company,
            'type' => $devis->type,
            'product' => $devis->product,
            'quantity' => $devis->quantity,
            'message' => $devis->message,
            'requirements' => $devis->requirements,
            'status' => $devis->status,
            'type_exchangeur' => $devis->type_exchangeur,
            'cuivre_diametre' => $devis->cuivre_diametre,
            'pas_ailette' => $devis->pas_ailette,
            'hauteur_mm' => $devis->hauteur_mm,
            'largeur_mm' => $devis->largeur_mm,
            'longueur_mm' => $devis->longueur_mm,
            'longueur_totale_mm' => $devis->longueur_totale_mm,
            'collecteur1' => $devis->collecteur1,
            'collecteur1_diametre' => $devis->collecteur1_diametre,
            'collecteur2' => $devis->collecteur2,
            'collecteur2_diametre' => $devis->collecteur2_diametre,
            'nombre_tubes' => $devis->nombre_tubes,
            'geometrie_x_mm' => $devis->geometrie_x_mm,
            'geometrie_y_mm' => $devis->geometrie_y_mm,
            'collecteur1_nb' => $devis->collecteur1_nb,
            'collecteur2_nb' => $devis->collecteur2_nb,
        ];

        return view('admin.devis-edit', ['devis' => $devisArray]);
    }

    /**
     * Update the specified devis.
     */
    public function update(Request $request, $id)
    {
        $requirements = $request->input('requirements');
        if (is_array($requirements)) {
            $request->merge(['requirements' => implode("\n", $requirements)]);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:40',
            'company' => 'nullable|string|max:120',
            'type' => 'required|in:standard,specific',
            'product' => 'required_if:type,standard|string|max:200',
            'quantity' => 'nullable|integer|min:1',
            'message' => 'required_if:type,standard|string|max:3000',
            'requirements' => 'required_if:type,specific|string|max:5000',
            'status' => 'required|in:nouveau,en_cours,envoye,confirme,annule',
            
            // Technical fields
            'type_exchangeur' => 'nullable|string|max:100',
            'cuivre_diametre' => 'nullable|numeric',
            'pas_ailette' => 'nullable|numeric',
            'hauteur_mm' => 'nullable|numeric',
            'largeur_mm' => 'nullable|numeric',
            'longueur_mm' => 'nullable|numeric',
            'longueur_totale_mm' => 'nullable|numeric',
            'collecteur1' => 'nullable|string|max:100',
            'collecteur1_diametre' => 'nullable|numeric',
            'collecteur2' => 'nullable|string|max:100',
            'collecteur2_diametre' => 'nullable|numeric',
            'nombre_tubes' => 'nullable|integer',
            'geometrie_x_mm' => 'nullable|numeric',
            'geometrie_y_mm' => 'nullable|numeric',
            'collecteur1_nb' => 'nullable|integer',
            'collecteur2_nb' => 'nullable|integer',
        ]);

        try {
            $devis = Devis::where('ref_id', $id)->first();
            
            if (!$devis) {
                abort(404, 'Devis introuvable');
            }

            $devis->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'company' => $validated['company'] ?? null,
                'type' => $validated['type'],
                'product' => $validated['product'] ?? null,
                'quantity' => $validated['quantity'] ?? 1,
                'message' => $validated['message'] ?? null,
                'requirements' => $validated['requirements'] ?? null,
                'status' => $validated['status'],
                
                // Technical fields
                'type_exchangeur' => $validated['type_exchangeur'] ?? null,
                'cuivre_diametre' => $validated['cuivre_diametre'] ?? null,
                'pas_ailette' => $validated['pas_ailette'] ?? null,
                'hauteur_mm' => $validated['hauteur_mm'] ?? null,
                'largeur_mm' => $validated['largeur_mm'] ?? null,
                'longueur_mm' => $validated['longueur_mm'] ?? null,
                'longueur_totale_mm' => $validated['longueur_totale_mm'] ?? null,
                'collecteur1' => $validated['collecteur1'] ?? null,
                'collecteur1_diametre' => $validated['collecteur1_diametre'] ?? null,
                'collecteur2' => $validated['collecteur2'] ?? null,
                'collecteur2_diametre' => $validated['collecteur2_diametre'] ?? null,
                'nombre_tubes' => $validated['nombre_tubes'] ?? null,
                'geometrie_x_mm' => $validated['geometrie_x_mm'] ?? null,
                'geometrie_y_mm' => $validated['geometrie_y_mm'] ?? null,
                'collecteur1_nb' => $validated['collecteur1_nb'] ?? null,
                'collecteur2_nb' => $validated['collecteur2_nb'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Devis mis à jour avec succès!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update devis', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du devis.'
            ], 500);
        }
    }

    /**
     * Remove the specified devis.
     */
    public function destroy($id)
    {
        try {
            $devis = Devis::where('ref_id', $id)->first();
            
            if (!$devis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Devis introuvable.'
                ], 404);
            }

            $devis->delete();

            return response()->json([
                'success' => true,
                'message' => 'Devis supprimé avec succès!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete devis', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du devis.'
            ], 500);
        }
    }

    /**
     * Update devis status.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate(['status' => 'required|in:nouveau,en_cours,envoye,confirme,annule']);
        
        try {
            $devis = Devis::where('ref_id', $id)->first();
            
            if (!$devis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Devis introuvable'
                ], 404);
            }

            $devis->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut.'
            ], 500);
        }
    }

    /**
     * Send devis email with attachment.
     */
    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|string',
            'recipient_name' => 'required|string|max:120',
            'recipient_email' => 'required|email',
            'subject' => 'required|string|max:200',
            'text' => 'required|string|max:5000',
            'file' => 'nullable|file|max:5120|mimes:pdf,doc,docx',
        ]);

        try {
            // Handle file upload
            $filePath = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('devis_attachments', $fileName, 'public');
            }

            // Send email
            Mail::html(nl2br(e($validated['text'])), function ($message) use ($validated, $filePath) {
                $message->to($validated['recipient_email'], $validated['recipient_name'])
                        ->subject($validated['subject']);
                if ($filePath) {
                    $fullPath = storage_path('app/public/'.$filePath);
                    if (file_exists($fullPath)) {
                        $message->attach($fullPath);
                    }
                }
            });

            // Update status to 'envoye'
            $devis = Devis::where('ref_id', $validated['id'])->first();
            
            if ($devis) {
                $devis->update(['status' => 'envoye']);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Email envoyé avec succès!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send devis email', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false, 
                'message' => 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage() // إضافة رسالة الخطأ هنا
            ], 500);
        }
    }

    /**
     * Export devis to Excel
     */
    public function export(Request $request)
    {
        $type = $request->query('type', 'standard');

        if ($type === 'all') {
            $standard = Devis::where('type', 'standard')->orderBy('ref_id', 'asc')->get()->toArray();
            $specific = Devis::where('type', 'specific')->orderBy('ref_id', 'asc')->get()->toArray();

            return Excel::download(
                new \App\Exports\DevisMultiSheetExport($standard, $specific),
                'Devis_All_'.now()->format('Y-m-d').'.xlsx'
            );
        }

        $devis = Devis::where('type', $type)
                      ->orderBy('ref_id', 'asc')
                      ->get()
                      ->toArray();

        return Excel::download(
            new DevisExport($devis, $type),
            'Devis_'.$type.'_'.now()->format('Y-m-d').'.xlsx'
        );
    }

    /**
     * Export single devis to PDF with company branding
     */
    public function exportPdf($id)
    {
        $devis = Devis::where('ref_id', $id)->firstOrFail();
        $company = CompanyInfo::first();
        $candidate = ($company && $company->logo_path) ? $company->logo_path : 'images/Logo_ALCOIL_with_txt_b_org@3x.png';
        $logoSrc = null;

        try {
            // Priority 1: Check if it's a local file in public or storage
            $localPath = null;
            if (file_exists(public_path($candidate))) {
                $localPath = public_path($candidate);
            } elseif (file_exists(storage_path('app/public/' . $candidate))) {
                $localPath = storage_path('app/public/' . $candidate);
            }

            if ($localPath) {
                $mime = @mime_content_type($localPath) ?: 'image/png';
                $logoSrc = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($localPath));
            } elseif (preg_match('#^https?://#', $candidate)) {
                // Priority 2: External URL (Try to fetch with timeout)
                $ctx = stream_context_create(['http' => ['timeout' => 5]]);
                $img = @file_get_contents($candidate, false, $ctx);
                if ($img !== false) {
                    $ext = pathinfo(parse_url($candidate, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
                    $mime = $ext === 'svg' ? 'image/svg+xml' : ('image/' . $ext);
                    $logoSrc = 'data:' . $mime . ';base64,' . base64_encode($img);
                }
            }
        } catch (\Throwable $e) {
            Log::warning("PDF Export: Logo processing failed for {$candidate}. " . $e->getMessage());
        }

        // Final fallback to default local logo if nothing worked
        if (!$logoSrc) {
            $defaultLogo = public_path('images/Logo_ALCOIL_with_txt_b_org@3x.png');
            if (file_exists($defaultLogo)) {
                $mime = @mime_content_type($defaultLogo) ?: 'image/png';
                $logoSrc = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($defaultLogo));
            }
        }

        $data = ['devis' => $devis, 'logo' => $logoSrc, 'company' => $company];
        $view = view()->exists('admin.devis-pdf') ? 'admin.devis-pdf' : 'admin.pdf_devis';

        $pdf = Pdf::loadView($view, $data)
            ->setPaper('a4')
            ->setOptions([
                'isRemoteEnabled' => false, // Disable remote for better performance
                'isHtml5ParserEnabled' => true,
                'dpi' => 120,
                'defaultFont' => 'sans-serif',
                'enable_font_subsetting' => true,
                'isFontSubsettingEnabled' => true,
                'tempDir' => storage_path('app/temp')
            ]);

        return $pdf->download('Devis_' . $id . '.pdf');
    }
}