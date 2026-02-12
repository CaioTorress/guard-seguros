<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Mail\ContactUsMail;
use App\Mail\SenderContactUsMail;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends Controller
{
    public function create(ContactUsRequest $request)
    {
        try {
            
            $work = ContactUs::create($request->all());
            //work with us
            Mail::to('atelie@ateliedepropaganda.com.br')->send(new ContactUsMail($work));
    
            if ($request->email) {
                Mail::to($request->email)->send(new SenderContactUsMail());
            }
            
            return response()->json(['message' => 'Contato enviado com sucesso!'], 200);
        } catch (\Exception $e) {
        
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
