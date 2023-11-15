<?php

namespace App\Http\Controllers;

use App\Activo;
use App\Area;
use App\Centro;
use App\Departamento;
use App\Estado;
use App\Item;
use App\Item_Potosi;
use App\Material;
use App\Movimiento;
use App\Tipos;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

require_once(public_path('phpqrcode/qrlib.php'));


class ItemController extends Controller
{
    public function create($id)
    {
        $tipoActivo = Tipos::all();
        $areas = Area::all();
        $analisis = Centro::all();
        $estados = Estado::all();
        if ($id === '0') {
            return view('items.create', compact('estados'))->with('analis', $analisis)->with('areas', $areas)->with('tipoActivo', $tipoActivo);;
        } else {
            $idarea = Area::find($id);
            return view('items.create', compact('estados'))->with('analis', $analisis)->with('areas', $areas)->with('tipoActivo', $tipoActivo)->with('idarea', $idarea);
        }
    }

    public function store(Request $request)
    {
        // Validate
        $this->validate($request, [
            'nombre' => 'required',
            'descripcion' => 'required',
            'id_area' => 'required',
            'id_tipo' => 'required',
            'id_estado' => 'required',
            'fecha' => 'required',
            'id_centro' => 'required',
        ]);
        $imageData = $request->input('image_data');
        $area = Area::find($request->id_area);
        // Datos del activo
        $coll = new Item();
        $coll->activo_id = $request->nombre;
        $coll->descripcion = $request->descripcion;
        $coll->area_id = $request->id_area;
        $coll->tipo_id = $request->id_tipo;
        $coll->estado_id = $request->id_estado;
        $coll->centro_id = $request->id_centro;
        $coll->modelo = $request->modelo;
        $coll->serie = $request->serie;
        $coll->fecha_compra = $request->fecha;
        //Codigo UPDS
        $tipo = Tipos::find($request->id_tipo);
        $nombreActivo = Activo::find($request->nombre);
        $siglaActivo = strtoupper(substr($nombreActivo->activo, 0, 3));
        $elementosDeActivo = Item::where('area_id', $area->id)->where('activo_id', $request->nombre)->count();
        if ($elementosDeActivo == 0) {
            $num = 1; // Si no hay elementos en el área para el activo, comenzamos en 1.
        } else {
            $num = $elementosDeActivo + 1; // Si hay elementos, incrementamos en 1.
        }
        $coll->codigo = $area->sigla. '.' .$tipo->codigo . '.' . $siglaActivo . '.' . sprintf('%05d', $num);
        $user = User::find(auth()->user()->id);
        if ($imageData) {
            // Obtener la foto tomada por la cámara en formato base64
            $imagenBase64 = $request->input('image_data');
            $imagenCodificadaLimpia = str_replace("data:image/png;base64,", "", urldecode($imagenBase64));
            $nombreImagen = 'camara_' . uniqid() . '.png';
            // Ruta de destino para guardar la imagen
            $rutaImagen = public_path('/img/fotos/'.$user->dep->sigla.'/' . $nombreImagen);
            // Decodifica la imagen base64 y obtén el tipo de contenido
            $imagenDecodificada = base64_decode($imagenCodificadaLimpia);
            $moved = file_put_contents($rutaImagen, $imagenDecodificada);
            if ($moved) {
                $coll->image = $nombreImagen;
                $coll->save();
            }
        } else {
            $file = $request->file('image');
            $path = public_path() . '/img/fotos/'.$user->dep->sigla;
            $fileName = uniqid() . '-' . $file->getClientOriginalName();
            $moved = $file->move($path, $fileName);
            if ($moved) {
                $coll->image = $fileName;
                $coll->save();
            }
        }
        $coll->save();
        // Redirect
        return redirect('/')->with('success', 'Activo registrado exitosamente');
    }

    public function show($id)
    {
        $databaseName = config('database.connections.' . config('database.default') . '.database');
        $depart = strtoupper(substr($databaseName, -2));
        $item = Item::find($id);
        ob_start();
        $url = url('vistaQR/' . $item->id . ($depart == 'PO' ? '' : '/' . $depart));
        QRcode::png($url);
        $qrImage = ob_get_clean();
        return view('items.show', ['item' => $item, 'qrImage' => $qrImage]);
    }

    public function edit($id)
    {
        $tipoActivo = Tipos::all();
        $analis = Centro::all();
        $estados = Estado::all();
        $item = Item::find($id);
        $activos = Activo::all();
        // retrieve areas
        $areas = Area::all();
        return view('items.edit', compact('estados'))
                    ->with('item', $item)
                    ->with('analis', $analis)
                    ->with('areas', $areas)
                    ->with('activos', $activos)
                    ->with('tipoActivo', $tipoActivo);
    }

    public function update(Request $request, $id)
    {
        // Validate
        $this->validate($request, [
            'nombre' => 'required',
            'id_area' => 'required'
        ]);
        $user = User::find(auth()->user()->id);
        $item = Item::find($id);
        $item->activo_id = $request->nombre;
        $item->descripcion = $request->descripcion;
        $item->estado_id = $request->id_estado;
        $item->centro_id = $request->id_centro;
        $item->modelo = $request->modelo;
        $item->serie = $request->serie;
        if ($item->area_id != $request->id_area) {
            $nomArea = Area::find($request->id_area);
            $movi = new Movimiento();
            $movi->item_id = $id;
            $movi->descripcion = 'Se movio de '.$item->area->nombre.' a '.$nomArea->nombre;
            $movi->save();
            $area = Area::find($request->id_area);
            $tipo = Tipos::find($request->id_tipo);
            $nombreActivo = Activo::find($request->nombre);
            $siglaActivo = strtoupper(substr($nombreActivo->activo, 0, 3));
            // Calcular el valor de $num como el máximo entre $elementosEnArea y $elementosDeTipo
            $elementosDeActivo = Item::where('area_id', $area->id)->where('activo_id', $request->nombre)->count();
            if ($elementosDeActivo == 0) {
                $num = 1; // Si no hay elementos en el área para el activo, comenzamos en 1.
            } else {
                $num = $elementosDeActivo + 1;
            }
            $item->codigo = $area->sigla. '.' .$tipo->codigo . '.' . $siglaActivo . '.' . sprintf('%05d', $num);
        }
        $item->area_id = $request->id_area;
        $item->tipo_id = $request->id_tipo;
        if ($request->fecha_compra != null) {
            $item->fecha_compra = $request->fecha_compra;
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = public_path() . '/img/fotos/'.$user->dep->sigla;
            $fileName = uniqid() . '-' . $file->getClientOriginalName();
            $moved = $file->move($path, $fileName);
            if ($moved) {
                $previousPath = $path . '/' . $item->image;
                $item->image = $fileName;
                $saved = $item->save();
                if ($saved)
                    File::delete($previousPath);
            }
        }
        $item->save();
        ob_start();  // Capturar la salida
        QRcode::png(url('vistaQR/' . $item->id));
        $qrImage = ob_get_clean();
        return view('items.show')->with('success', 'Activo actualizado correctamente.')->with('item', $item)->with('qrImage', $qrImage);
    }

    public function destroy(Request $request, $id)
    {
        $item = Item::find($id);
        if ($request->has('encargado') && $request->has('id_observacion')) {
            $item->user_baja = $request->encargado;
            $item->obserb_id = $request->id_observacion;
            $item->fecha_baja = Carbon::now();
            $item->estado = 0;
            $item->update();
            return redirect('/')->with('success', 'Mueble eliminado correctamente');
        } else {
            return redirect('/')->with('success', 'Error al dar de baja, No agrego todos los datos necesarios');
        }
    }

    public function delete($id, Request $request)
    {
        $item = Item::find($request->item_id);
        $item->delete();
        return redirect('admin/area/'.$item->area_id.'/show')->with('success', 'Item eliminado con éxito');
    }

    public function vistaQR($id, $sigla = null)
    {
        if ($sigla !== null && in_array($sigla, ['SU', 'TJ', 'LP', 'CB', 'OR', 'SC', 'PA', 'BE'])) {
            $databaseConnection = "departamento_" . strtolower($sigla);
            config(['database.default' => $databaseConnection]);
            DB::reconnect();
            $item = Item::find($id);
            if ($item == null) {
                return redirect('/');
            } else {
                return view('layouts.showqr', compact('sigla'))->with('item', $item);
            }
        } else if ($sigla == null) {
            $item = Item_Potosi::find($id);
            if ($item == null) {
                return redirect('/');
            } else {
                $sigla = 'PO';
                return view('layouts.showqr', compact('sigla'))->with('item', $item);
            }
        } else {
            return redirect('/');
        }
    }

    public function history($id)
    {
        $coll = Item::find($id);
        $hitory = Movimiento::where('item_id', $id)->get();
        return view('items.history')->with('hitory', $hitory)->with('item', $coll);
    }

    public function obtenerActivosPorTipo($tipoId)
    {
        if ($tipoId === 'ningun-activo') {
            return response()->json([]);
        }
        $activos = Activo::where('tipo_id', $tipoId)->get();
        return response()->json($activos);
    }

    public function otroMaterial($id) {
        $area = Area::find($id);
        return view('items.otro_material', compact('area'));
    }

    public function storeMaterial(Request $request) {
        // Validate
        $this->validate($request, [
            'nombre' => 'required',
            'descripcion' => 'required',
            'id_area' => 'required',
        ]);
        
        $imageData = $request->input('image_data');
        $coll = new Material();
        $coll->nombre = $request->nombre;
        $coll->descripcion = $request->descripcion;
        $coll->area_id = $request->id_area;
        $user = User::find(auth()->user()->id);
        if ($imageData) {
            $imagenBase64 = $request->input('image_data');
            $imagenCodificadaLimpia = str_replace("data:image/png;base64,", "", urldecode($imagenBase64));
            $nombreImagen = 'camara_' . uniqid() . '.png';
            $rutaImagen = public_path('/img/otros/'.$user->dep->sigla.'/' . $nombreImagen);
            $imagenDecodificada = base64_decode($imagenCodificadaLimpia);
            $moved = file_put_contents($rutaImagen, $imagenDecodificada);
            if ($moved) {
                $coll->image = $nombreImagen;
                $coll->save();
            }
        } else {
            $file = $request->file('image');
            $path = public_path() . '/img/otros/'.$user->dep->sigla;
            $fileName = uniqid() . '-' . $file->getClientOriginalName();
            $moved = $file->move($path, $fileName);
            if ($moved) {
                $coll->image = $fileName;
                $coll->save();
            }
        }
        $coll->save();
        return back()->with('success', 'Material registrado exitosamente');
    }

    public function showMaterial($id) {
        $otro = Material::find($id);
        return view('otros_materiales.show', compact('otro'));
    }
    
    public function editMaterial($id) {
        $material = Material::find($id);
        return view('otros_materiales.edit', compact('material'));
    }

    public function updateMaterial(Request $request, $id) {
        // Validate
        $this->validate($request, [
            'nombre' => 'required',
            'descripcion' => 'required',
            'id_area' => 'required',
        ]);
        // Update
        $item = Material::find($id);
        $item->nombre = $request->nombre;
        $item->descripcion = $request->descripcion;
        $item->area_id = $request->id_area;
        $user = User::find(auth()->user()->id);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = public_path() . '/img/otros/'.$user->dep->sigla;
            $fileName = uniqid() . '-' . $file->getClientOriginalName();
            $moved = $file->move($path, $fileName);
            if ($moved) {
                $previousPath = $path . '/' . $item->image;
                $item->image = $fileName;
                $saved = $item->save();
                if ($saved)
                    File::delete($previousPath);
            }
        }
        $item->save();
        return view('otros_materiales/show')->with('success', 'Activo actualizado correctamente.')->with('otro', $item);
    }

    public function deleteMaterial($id)
    {
        $item = Material::find($id);
        $item->delete();
        return redirect('admin/area/'.$item->area_id.'/show')->with('success', 'Item eliminado con éxito');
    }
}
