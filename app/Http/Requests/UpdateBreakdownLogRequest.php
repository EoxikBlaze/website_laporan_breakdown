<?php

namespace App\Http\Requests;

use App\Models\MasterUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateBreakdownLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'unit_id' => ['sometimes', 'required', 'exists:master_units,id'],
            'spare_unit_id' => [
                'nullable',
                'exists:master_units,id',
                'different:unit_id',
            ],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'waktu_awal_bd' => ['sometimes', 'required', 'date'],
            'waktu_akhir_bd' => ['nullable', 'date', 'after:waktu_awal_bd'],
            'waktu_spare_datang' => ['nullable', 'date', 'after_or_equal:waktu_awal_bd'],
            'status' => ['nullable', 'in:Open,Closed'],
            'keterangan' => ['nullable', 'string'],
        ];
    }


    public function messages(): array
    {
        return [
            'spare_unit_id.different' => 'Unit pengganti tidak boleh sama dengan unit yang rusak.',
            'waktu_akhir_bd.after' => 'Waktu akhir breakdown harus setelah waktu awal.',
            'waktu_spare_datang.after_or_equal' => 'Waktu spare datang tidak boleh mendahului waktu awal breakdown.',
        ];
    }
}
