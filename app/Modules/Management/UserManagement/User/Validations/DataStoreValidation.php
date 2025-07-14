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
        // $isUpdate = $this->method() === 'PUT' || $this->method() === 'PATCH';
         
        // return [
        //     'name' => 'required | sometimes',
        //     'email' => 'required | sometimes',
        //     'password' => $isUpdate ? 'sometimes|nullable' : 'required',
        //     'image' => 'required | sometimes',
        //     'phone_number' => 'required | sometimes',
        //     'whatsapp' => 'required | sometimes',
        //     'telegram' => 'required | sometimes',
        //     'batch_id' => 'required | sometimes',
        //     'address' => 'required | sometimes',
        //     'role_id' => 'required | sometimes',
        //     'department' => 'required | sometimes',
        //     'status' => ['sometimes', Rule::in(['active', 'inactive'])],
        // ];

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

    

}