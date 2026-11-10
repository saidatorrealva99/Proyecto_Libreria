<?php

namespace App\Http\Controllers;

use App\Models\Autore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AutoreRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AutoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $autores = Autore::paginate();

        return view('autore.index', compact('autores'))
            ->with('i', ($request->input('page', 1) - 1) * $autores->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $autore = new Autore();

        return view('autore.create', compact('autore'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AutoreRequest $request): RedirectResponse
    {
        Autore::create($request->validated());

        return Redirect::route('autores.index')
            ->with('success', 'Autore created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $autore = Autore::find($id);

        return view('autore.show', compact('autore'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $autore = Autore::find($id);

        return view('autore.edit', compact('autore'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AutoreRequest $request, Autore $autore): RedirectResponse
    {
        $autore->update($request->validated());

        return Redirect::route('autores.index')
            ->with('success', 'Autore updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Autore::find($id)->delete();

        return Redirect::route('autores.index')
            ->with('success', 'Autore deleted successfully');
    }
}
