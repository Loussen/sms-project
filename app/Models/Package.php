<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Package
 *
 * @property int $id
 * @property string $name
 * @property int $limit
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Package extends Model
{
    use HasFactory;

	protected $table = 'packages';

	protected $casts = [
		'limit' => 'int'
	];

	protected $fillable = [
		'name',
		'limit',
		'status'
	];

    /**
     * Get the companies for the package.
     *
     * Syntax: return $this->hasMany(Company::class, 'package_id', 'id');
     *
     * Example: return $this->hasMany(Company::class, 'package_id', 'id');
     *
     */
    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
