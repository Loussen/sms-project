<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
