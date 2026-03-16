<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'nama_vendor' => ['required', 'string', 'unique:vendors,nama_vendor'],
            'kontak' => ['nullable', 'string'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
