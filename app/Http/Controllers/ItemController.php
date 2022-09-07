<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('item.index', ["items" => Item::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('item.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            "name" => "required|unique:App\Models\Item,name",
            "sku" => "required|unique:App\Models\Item,sku",
            "description" => "between:0,300",
            "purchase_price" => "nullable|numeric",
            "selling_price" => "nullable|numeric",
            "minimum_stock" => "nullable|numeric",
        ]);

        if ($request->hasFile('image')) {
            $formFields['image'] = $request->file('image')->store('itemImages', 'public');
        }

        Item::create($formFields);

        return redirect(route('item.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        $item['image'] ?? url('/');
        return view('item.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        return view('item.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $formFields = $request->validate([
            "name" => "required",
            "sku" => "required",
            "description" => "between:0,300",
            "purchase_price" => "nullable|numeric",
            "selling_price" => "nullable|numeric",
            "minimum_stock" => "nullable|numeric",
        ]);

        if ($request->hasFile('image')) {
            $formFields['image'] = $request->file('image')->store('itemImages', 'public');
        }

        $item->update($formFields);

        return redirect(route('item.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect(route('item.index'));
    }
}
