<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Mail\ContactUsMail;
use App\Mail\SenderContactUsMail;
use App\Models\ContactUs;
use App\Services\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends Controller
{
    public function create(ContactUsRequest $request)
    {
        try {
            
            $work = ContactUs::create($request->all());
            //work with us
            Mail::to('caio.torres@ateliedepropaganda.com.br')->send(new ContactUsMail($work));
    
            if ($request->email) {
                Mail::to($request->email)->send(new SenderContactUsMail());
            }
            
            return response()->json(['message' => 'Contato enviado com sucesso!'], 200);
        } catch (\Exception $e) {
        
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        $all = $this->handle(ContactUs::query());
        
        return $this->success($all, "Contact Us list");
    }


    public function update(Request $request, ContactUs $contactUs)
    {
        try{
            
            $files = $request->all();

            $contactUs -> update( $files );
    
            return $this->success($contactUs, 'Contato Us Alterado.');
    
        }catch(\Exception $e){
            Log::Error($e->getMessage(), $request->all());
            return $this->serverError(
                $e->getMessage(),
                'Erro ao atualizar contato.');
        }
    }
}
