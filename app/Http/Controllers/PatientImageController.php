<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientImage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PatientImageController extends Controller
{
    // ── Lista de imágenes del paciente ────────────────────────
    public function index(Patient $patient): View
    {
        $images = PatientImage::where('patient_id', $patient->id)
            ->with('uploadedBy')
            ->orderByDesc('taken_at')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('category');

        $totalImages = PatientImage::where('patient_id', $patient->id)->count();

        return view('patients.images.index', compact('patient', 'images', 'totalImages'));
    }

    // ── Formulario subir imagen ───────────────────────────────
    public function create(Patient $patient): View
    {
        return view('patients.images.create', compact('patient'));
    }

    // ── Guardar imagen ────────────────────────────────────────
    public function store(Request $request, Patient $patient): RedirectResponse
    {
        $request->validate([
            'images'    => 'required|array|min:1|max:10',
            'images.*'  => 'required|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:10240',
            'category'  => 'required|string',
            'title'     => 'nullable|string|max:150',
            'notes'     => 'nullable|string',
            'taken_at'  => 'nullable|date',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $count = 0;
        foreach($request->file('images') as $index => $file){
            $originalName = $file->getClientOriginalName();
            $extension    = $file->getClientOriginalExtension();
            $safeName     = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
            $fileName     = time().'_'.$index.'_'.$safeName.'.'.$extension;
            $folder       = 'patients/'.$patient->id.'/images';

            $path = $file->storeAs($folder, $fileName, 'public');

            PatientImage::create([
                'patient_id' => $patient->id,
                'user_id'    => $user->id,
                'title'      => $request->input('title') ?: $originalName,
                'category'   => $request->input('category'),
                'file_path'  => $path,
                'file_name'  => $originalName,
                'file_type'  => $file->getMimeType(),
                'file_size'  => $file->getSize(),
                'notes'      => $request->input('notes'),
                'taken_at'   => $request->input('taken_at') ?: today(),
            ]);

            $count++;
        }

        return redirect()
            ->route('patients.images.index', $patient)
            ->with('success', $count > 1
                ? "$count imágenes subidas correctamente."
                : 'Imagen subida correctamente.');
    }

    // ── Ver imagen individual ─────────────────────────────────
    public function show(Patient $patient, PatientImage $image): View
    {
        $prev = PatientImage::where('patient_id', $patient->id)
            ->where('id', '<', $image->id)
            ->orderByDesc('id')
            ->first();

        $next = PatientImage::where('patient_id', $patient->id)
            ->where('id', '>', $image->id)
            ->orderBy('id')
            ->first();

        return view('patients.images.show', compact('patient', 'image', 'prev', 'next'));
    }

    // ── Eliminar imagen ───────────────────────────────────────
    public function destroy(Patient $patient, PatientImage $image): RedirectResponse
    {
        Storage::disk('public')->delete($image->file_path);
        $image->delete();

        return redirect()
            ->route('patients.images.index', $patient)
            ->with('success', 'Imagen eliminada correctamente.');
    }

    // ── API: datos JSON para lightbox ─────────────────────────
    // ── API: datos JSON para lightbox ─────────────────────────
    public function data(Patient $patient): JsonResponse
    {
        $images = PatientImage::where('patient_id', $patient->id)
            ->orderByDesc('taken_at')
            ->get()
            ->map(function($img) {
                // Convertir imagen a base64 para evitar problemas de servidor en Windows
                $base64Url = null;
                if($img->is_image){
                    $filePath = storage_path('app/public/'.$img->file_path);
                    if(file_exists($filePath)){
                        $mime     = mime_content_type($filePath);
                        $data     = base64_encode(file_get_contents($filePath));
                        $base64Url = "data:{$mime};base64,{$data}";
                    }
                }

                return [
                    'id'       => $img->id,
                    'url'      => $base64Url ?? $img->url,
                    'title'    => $img->title,
                    'category' => $img->category_label,
                    'date'     => $img->taken_at?->format('d/m/Y'),
                    'is_image' => $img->is_image,
                    'download_url' => route('storage.serve', ['path' => $img->file_path]),
                ];
            });

        return response()->json($images);
    }
}