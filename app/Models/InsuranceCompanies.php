<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceCompanies extends Model
{
    //
    protected $fillable = [
        'name',
        'image',
        'description',
        'type',
        'is_active'
    ];

    public function storageFile($field)
    {
        $file = request()->file($field);
        if (!$file) {
            return $this;
        }

        $stored = $file->storePublicly('public/insurance-companies');
        $this->$field = str_replace('public', 'storage', $stored);
    }

    public function storageInsuranceCompaniesFiles(): self
    {
        $this->storageFile('image');
        return $this;
    }
}
