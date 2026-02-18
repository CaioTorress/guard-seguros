<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Services\Log;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all = $this->handle(Contact::query());
        
        return $this->success($all, "Contatc list");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            
            $files = $request->all();

            $contact = Contact::create( $files );
    
            return $this->created($contact, 'Novo contato cadastrado.');
    
        }catch(\Exception $e){
            Log::Error($e->getMessage(), $request->all());
            return $this->serverError(
                $e->getMessage(),
                'Erro ao criar contato.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        return $this->success($contact, "Dados do contato.");
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        try{
            
            $files = $request->all();

            $contact -> update( $files );
    
            return $this->success($contact, 'Contato Alterado.');
    
        }catch(\Exception $e){
            Log::Error($e->getMessage(), $request->all());
            return $this->serverError(
                $e->getMessage(),
                'Erro ao atualizar contato.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return $this->deleted($contact, "Contato deletado.");
    }
}
