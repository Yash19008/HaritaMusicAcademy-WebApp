<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreditPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CreditPackageController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'credits' => ['required', 'integer', 'min:1'],
        ]);

        CreditPackage::create($data);

        return back()->with('success', 'Credit package created successfully.');
    }

    public function update(Request $request, CreditPackage $creditPackage): RedirectResponse
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'credits' => ['required', 'integer', 'min:1'],
        ]);

        $creditPackage->update($data);

        return back()->with('success', 'Credit package updated successfully.');
    }

    public function destroy(CreditPackage $creditPackage): RedirectResponse
    {
        $creditPackage->delete();
        return back()->with('success', 'Credit package removed.');
    }
}
