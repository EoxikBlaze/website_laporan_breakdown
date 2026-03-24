<?php

namespace App\Http\Requests;

use App\Models\MasterUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreBreakdownLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'unit_id' => ['required', 'exists:master_units,id'],
            'spare_unit_id' => [
                'nullable',
                'exists:master_units,id',
                'different:unit_id', // spare_unit_id != unit_id
            ],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'waktu_awal_bd' => ['required', 'date'],
            'waktu_akhir_bd' => ['nullable', 'date', 'after:waktu_awal_bd'], // waktu_akhir_bd > waktu_awal_bd
            'status' => ['nullable', 'in:Open,Closed'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    /**
     * Custom validation for business logic: spare_unit_id status must be 'Ready'.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $spareUnitId = $this->input('spare_unit_id');

            if ($spareUnitId) {
                $spareUnit = MasterUnit::find($spareUnitId);

                if ($spareUnit && $spareUnit->status_operasional !== 'Ready') {
                    $validator->errors()->add(
                        'spare_unit_id',
                        "Unit pengganti (spare) tidak dapat digunakan karena statusnya adalah '{$spareUnit->status_operasional}'. Hanya unit dengan status 'Ready' yang diperbolehkan."
                    );
                }
            }
        });
    }

    /**
     * Custom messages for clarity.
     */
    public function messages(): array
    {
        return [
            'spare_unit_id.different' => 'Unit pengganti tidak boleh sama dengan unit yang rusak.',
            'waktu_akhir_bd.after' => 'Waktu akhir breakdown harus setelah waktu awal.',
            'waktu_spare_datang.after_or_equal' => 'Waktu spare datang tidak boleh mendahului waktu awal breakdown.',
        ];
    }
}
