<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image_desktop',
        'image_mobile',
        'badge',
        'button_text',
        'button_link',
        'is_active'
    ];

    public function storageFile($field)
    {
        $file = request()->file($field);
        if (!$file) {
            return $this;
        }

        $stored = $file->storePublicly('public/banner');
        $this->$field = str_replace('public', 'storage', $stored);

        return $this;
    }

    /**
     * Armazena os arquivos de banner desktop e/ou mobile quando enviados.
     */
    public function storageBannerFiles(): self
    {
        $this->storageFile('image_desktop')->storageFile('image_mobile');
        return $this;
    }
}
