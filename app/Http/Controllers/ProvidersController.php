<?php

namespace App\Http\Controllers;

use App\Provider;
use App\Contact;
use App\Flag;
use App\Http\Requests\ProviderRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\FlagRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProvidersController extends Controller
{
    public function index()
    {
        $providers = Provider::all();
        return view('providers.index', compact('providers'));
    }

    public function show(Provider $provider)
    {
        //$provider = Provider::findOrFail($id);
        //Incrementing view count when viewed
        //app('App\Http\Controllers\ViewsController')->providerView($provider);

        return view('providers.show', compact('provider'));
    }

    public function create()
    {
        $contactList = $this->getAllContactsFullName();
        return view('providers.create', compact('contactList'));
    }

    public function store(ProviderRequest $request)
    {
        $provider = new Provider($request->all());
        $provider->save();
        $provider->contacts()->attach($request->input('contact_list'));

        \Session::flash('flash_message', 'Provider Created Successfully!');

        return redirect('providers');
    }

    public function edit(Provider $provider)
    {
        $contactList = $this->getAllContactsFullName();
        return view('providers.edit', compact('provider', 'contactList'));
    }

    public function update(Provider $provider, ProviderRequest $request)
    {
        $provider->update($request->all());
        $provider->contacts()->sync($request->input('contact_list'));

        \Session::flash('flash_message', 'Provider Updated Successfully!');
        return redirect('/providers/' . $provider->id);
    }

    public function destroy(Provider $provider)
    {
        foreach($provider->flags as $flag)
        {
            DB::table('archive_flags')->insert(
                ['id' => $flag->id,
                    'level' => $flag->level,
                    'comments' => $flag->comments,
                    'resolved' => $flag->resolved,
                    'submitted_by' => $flag->submitter->id,
                    'user_id' => $flag->userIdNumber,
                    'resource_id' => $flag->resourceIdNumber,
                    'contact_id' => $flag->contactIdNumber,
                    'provider_id' => $flag->providerIdNumber,
                    'event_id' => $flag->eventIdNumber,
                    'created_at' => $flag->created_at,
                    'updated_at' => $flag->updated_at,
                    'archived_at' => Carbon::now()->format('Y-m-d H:i:s')]
            );
        }
        foreach($provider->contacts as $contact)
        {
            DB::table('archive_contact_provider')->insert(
                [
                    'contact_id' => $contact->pivot->contact_id,
                    'provider_id' => $provider->id,
                    'title' => $contact->pivot->table,
                    'created_at' => $contact->pivot->created_at,
                    'updated_at' => $contact->pivot->updated_at,
                    'archived_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]
            );
        }
        DB::table('archive_providers')->insert(
            [
                'id' => $provider->id,
                'name' => $provider->name,
                'publicPhoneNumber' => $provider->publicPhoneNumber,
                'publicEmail' => $provider->publicEmail,
                'website' => $provider->website,
                'description' => $provider->description,
                'comments' => $provider->comments,
                'created_at' => $provider->created_at,
                'updated_at' => $provider->updated_at,
                'archived_at' => Carbon::now()->format('Y-m-d H:i:s')]
        );
        $provider->delete();
        \Session::flash('flash_message', 'Provider Deleted');
        return redirect('/providers');
    }

    public function getAllContactsFullName()
    {
        $allContacts = Contact::all();
        $passedContacts = array();
        foreach($allContacts as $contact)
        {
            $fullname = ucfirst($contact->firstName). ' ' . ucfirst($contact->lastName);
            $passedContacts[$contact->id] = $fullname;
        }
        return $passedContacts;
    }

    /*
    * This method takes in a provider, compacts it into a "common flag format" and sends it to the flag.create view
    */
    public function flag(Provider $provider)
    {
        return view('flags.create')->with('url', 'providers/flag/' . $provider->id)
            ->with('name', $provider->name);
    }

    public function storeFlag(Provider $provider, FlagRequest $request)
    {
        $flagData = ['level' => $request->level,
            'comments' => $request->comments,
            'resolved' => '0',
            'provider_id' => $provider->id,
            'submitted_by' => Auth::id()];
        $flag = new Flag($flagData);
        $flag->save();

        \Session::flash('flash_message', 'Flag Created Successfully!');

        return redirect('providers/'.$provider->id);
    }
}