<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ArchiveMessage
 * 
 * @property int $id
 * @property int $instance_id
 * @property int $active_message_id
 * @property string $to
 * @property string $body
 * @property int $priority
 * @property int $referance_id
 * @property Carbon|null $send_at
 * @property string $status
 * @property string $ack_status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class ArchiveMessage extends Model
{
	protected $table = 'archive_messages';

	protected $casts = [
		'instance_id' => 'int',
		'active_message_id' => 'int',
		'priority' => 'int',
		'referance_id' => 'int'
	];

	protected $dates = [
		'send_at'
	];

	protected $fillable = [
		'instance_id',
		'active_message_id',
		'to',
		'body',
		'priority',
		'referance_id',
		'send_at',
		'status',
		'ack_status'
	];
}
