<?php

namespace App\Domains\Contact\Models;

use Database\Factories\ContactFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model implements \JsonSerializable
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'address',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory()
    {
        return ContactFactory::new();
    }

    public function phones(): HasMany
    {
        return $this->hasMany(ContactPhone::class, 'contact_id', 'id');
    }

    public function getPhones()
    {
        return $this->phones->pluck('number')->implode(', ');
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phones' => $this->getPhones(),
            'address' => $this->address,
        ];
    }
}
