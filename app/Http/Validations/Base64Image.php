<?php

namespace App\Http\Validations;

use Illuminate\Contracts\Validation\Rule;
use Intervention\Image\Facades\Image;

class Base64Image implements Rule
{

    private $errorMessage;

    public function __construct(string $errorMessage = ':attribute not is a valid base 64 image.')
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $validMime = true;
        
        if (is_array($value)) {
            $this->errorMessage = 'Sent files are not images.';
            foreach ($value as $item) {
                if (!isset($item['id'])) {
                    $decoded = $this->decodeBase64($item['image_url']);
                    $validMime = $this->validaMime($decoded);
                }
                if (!$validMime) {
                    return $validMime;
                }
            }
        }

        return $validMime;
    }

    /**
     * @param $value
     * @return bool|string
     */
    private function decodeBase64($value)
    {
        $string = strpos($value, ",") === false ? $value : substr($value, strpos($value, ",") + 1);
        $decoded = base64_decode($string);
        return $decoded;
    }

    /**
     * @param $decoded
     * @return bool
     */
    private function validaMime($decoded): bool
    {
        try {
            $image = Image::make($decoded);

            return in_array($image->mime, [
                'image/jpg',
                'image/png',
                'image/gif',
                'image/jpeg'
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }
}
