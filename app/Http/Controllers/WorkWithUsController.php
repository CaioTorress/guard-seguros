<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Http\Requests\WorkWithUsRequest;
use App\Mail\ConfirmationMail;
use App\Mail\SenderWorkMail;
use App\Mail\WorkWithUsMail;
use App\Models\WorkWithUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WorkWithUsController extends Controller
{
    public function create(WorkWithUsRequest $request)
    {
        try {
            $work = WorkWithUs::create($request->all());
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time(). '.'. $file->getClientOriginalExtension();
                $file->storeAs('public/work_with_us', $filename);
                $work->file = $filename;
                $work->save();
            }
            
            $mail = new WorkWithUsMail($work);
            if ($request->hasFile('file')) {
                $mail->attach(storage_path('app/public/work_with_us/'. $work->file));
            }
            Mail::to('atelie@ateliedepropaganda.com.br')->send($mail);
            
            if ($request->email) {
                $mail = new SenderWorkMail();
                if ($request->hasFile('file')) {
                    $mail->attach(storage_path('app/public/work_with_us/'. $work->file));
                }
                Mail::to($request->email)->send($mail);
            }
            
            return response()->json(['message' => 'Contato enviado com sucesso!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}