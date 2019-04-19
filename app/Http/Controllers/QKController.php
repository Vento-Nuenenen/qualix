<?php

namespace App\Http\Controllers;

use App\Http\Requests\QKRequest;
use App\Models\Kurs;
use App\Models\QK;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;

class QKController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        return view('admin.qk');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QKRequest $request
     * @param Kurs $kurs
     * @return RedirectResponse
     */
    public function store(QKRequest $request, Kurs $kurs) {
        QK::create(array_merge($request->validated(), ['kurs_id' => $kurs->id]));
        return Redirect::route('admin.qk', ['kurs' => $kurs->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Kurs $kurs
     * @param QK $qk
     * @return Response
     */
    public function edit(Kurs $kurs, QK $qk) {
        return view('admin.qk-edit', ['qk' => $qk]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param QKRequest $request
     * @param Kurs $kurs
     * @param QK $qk
     * @return RedirectResponse
     */
    public function update(QKRequest $request, Kurs $kurs, QK $qk) {
        $qk->update($request->validated());
        $request->session()->flash('alert-success', __('Qualikategorie erfolgreich gespeichert.'));
        return Redirect::route('admin.qk', ['kurs' => $kurs->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Kurs $kurs
     * @param QK $qk
     * @return RedirectResponse
     */
    public function destroy(Request $request, Kurs $kurs, QK $qk) {
        $qk->delete();
        $request->session()->flash('alert-success', __('Qualikategorie erfolgreich gelöscht.'));
        return Redirect::route('admin.qk', ['kurs' => $kurs->id]);
    }
}