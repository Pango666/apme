<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index()
    {
        $items = Partner::latest()->paginate(18);
        return view('admin.partners.index', compact('items'));
    }

    public function create()
    {
        $partner = new Partner();
        return view('admin.partners.form', compact('partner'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => ['required','string','max:180'],
            // URL informativa; si quieres validar URL real: 'nullable','url'
            'url'  => ['nullable','string','max:255'],
            'logo' => ['nullable','image','max:5120'], // 5MB
        ]);

        if ($r->hasFile('logo')) {
            $path = $r->file('logo')->store('partners', 'public'); // partners/...
            $data['logo_path'] = "/storage/{$path}";
        }

        $partner = Partner::create($data);

        return redirect()
            ->route('admin.partners.edit', $partner)
            ->with('ok', 'Creado');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.form', compact('partner'));
    }

    public function update(Request $r, Partner $partner)
    {
        $data = $r->validate([
            'name'         => ['required','string','max:180'],
            'url'          => ['nullable','string','max:255'],
            'logo'         => ['nullable','image','max:5120'],
            'remove_logo'  => ['nullable','boolean'],
        ]);

        // Quitar logo si se marca
        if ($r->boolean('remove_logo') && $partner->logo_path && str_starts_with($partner->logo_path, '/storage/')) {
            $old = str_replace('/storage/', '', $partner->logo_path);
            Storage::disk('public')->delete($old);
            $data['logo_path'] = null;
        }

        // Reemplazar logo si suben archivo
        if ($r->hasFile('logo')) {
            if ($partner->logo_path && str_starts_with($partner->logo_path, '/storage/')) {
                $old = str_replace('/storage/', '', $partner->logo_path);
                Storage::disk('public')->delete($old);
            }
            $path = $r->file('logo')->store('partners', 'public');
            $data['logo_path'] = "/storage/{$path}";
        }

        $partner->update($data);

        return back()->with('ok', 'Guardado');
    }

    public function destroy(Partner $partner)
    {
        if ($partner->logo_path && str_starts_with($partner->logo_path, '/storage/')) {
            $old = str_replace('/storage/', '', $partner->logo_path);
            Storage::disk('public')->delete($old);
        }
        $partner->delete();
        return back()->with('ok', 'Eliminado');
    }
}
