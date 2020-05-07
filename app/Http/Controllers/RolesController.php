<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use Illuminate\Http\Request;
use Exception;

class RolesController extends Controller
{

    /**
     * Display a listing of the roles.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $rolesObjects = Roles::paginate(25);

        return view('roles.index', compact('rolesObjects'));
    }

    /**
     * Show the form for creating a new roles.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('roles.create');
    }

    /**
     * Store a new roles in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            Roles::create($data);

            return redirect()->route('roles.roles.index')
                ->with('success_message', 'Roles was successfully added.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified roles.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $roles = Roles::findOrFail($id);

        return view('roles.show', compact('roles'));
    }

    /**
     * Show the form for editing the specified roles.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $roles = Roles::findOrFail($id);
        

        return view('roles.edit', compact('roles'));
    }

    /**
     * Update the specified roles in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            $roles = Roles::findOrFail($id);
            $roles->update($data);

            return redirect()->route('roles.roles.index')
                ->with('success_message', 'Roles was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }        
    }

    /**
     * Remove the specified roles from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $roles = Roles::findOrFail($id);
            $roles->delete();

            return redirect()->route('roles.roles.index')
                ->with('success_message', 'Roles was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    
    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request 
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
                'name' => 'required|string|min:1|max:255', 
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
