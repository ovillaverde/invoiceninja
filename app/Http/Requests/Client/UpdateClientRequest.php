<?php
/**
 * Invoice Ninja (https://invoiceninja.com)
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2019. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/AAL
 */

namespace App\Http\Requests\Client;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize() : bool
    {
        return auth()->user()->can('edit', $this->client);
    }

    public function rules()
    {
        /* Ensure we have a client name, and that all emails are unique*/
        $rules['name'] = 'required';

        $rules['industry_id'] = 'integer|nullable';
        $rules['size_id'] = 'integer|nullable';
        $rules['currency_id'] = 'integer|nullable';
        $rules['country_id'] = 'integer|nullable';
        $rules['shipping_country_id'] = 'integer|nullable';

        $contacts = request('contacts');

            if(is_array($contacts))
            {
                for ($i = 0; $i < count($contacts); $i++) {
                    $rules['contacts.' . $i . '.email'] = 'required|email|unique:client_contacts,email,' . $contacts[$i]['id'];
                }
            }
            return $rules;
            

    }

    public function messages()
    {
        return [
            'unique' => ctrans('validation.unique', ['attribute' => 'email']),
            'email' => ctrans('validation.email', ['attribute' => 'email']),
            'name.required' => ctrans('validation.required', ['attribute' => 'name']),
            'required' => ctrans('validation.required', ['attribute' => 'email']),
        ];
    }


}