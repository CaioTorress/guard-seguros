<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Services\Log;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all = $this->handle(Banner::query());
        
        return $this->success($all, "Banner list");
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
           
            $fields = $request->all();

            
            $company = Banner::create($fields);

            if ($request->hasFile('image_desktop') || $request->hasFile('image_mobile')) {
                $company->storageBannerFiles()->save();
            }


            Log::Info(Banner::class,
                'Banner created.',
            );

            return $this->created($company, 'Nova banner cadastrado');
        } catch (\Exception $e) {
            Log::Error($e->getMessage(), $request->all());
            return $this->serverError(
                $e->getMessage(),
                'Erro ao criar banner'
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        return $this->success($banner, "Dados do banner.");

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        try {
            $files = $request->all();

            if ($request->hasFile('image_desktop') || $request->hasFile('image_mobile')) {
                $banner->storageBannerFiles();
                $files['image_desktop'] = $banner->image_desktop;
                $files['image_mobile'] = $banner->image_mobile;
            }

            $banner->update($files);

            return $this->success($banner->fresh(), 'Banner alterado.');
    
        }catch(\Exception $e){
    
            Log::Error($e->getMessage(), $request->all());
            return $this->serverError(
                $e->getMessage(), 
                'Erro ao atualizar banner');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();

        return $this->deleted($banner, "Banner deletado.");
    }
}
