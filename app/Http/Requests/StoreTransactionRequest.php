<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
{
    /*
    ^ array:4 [▼
        "_token" => "prmzobveAHbZhCfx77zWDQaGRa9HzXFpJqpYdjbe"
        "transaction_type" => "in"
        "item_ids" => array:3 [▼
            0 => "1"
            1 => "2"
            2 => "3"
        ]
        "item_quantities" => array:3 [▼
            0 => "5"
            1 => "5"
            2 => "4"
        ]
    ]
    */

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // TODO: maximum quantity (database may have issue, issue with big number)

        $rules = [
            "transaction_type" => ["required", Rule::in(['in', 'out', 'audit'])],
            "item_ids" => "required|min:1",
            "item_ids.*" => "required|numeric",
            "item_quantities" => "required|min:1",
            "item_quantities.*" => "required|numeric|min:1",
        ];

        // dont allow negative value in form except type = audit
        if ($this->input('transaction_type') == 'audit') {
            $rules['item_quantities.*'] = "required|numeric";
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'item_ids.*.required' => 'Product cannot be empty',
            'item_quantities.*.required' => 'Quantity cannot be empty',
            'item_quantities.*.min' => 'Quantity must be positive number',
        ];
    }
}
