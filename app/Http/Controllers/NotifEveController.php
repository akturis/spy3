<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NotifEve;
use Illuminate\Http\Request;
use Exception;

class NotifEveController extends Controller
{

    /**
     * Display a listing of the corporations.
     *
     * @return Illuminate\View\View
     */
	public function __construct()
	{
	    $this->middleware('director');
	}

    public function index()
    {
        $notifeveObjects = NotifEve::paginate(25);

        return view('notifeve.index', compact('notifeveObjects'));
    }

    /**
     * Show the form for creating a new corporations.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('notifeve.create');
    }

    /**
     * Store a new corporations in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            NotifEve::create($data);

            return redirect()->route('notificaton_eves.notificaton_eves.index')
                ->with('success_message', 'Notificator was successfully added.');
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors($exception->validator->errors());
        }
    }

    /**
     * Display the specified corporations.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $notifeves = NotifEve::findOrFail($id);

        return view('notifeve.show', compact('notifeves'));
    }

    /**
     * Show the form for editing the specified corporations.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $notifeves = NotifEve::findOrFail($id);
        

        return view('notifeve.edit', compact('notifeves'));
    }

    /**
     * Update the specified corporations in the storage.
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
            $corporations = NotidEve::findOrFail($id);
            $corporations->update($data);
            
            return redirect()->route('corporations.corporations.index')
                ->with('success_message', 'Corporations was successfully updated.');
        } catch (Exception $exception) {
            return back()
                ->withErrors($exception->validator->errors());
//                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }        
    }

    /**
     * Remove the specified corporations from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $corporations = Corporations::findOrFail($id);
            $corporations->delete();

            return redirect()->route('corporations.corporations.index')
                ->with('success_message', 'Corporations was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors($exception->validator->errors());
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
            'character_id' => 'nullable|numeric|min:0|max:2147483647',
            'enabled' => 'nullable|int|min:0|max:1|empty_field:token',
            'token' => 'nullable|string|min:0|max:255'
        ];
        
        $data = $request->validate($rules);

        return $data;
    }

}
