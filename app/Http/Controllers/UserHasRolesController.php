<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserHasRolesFormRequest;
use App\Models\Roles;
use App\Models\UserHasRoles;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Auth;


class UserHasRolesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct()
	{
	    $this->middleware('admin');
	}
	
    /**
     * Display a listing of the user has roles.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        if(Auth::user()->hasRole('super')) {
            $userHasRolesObjects = UserHasRoles::with('role','user')->paginate(25);
        } elseif(Auth::user()->hasRole('admin')) {
            $userHasRolesObjects = UserHasRoles::with('role','user')
                                ->where('role_id','<',2)->paginate(25);
        } else {
            $userHasRolesObjects = UserHasRoles::with('role','user')
                                ->where('role_id','=',1)->paginate(25);
        }

        return view('user_has_roles.index', compact('userHasRolesObjects'));
    }

    /**
     * Show the form for creating a new user has roles.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $Roles = Roles::pluck('name','id')->all();
        $Users = User::pluck('name','id')->all();
        
        return view('user_has_roles.create', compact('Roles','Users'));
    }

    /**
     * Store a new user has roles in the storage.
     *
     * @param App\Http\Requests\UserHasRolesFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(UserHasRolesFormRequest $request)
    {
        try {
            
            $data = $request->getData();
            
            UserHasRoles::create($data);

            return redirect()->route('user_has_roles.user_has_roles.store')
                ->with('success_message', 'User Has Roles was successfully added.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request 1.']);
        }
    }

    /**
     * Display the specified user has roles.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id,$role_id)
    {
        $userHasRoles = UserHasRoles::with('role','user')->where('role_id','=',$role_id)->where('user_id','=',$id)->first();

        return view('user_has_roles.show', compact('userHasRoles'));
    }

    /**
     * Show the form for editing the specified user has roles.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id,$role_id)
    {
        $userHasRoles = UserHasRoles::with('role','user')->where('role_id','=',$role_id)->where('user_id','=',$id)->first();
        $Roles = Roles::pluck('name','id')->all();
        $Users = User::where('id',$id)->pluck('name','id');

        return view('user_has_roles.edit', compact('userHasRoles','Roles','Users'));
    }

    /**
     * Update the specified user has roles in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\UserHasRolesFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, UserHasRolesFormRequest $request)
    {
        try {
            
            $role_id = $request->route()->parameter('Roles');
            $user_id = $request->route()->parameter('userHasRoles');
            $data = $request->getData();
            $userHasRoles = UserHasRoles::where('role_id','=',$role_id)->where('user_id','=',$user_id)->delete();
            UserHasRoles::create($data);

            return redirect()->route('user_has_roles.user_has_roles.index')
                ->with('success_message', 'User Has Roles was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request 2.'.$exception->getMessage()]);
        }        
    }

    /**
     * Remove the specified user has roles from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy(Request $request)
    {
        $role_id = $request->input('role_id');
        $user_id = $request->input('user_id');
        try {
            $userHasRoles = UserHasRoles::where('role_id',$role_id)->where('user_id',$user_id)->delete();
            return redirect()->route('user_has_roles.user_has_roles.index')
                ->with('success_message', 'User Has Roles was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('role_id', function (Builder $builder) {
            $builder->where('role_id', '=', 1);
        });
    }

    
}


