<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Company
 *
 * @property int $id
 * @property int $customer_id
 * @property string $name
 * @property int|null $package_id
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Company extends Model
{
    use HasFactory;

	protected $table = 'companies';

	protected $casts = [
		'customer_id' => 'int',
		'package_id' => 'int'
	];

	protected $fillable = [
		'customer_id',
		'name',
		'package_id',
		'status'
	];

    /**
     * Get the package that owns the company.
     *
     * Syntax: return $this->belongsTo(Package::class, 'foreign_key', 'owner_key');
     *
     * Example: return $this->belongsTo(Package::class, 'package_id', 'id');
     *
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function instance(): HasMany
    {
        return $this->hasMany(Instance::class);
    }
}
