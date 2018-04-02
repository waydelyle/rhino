<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Client
 * @package App
 */
class Client extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'publication',
        'emails',
        'phone_numbers',
        'join_date',
    ];

    /**
     * Json decode emails.
     *
     * @param string $value
     * @return array
     */
    public function getEmailsAttribute(string $value): array
    {
        return json_decode($value);
    }

    /**
     * Json decode phone numbers.
     *
     * @param string $value
     * @return array
     */
    public function getPhoneNumbersAttribute(string $value): array
    {
        return json_decode($value);
    }
}
