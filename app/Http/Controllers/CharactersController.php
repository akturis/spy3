<?php

namespace App\Http\Controllers;

use App\Http\Controllers\\Controller;
use App\Models\\Characters;
use Illuminate\Http\Request;
use Exception;

class CharactersController extends Controller
{

    /**
     * Display a listing of the characters.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $charactersObjects = Characters::paginate(25);

        return view('characters.index', compact('charactersObjects'));
    }

    /**
     * Show the form for creating a new characters.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('characters.create');
    }

    /**
     * Store a new characters in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            Characters::create($data);

            return redirect()->route('characters.characters.index')
                ->with('success_message', 'Characters was successfully added.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified characters.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $characters = Characters::findOrFail($id);

        return view('characters.show', compact('characters'));
    }

    /**
     * Show the form for editing the specified characters.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $characters = Characters::findOrFail($id);
        

        return view('characters.edit', compact('characters'));
    }

    /**
     * Update the specified characters in the storage.
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
            
            $characters = Characters::findOrFail($id);
            $characters->update($data);

            return redirect()->route('characters.characters.index')
                ->with('success_message', 'Characters was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }        
    }

    /**
     * Remove the specified characters from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $characters = Characters::findOrFail($id);
            $characters->delete();

            return redirect()->route('characters.characters.index')
                ->with('success_message', 'Characters was successfully deleted.');
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
                'name' => 'nullable|string|min:0|max:256',
            'startDateTime' => 'nullable|string|min:0',
            'roles' => 'nullable|string|min:0|max:20',
            'title' => 'nullable|string|min:0|max:512',
            'corporationID' => 'required|numeric|min:-2147483648|max:2147483647',
            'SS' => 'required|numeric|min:-999999.9999|max:999999.9999', 
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
