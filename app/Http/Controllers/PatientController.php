<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PatientController extends Controller
{
    // ─── Lista de pacientes ───────────────────────────────────

    public function index(Request $request): View
    {
        $query = Patient::query();

        // ── Búsqueda inteligente ──────────────────────────────────
        if($search = $request->get('search')){
            $terms = preg_split('/\s+/', trim($search));

            $query->where(function($q) use ($terms, $search) {

                // Búsqueda por término completo primero
                $q->where(function($q2) use ($search) {
                    $q2->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?",
                                ['%'.$search.'%'])
                    ->orWhere('cedula', 'like', '%'.$search.'%')
                    ->orWhere('phone',  'like', '%'.$search.'%')
                    ->orWhere('email',  'like', '%'.$search.'%');
                });

                // Si hay múltiples términos: buscar cada uno en nombre Y apellido
                if(count($terms) > 1){
                    $q->orWhere(function($q2) use ($terms) {
                        foreach($terms as $term){
                            $q2->where(function($q3) use ($term) {
                                $q3->where('first_name', 'like', $term.'%')
                                ->orWhere('last_name',  'like', $term.'%')
                                ->orWhere('first_name', 'like', '%'.$term.'%')
                                ->orWhere('last_name',  'like', '%'.$term.'%');
                            });
                        }
                    });
                } else {
                    // Un solo término: buscar en nombre, apellido o concatenado
                    $q->orWhere('first_name', 'like', $search.'%')
                    ->orWhere('last_name',  'like', $search.'%')
                    ->orWhere('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name',  'like', '%'.$search.'%');
                }
            });
        }

        // ── Filtro por ciudad ─────────────────────────────────────
        if($city = $request->get('city')){
            $query->where('city', $city);
        }

        // ── Filtro por género ─────────────────────────────────────
        if($gender = $request->get('gender')){
            $query->where('gender', $gender);
        }

        // ── Filtro por rango de edad ──────────────────────────────
        if($ageMin = $request->get('age_min')){
            $query->where(function($q) use ($ageMin) {
                $q->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= ?', [$ageMin])
                ->orWhere('age', '>=', $ageMin);
            });
        }

        if($ageMax = $request->get('age_max')){
            $query->where(function($q) use ($ageMax) {
                $q->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) <= ?', [$ageMax])
                ->orWhere('age', '<=', $ageMax);
            });
        }

        // ── Filtro por fecha de registro ──────────────────────────
        if($dateFrom = $request->get('date_from')){
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if($dateTo = $request->get('date_to')){
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // ── Ordenamiento ──────────────────────────────────────────
        $orderBy  = $request->get('order_by',  'created_at');
        $orderDir = $request->get('order_dir', 'desc');

        $allowedOrders = ['created_at','first_name','last_name','city'];
        if(!in_array($orderBy, $allowedOrders)) $orderBy = 'created_at';
        if(!in_array($orderDir, ['asc','desc'])) $orderDir = 'desc';

        if($orderBy === 'first_name' || $orderBy === 'last_name'){
            $query->orderBy('first_name', $orderDir)
                ->orderBy('last_name',  $orderDir);
        } else {
            $query->orderBy($orderBy, $orderDir);
        }

        $patients = $query->paginate(20)->withQueryString();

        // ── Datos para filtros de ciudad ──────────────────────────
        $cities = Patient::select('city')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        $totalCount = Patient::count();

        return view('patients.index', compact(
            'patients', 'cities', 'totalCount'
        ));
    }

    // ─── Formulario de creación ───────────────────────────────

    public function create(): View
    {
        return view('patients.create');
    }

    // ─── Guardar nuevo paciente ───────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePatient($request);

        $patient = Patient::create($data);

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Paciente registrado correctamente.');
    }

    // ─── Ficha del paciente ───────────────────────────────────

    public function show(Patient $patient): View
    {
        $patient->load(['budgets', 'paymentControls']);

        return view('patients.show', compact('patient'));
    }

    // ─── Formulario de edición ────────────────────────────────

    public function edit(Patient $patient): View
    {
        return view('patients.edit', compact('patient'));
    }

    // ─── Actualizar paciente ──────────────────────────────────

    public function update(Request $request, Patient $patient): RedirectResponse
    {
        $data = $this->validatePatient($request, $patient->id);

        $patient->update($data);

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Paciente actualizado correctamente.');
    }

    // ─── Eliminar paciente (soft delete) ─────────────────────

    public function destroy(Patient $patient): RedirectResponse
    {
        $patient->delete();

        return redirect()
            ->route('patients.index')
            ->with('success', 'Paciente eliminado correctamente.');
    }

    // ─── Validación centralizada ──────────────────────────────

    private function validatePatient(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'first_name'                  => 'required|string|max:100',
            'last_name'                   => 'required|string|max:100',
            'cedula'                      => 'nullable|string|max:20|unique:patients,cedula,' . $ignoreId,
            'birth_date'                  => 'nullable|date',
            'age'                         => 'nullable|integer|min:0|max:150',
            'gender'                      => 'nullable|in:masculino,femenino,otro',
            'phone'                       => 'nullable|string|max:20',
            'phone_whatsapp'              => 'nullable|string|max:20',
            'email'                       => 'nullable|email|max:100',
            'address'                     => 'nullable|string|max:255',
            'city'                        => 'nullable|string|max:100',
            'representative_name'         => 'nullable|string|max:150',
            'representative_cedula'       => 'nullable|string|max:20',
            'representative_relationship' => 'nullable|string|max:50',
            'representative_phone'        => 'nullable|string|max:20',
            'reason_for_consultation'     => 'nullable|string|max:255',
            'allergies'                   => 'nullable|string',
            'pathologies'                 => 'nullable|string',
            'observations'                => 'nullable|string',
            'whatsapp_notifications'      => 'boolean',
        ]);
    }
}