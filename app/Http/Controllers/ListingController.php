<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use Illuminate\Contracts\Session\Session;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    // Show all listings
    public function index(Request $request) {
        // dd(Listing::latest()->filter(request(['tag','search']))->paginate(2));
        return view('listings.index', [
            'listings' => Listing::latest()->filter(request(['tag','search']))->paginate(6)
        ]);
    }
    // Show single listing
    public function show(Listing $listing) {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }
    public function create() {
        return view('listings.create');
    }
    public function store(Request $request) {
        // dd($request->file('logo')->store()); 
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings','company')],
            'website' => 'required',
            'email' => ['required','email'],
            'location' => 'required',
            'tags' => 'required',
            'description' => 'required'
        ]);
        if($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos','public');
        }
        Listing::create($formFields);
        
        return redirect('/')->with('message','Listing created successfully');
    }
}
