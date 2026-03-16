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
            'status' => ['sometimes', 'required', 'in:Open,Closed'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $spareUnitId = $this->input('spare_unit_id');
            $breakdownLog = $this->route('breakdown_log');

            if ($spareUnitId) {
                // If the spare unit is being changed to a different one, check if the new one is Ready
                // Or if it's the same one currently assigned, it's fine (as it might already be 'In Use' because of THIS log)
                if ($breakdownLog && $breakdownLog->spare_unit_id != $spareUnitId) {
                    $spareUnit = MasterUnit::find($spareUnitId);
                    if ($spareUnit && $spareUnit->status_operasional !== 'Ready') {
                        $validator->errors()->add(
                            'spare_unit_id',
                            "Unit pengganti baru tidak dapat digunakan karena statusnya adalah '{$spareUnit->status_operasional}'."
                        );
                    }
                }
            }
        });
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
