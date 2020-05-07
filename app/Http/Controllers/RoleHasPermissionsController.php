<?php

namespace App\Http\Controllers;

use App\Http\Controllers\\Controller;
use App\Models\Role;
use App\Models\\RoleHasPermissions;
use Illuminate\Http\Request;
use Exception;

class RoleHasPermissionsController extends Controller
{

    /**
     * Display a listing of the role has permissions.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $roleHasPermissionsObjects = RoleHasPermissions::with('role')->paginate(25);

        return view('role_has_permissions.index', compact('roleHasPermissionsObjects'));
    }

    /**
     * Show the form for creating a new role has permissions.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $Roles = Role::pluck('name','id')->all();
        
        return view('role_has_permissions.create', compact('Roles'));
    }

    /**
     * Store a new role has permissions in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            RoleHasPermissions::create($data);

            return redirect()->route('role_has_permissions.role_has_permissions.index')
                ->with('success_message', 'Role Has Permissions was successfully added.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified role has permissions.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $roleHasPermissions = RoleHasPermissions::with('role')->findOrFail($id);

        return view('role_has_permissions.show', compact('roleHasPermissions'));
    }

    /**
     * Show the form for editing the specified role has permissions.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $roleHasPermissions = RoleHasPermissions::findOrFail($id);
        $Roles = Role::pluck('name','id')->all();

        return view('role_has_permissions.edit', compact('roleHasPermissions','Roles'));
    }

    /**
     * Update the specified role has permissions in the storage.
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
            
            $roleHasPermissions = RoleHasPermissions::findOrFail($id);
            $roleHasPermissions->update($data);

            return redirect()->route('role_has_permissions.role_has_permissions.index')
                ->with('success_message', 'Role Has Permissions was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }        
    }

    /**
     * Remove the specified role has permissions from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $roleHasPermissions = RoleHasPermissions::findOrFail($id);
            $roleHasPermissions->delete();

            return redirect()->route('role_has_permissions.role_has_permissions.index')
                ->with('success_message', 'Role Has Permissions was successfully deleted.');
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
                'role_id' => 'required', 
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
