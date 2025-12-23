<?php

namespace App\Http\Controllers;

use App\Models\Load;
use App\Models\LoadDocument;
use App\Http\Requests\UploadDocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function upload(UploadDocumentRequest $request, Load $load)
    {
        $file = $request->file('document');
        
        // Generate unique filename
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('load_' . $load->id, $filename, 'documents');

        $document = LoadDocument::create([
            'load_id' => $load->id,
            'type' => $request->type,
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);

        return back()->with('success', 'Document uploaded successfully!');
    }

    public function download(LoadDocument $document)
    {
        // Authorization: Check if user can view this load
        if (!$this->canAccessDocument($document)) {
            abort(403, 'Unauthorized access to document');
        }

        return Storage::disk('documents')->download($document->path, $document->filename);
    }

    public function preview(LoadDocument $document)
    {
        // Authorization check
        if (!$this->canAccessDocument($document)) {
            abort(403, 'Unauthorized access to document');
        }

        // Stream for preview
        $file = Storage::disk('documents')->get($document->path);
        return response($file, 200)->header('Content-Type', $document->mime_type);
    }

    protected function canAccessDocument(LoadDocument $document): bool
    {
        $user = auth()->user();
        
        // Admins and dispatchers can access all documents
        if ($user->hasAnyRole(['Admin', 'Dispatcher'])) {
            return true;
        }

        // Drivers can only access documents for their assigned loads
        if ($user->hasRole('Driver')) {
            $load = $document->load;
            return $load->assigned_driver_id === $user->id;
        }

        return false;
    }
}
