<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::all();
        return response()->json($purchases, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'material_id' => 'required|exists:materials,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'unit' => 'required|string|max:255',
            'purchase_date' => 'required|date',
        ]);

        $purchase = Purchase::create($validated);

        return response()->json($purchase, 201);
    }

    public function show($id)
    {
        $purchase = Purchase::findOrFail($id);
        return response()->json($purchase, 200);
    }

    public function update(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);

        $validated = $request->validate([
            'project_id' => 'sometimes|required|exists:projects,id',
            'material_id' => 'sometimes|required|exists:materials,id',
            'supplier_id' => 'sometimes|required|exists:suppliers,id',
            'price' => 'sometimes|required|numeric',
            'quantity' => 'sometimes|required|numeric',
            'unit' => 'sometimes|required|string|max:255',
            'purchase_date' => 'sometimes|required|date',
        ]);

        $purchase->update($validated);

        return response()->json($purchase, 200);
    }

    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->delete();

        return response()->json(null, 204);
    }
}
