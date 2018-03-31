<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /**
     * @var bool
     */
    public $timestamps = true;

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
     * @param string $value
     * @return array
     */
    public function getEmailsAttribute(string $value): array
    {
        return json_decode($value);
    }

    /**
     * @param string $value
     * @return array
     */
    public function getPhoneNumbersAttribute(string $value): array
    {
        return json_decode($value);
    }
}
