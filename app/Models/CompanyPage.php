<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class CompanyPage extends Model
{
    use HasFactory;

    /** @var bool $timestamps */
    public $timestamps = false;

    /** @var string $table */
    protected $table = 'company_pages';

    /** @var array $guarded */
    protected $guarded = ['id'];

    /** @var array $casts */
    protected $casts = [
        'page_config' => 'json'
    ];

    /**
     * @return MorphTo
     */
    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
