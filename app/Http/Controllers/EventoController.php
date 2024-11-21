<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventoController extends Controller
{
    public function index()
    {
        return response()->json(Evento::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('eventos', 'public');
            $fotoPath = asset('storage/' . $fotoPath);
        }

        $evento = Evento::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'foto' => $fotoPath,
        ]);

        return response()->json($evento, 201);
    }


    public function show(Evento $evento)
    {
        return response()->json($evento);
    }

    public function update(Request $request, Evento $evento)
    {
        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($evento->foto) {
                Storage::disk('public')->delete($evento->foto);
            }
            $evento->foto = $request->file('foto')->store('eventos', 'public');
        }

        $evento->update($request->only('nombre', 'descripcion'));

        return response()->json($evento);
    }

    public function destroy(Evento $evento)
    {
        if ($evento->foto) {
            Storage::disk('public')->delete($evento->foto);
        }

        $evento->delete();

        return response()->json(null, 204);
    }
}
