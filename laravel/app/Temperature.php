<?php
/**
 * Temperature model
 *
 * @author Jakub Handzus
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Temperature extends Model
{
	public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sensor() {
    	return $this->belongsTo(Sensor::class);
    }
}
