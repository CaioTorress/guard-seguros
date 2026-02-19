<?php

namespace App\Http\Controllers;

use App\Models\InsuranceCompanies;
use Illuminate\Http\Request;

class InsuranceCompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all = $this->handle(InsuranceCompanies::query());

        return $this->success($all, "Insurance Companies list");
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $fields = $request->all();

            $insuranceCompanies = InsuranceCompanies::create($fields);
            if ($request->hasFile('image')) {
                $insuranceCompanies->storageInsuranceCompaniesFiles()->save();
            }
            return $this->created($insuranceCompanies, "Insurance Company created");
        } catch (\Exception $e) {
            return $this->serverError($e->getMessage(), "Error creating Insurance Company");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(InsuranceCompanies $insuranceCompanies)
    {
        return $this->success($insuranceCompanies, "Insurance Company details");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InsuranceCompanies $insuranceCompanies)
    {
        try{
            $fields = $request->all();
            $insuranceCompanies->update($fields);
            return $this->success($insuranceCompanies, "Insurance Company updated");
        }catch(\Exception $e){
            return $this->serverError($e->getMessage(), "Error updating Insurance Company");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InsuranceCompanies $insuranceCompanies)
    {
        try{
            $insuranceCompanies->delete();
            return $this->deleted($insuranceCompanies, "Insurance Company deleted");
        }catch(\Exception $e){
            return $this->serverError($e->getMessage(), "Error deleting Insurance Company");
        }
    }
}
