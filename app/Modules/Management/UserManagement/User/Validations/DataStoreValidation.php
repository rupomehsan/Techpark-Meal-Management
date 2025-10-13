<?php

namespace App\Modules\Management\UserManagement\User\Validations;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class DataStoreValidation extends FormRequest
{
    /**
     * Determine if the  is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * validateError to make this request.
     */
    public function validateError($data)
    {
        $errorPayload =  $data->getMessages();
        return response(['status' => 'validation_error', 'errors' => $errorPayload], 422);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validateError($validator->errors()));
        if ($this->wantsJson() || $this->ajax()) {
            throw new HttpResponseException($this->validateError($validator->errors()));
        }
        parent::failedValidation($validator);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */

     
    public function rules(): array
    {
         return [
            'name' => 'sometimes|string',
            'email' => 'sometimes|email',
            'password' => 'sometimes|string|min:8', // remove 'required'
            'image' => 'sometimes|image',
            'phone_number' => 'sometimes|string',
            'batch_id' => 'sometimes|integer',
            'address' => 'sometimes|string',
            'role_id' => 'sometimes|integer',
            'department' => 'sometimes|string',
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
        ];
        
    }

    // public function rules(): array
    // {
    //     $rules = [
    //         'name' => 'sometimes|string',
    //         'email' => 'sometimes|email',
    //         'image' => 'sometimes|image',
    //         'phone_number' => 'sometimes|string',
    //         'batch_id' => 'sometimes|integer',
    //         'address' => 'sometimes|string',
    //         'role_id' => 'sometimes|integer',
    //         'department' => 'sometimes|string',
    //         'status' => ['sometimes', Rule::in(['active', 'inactive'])],
    //     ];

    //     // Create হলে password required
    //     if ($this->isMethod('post')) {
    //         $rules['password'] = 'required|string|min:8';
    //     } 
    //     // Update হলে password optional
    //     elseif ($this->isMethod('update')) {
    //         $rules['password'] = 'sometimes|string|min:8';
    //     }
    //     // elseif ($this->isMethod('put') || $this->isMethod('update')) {
    //     //     $rules['password'] = 'sometimes|string|min:8';
    //     // }

    //     return $rules;
    // }
    

}