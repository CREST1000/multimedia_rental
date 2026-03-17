<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Category;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipment = Equipment::with('category')->get();
        return view('equipment.index', compact('equipment'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('equipment.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required',
            'daily_rate' => 'required|numeric|min:0',
            'quantity_available' => 'required|integer|min:0',
            'condition' => 'required',
        ]);

        Equipment::create($request->all());

        return redirect()->route('equipment.index')
            ->with('success', 'Equipment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipment $equipment)
    {
        return view('equipment.show', compact('equipment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipment $equipment)
    {
        $categories = Category::all();
        return view('equipment.edit', compact('equipment', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipment $equipment)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required',
            'daily_rate' => 'required|numeric|min:0',
            'quantity_available' => 'required|integer|min:0',
            'condition' => 'required',
        ]);

        $equipment->update($request->all());

        return redirect()->route('equipment.index')
            ->with('success', 'Equipment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return redirect()->route('equipment.index')
            ->with('success', 'Equipment deleted successfully.');
    }
}
