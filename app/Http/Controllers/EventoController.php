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

        // en la tabla Eventos no se guarda la foto en sí,
        // sino su ruta para accederla desde fuera de la API.
        $fotoPath = null;
        if ($request->hasFile('foto')) {

            // se guarda la foto en la carpeta storage/app/public/eventos
            $fotoPath = $request->file('foto')->store('eventos', 'public');

            // se arma la ruta completa para poder accederla desde {url_de_la_API}/storage/{ruta_de_la_foto}.
            // ej: http://localhost:8000/storage/eventos/imagen.jpg
            $fotoPath = asset('storage/' . $fotoPath);
        }

        // creación común de recurso
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

        $fotoPath = null;
        if ($request->hasFile('foto')) { // se revisa si el Request contiene una foto porque se definió como opcional

            // si el evento ya tenía una foto, se elimina del almacenamiento
            if ($evento->foto) {
                Storage::disk('public')->delete($evento->foto);
            }

            // se hace el mismo proceso que en store()
            $fotoPath = $request->file('foto')->store('eventos', 'public');
            $fotoPath = asset('storage/' . $fotoPath);

            // actualizamos la ruta en el evento
            $evento->foto = $fotoPath;
        }

        // actualizamos los demás campos
        $evento->nombre = $request->nombre;
        if ($request->has('descripcion')) { $evento->nombre = $request->nombre; }

        $evento->save();

        return response()->json($evento);
    }

    public function destroy(Evento $evento)
    {
        // al eliminar un evento, también se elimina su foto del almacenamiento (si tenía)
        if ($evento->foto) {
            Storage::disk('public')->delete($evento->foto);
        }

        $evento->delete();

        return response()->json(null, 204);
    }
}
