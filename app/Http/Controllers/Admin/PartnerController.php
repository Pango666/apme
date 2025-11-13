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
            // Permitimos # o vacíos; si quieres URL real, cambia por ->nullable()->url()
            'url'  => ['nullable','string','max:255'],
            'logo' => ['nullable','image','max:5120'], // 5MB
        ]);

        if ($r->hasFile('logo')) {
            $path = $r->file('logo')->store('partners', 'public'); // storage/app/public/partners/....
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
            'name' => ['required','string','max:180'],
            'url'  => ['nullable','string','max:255'],
            'logo' => ['nullable','image','max:5120'],
        ]);

        if ($r->hasFile('logo')) {
            // borrar anterior si era del storage
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
