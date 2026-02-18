<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Services\Log;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all = $this->handle(Comment::query());
        
        return $this->success($all, "Comment list");
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            
            $files = $request->all();

            $comment = Comment::create( $files );
    
            return $this->created($comment, 'Novo comentario cadastrado.');
    
        }catch(\Exception $e){
    
            Log::Error($e->getMessage(), $request->all());
            return $this->serverError(
                $e->getMessage(), 
                'Erro ao criar cliente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return $this->success($comment, "Dados do comentario.");   
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        try{
            
            $files = $request->all();

            $comment -> update( $files );
    
            return $this->success($comment, 'Comentario Alterado.');
    
        }catch(\Exception $e){
    
            Log::Error($e->getMessage(), $request->all());
            return $this->serverError(
                $e->getMessage(),
                'Erro ao atualizar prato do pedido.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return $this->deleted($comment, "Comentario deletado.");
    }
}
