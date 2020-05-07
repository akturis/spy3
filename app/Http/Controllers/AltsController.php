<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Characters;
use App\Models\Alts;
use Illuminate\Http\Request;
use Exception;

class AltsController extends Controller
{

    /**
     * Display a listing of the alts.
     *
     * @return Illuminate\View\View
     */
	public function __construct()
	{
//	    $this->middleware('admin');
	    $this->middleware('director');
	}

    public function index()
    {
        $altsObjects = Alts::join('characters','characters.id','=','alts.id')->
                        select('characters.name','alts.*')->
                        orderBy('characters.name')->paginate(25);

        return view('alts.index', compact('altsObjects'));
    }

    /**
     * Show the form for creating a new alts.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $mains = Characters::orderBy('name','asc')->pluck('name','id')->all();
        
        return view('alts.create', compact('mains'));
    }

    /**
     * Store a new alts in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            Alts::create($data);

            return redirect()->route('alts.alts.index')
                ->with('success_message', 'Alts was successfully added.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified alts.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $alts = Alts::with('main')->findOrFail($id);
        $main = Characters::findOrFail($alts->main_id);
        
        $alts = $main->Alts;
//        dd($alts_);

        return view('alts.show', compact('alts','main'));
    }

    /**
     * Show the form for editing the specified alts.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $alts = Alts::findOrFail($id);
        $mains = Characters::pluck('name','id')->all();

        return view('alts.edit', compact('alts','mains'));
    }

    /**
     * Update the specified alts in the storage.
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
            
            $alts = Alts::findOrFail($id);
            $alts->update($data);

            return redirect()->route('alts.alts.index')
                ->with('success_message', 'Alts was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }        
    }

    /**
     * Remove the specified alts from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $alts = Alts::findOrFail($id);
            $alts->delete();

            return redirect()->route('alts.alts.index')
                ->with('success_message', 'Alts was successfully deleted.');
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
                'main_id' => 'required', 
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
