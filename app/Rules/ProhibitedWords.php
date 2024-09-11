<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ProhibitedWords implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */

     protected $prohibitedWords = ['Upload', 'Screenshot', 'Evidence', 'Image', 'WhatsApp', 
    'UPLOAD', 'SCREENSHOT', 'EVIDENCE', 'IMAGE', 'WHATSAPP', 'upload', 'screenshot', 'evidence', 
    'image', 'whatsApp', 'phone', 'PHONE', 'Phone', 'phone number', 'Phone Number', 'PHONE NUMBER'];

    public function passes($attribute, $value)
    {
        foreach ($this->prohibitedWords as $word) {
            if (stripos($value, $word) !== false) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        // return 'We noticed you requested for screenshot/image upload as evidence but ypu did not tick the that allows image upload and proof, please';
        return 'Please tick the box to allow image upload for this job.';
    }
}
