<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryCategory;
use App\Models\Org;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventoryCategoriesExport;

class InventoryCategoryController extends Controller
{
    protected $_param;
    public $org;

    public function __construct()
    {
        $this->middleware('auth');
        $id = \Route::current()->parameter('id');
        $this->org = Org::find($id);  
    }

    /**
     * Mostrar todas las categorías.
     */
    public function index(Request $request)
    {
        $org = $this->org;  
    
        if (!$org) {
            return redirect()->route('orgs.index')->with('error', 'Organización no encontrada.');
        }
        
        $categories = InventoryCategory::where('org_id',$org->id)->orderBy('name', 'asc')->paginate(10);  
    
        return view('orgs.inventories.categories.index', compact('categories', 'org'));
    }
    /**
     * Mostrar el formulario de creación de categorías.
     */
    public function create($id)
    {
        $org = $this->org;
        return view('orgs.inventories.categories.create', compact('org'));
    }
    
    /**
     * Almacenar una nueva categoría.
     */
    public function store(Request $request, $id)
    {
        $org = $this->org;
        $validated = $request->validate([
            'name' => 'required|string|max:255',  
            'active' => 'required|boolean',      
        ]);

        $category = new InventoryCategory();
        $category->org_id = $org->id;
        $category->name = $validated['name'];  
        $category->active = $validated['active'];  
        $category->save();  
    
        return redirect()->route('orgs.categories.index', $org->id)
                         ->with('success', 'Categoría creada exitosamente.');
    }
    /**
    * Mostrar el formulario de edición de una categoría existente.
     */
    public function edit($id, $categoryId)
    {
        $org = Org::findOrFail($id);  
        $category = InventoryCategory::findOrFail($categoryId);  
    
        return view('orgs.inventories.categories.edit', compact('org', 'category'));
    }

    /**
     * Actualizar una categoría existente.
     */
    public function update(Request $request, $id, $categoryId)
    {
        $category = InventoryCategory::findOrFail($categoryId);  
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);
    
        $category->name = $validated['name'];
        $category->active = $validated['active'];
        $category->save(); 
    
        return redirect()->route('orgs.categories.index', $id)
                         ->with('success', 'Categoría actualizada exitosamente.');
    }
    /**
     * Eliminar una categoría.
     */
    public function destroy($id, $categoryId)
    {
        $org = Org::findOrFail($id);
        $category = InventoryCategory::findOrFail($categoryId);  
        $category->delete(); 
    
        return redirect()->route('orgs.categories.index', $org->id)
                         ->with('success', 'Categoría eliminada exitosamente.');
    }
    
    public function export() 
    {
        return Excel::download(new InventoryCategoriesExport, 'Categoria_Inventario-'.date('Ymdhis').'.xlsx');
    }
}

