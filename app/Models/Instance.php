<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Instance
 *
 * @property int $id
 * @property int $company_id
 * @property string $instance_id
 * @property string $token
 * @property string $status
 * @property Carbon|null $last_login
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Instance extends Model
{
    use HasFactory;

	protected $table = 'instances';

	protected $casts = [
		'company_id' => 'int'
	];

	protected $dates = [
		'last_login'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'company_id',
		'instance_id',
		'token',
		'status',
		'last_login'
	];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
