<?php
/**
 * Sensor model
 *
 * @author Jakub Handzus
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sensor extends Model
{
	protected $fillable = ['name', 'max', 'min'];

	public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	public function temperatures() {
		return $this->hasMany(Temperature::class)->select(['time', 'temperature']);
	}

    /**
     * @return int - count of sensor temperatures
     */
	public function temperaturesCount() {
		return $this->temperatures()->count();
	}

    /**
     * @param $date
     * @return sensor temperatures from specific date
     */
	public function temperaturesFrom($date) {
		return $this->temperatures()->where('time', '>=', $date)->orderBy('time', 'asc');
	}

    /**
     * @return last sensor temperature
     */
	public function lastTemp() {
		return $this->hasMany(Temperature::class)->orderBy('time', 'desc')->first();
	}

    /**
     * @return last sensor temperature in printable format
     */
	public function lastTempPrint() {
		if ($this->last_temperature != null) {
			return $this->last_temperature . '°C';
		}
		else {
			return 'N/A';
		}
	}

    /**
     * @param $format ('time' or 'diff')
     * @return last active time
     */
	public function activeTime($format) {

		if ($this->temperature_time != null) {
			if ($format == 'time') {
				return Carbon::parse($this->temperature_time)->format('H:i:s, d.m.Y');
			}
			elseif ($format == 'diff') {
				return 'Updated '. Carbon::parse($this->temperature_time)->diffForHumans();
			}
		}
		else {
			return 'Inactive';
		}

	}

}
