<?php

namespace App\Domains\Contact\Models;

use Database\Factories\ContactPhoneFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactPhone extends Model
{
    use HasFactory;

    public $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'number',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory()
    {
        return ContactPhoneFactory::new();
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
