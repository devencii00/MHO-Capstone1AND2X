<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        if ($perPage < 1) {
            $perPage = 15;
        }
        if ($perPage > 100) {
            $perPage = 100;
        }

        $search = trim((string) $request->query('search', ''));

        return Medicine::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('generic_name', 'like', '%' . $search . '%')
                        ->orWhere('brand_name', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('generic_name')
            ->orderBy('brand_name')
            ->paginate($perPage);
    }

    public function show(Medicine $medicine)
    {
        return $medicine;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'generic_name' => ['required', 'string'],
            'brand_name' => ['nullable', 'string'],
            'indications' => ['nullable', 'string'],
            'contraindications' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $medicine = Medicine::create($data);

        return response()->json($medicine, 201);
    }

    public function update(Request $request, Medicine $medicine)
    {
        $data = $request->validate([
            'generic_name' => ['sometimes', 'string'],
            'brand_name' => ['sometimes', 'nullable', 'string'],
            'indications' => ['sometimes', 'nullable', 'string'],
            'contraindications' => ['sometimes', 'nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $medicine->update($data);

        return $medicine->refresh();
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return response()->json([
            'message' => 'Medicine deleted',
        ]);
    }
}
