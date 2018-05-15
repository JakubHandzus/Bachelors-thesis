<?php
/**
 * Aggregate sensor's temperatures
 *
 * @author Jakub Handzus
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Temperature;
use App\Sensor;
use Carbon\Carbon;


class CleanupController extends Controller
{

    /**
     * Algorithm for aggregation all sensor's temperature
     */
	public function cleanup() {

	    // For each sensor in DB
		foreach (Sensor::get() as $sensor) {

			$last_cleanup;
			// first sensor's clean up
			if ($sensor->cleanup_time == null) {
				$first_temp = $sensor->temperatures()->orderBy('time', 'asc')->first();
				// if there was some temperature - get oldest
				if ($first_temp) {
					$last_cleanup = new Carbon($first_temp->time);
					$last_cleanup = $last_cleanup->minute(0)->second(0);
				}
				// no values
				else {
					$last_cleanup = Carbon::now()->minute(0)->second(0);
					$sensor->cleanup_time = Carbon::now()->minute(0)->second(0);
					$sensor->save();
				}
			}
			// it was cleaned up
			else {
				$last_cleanup = new Carbon($sensor->cleanup_time);
			}
			
			// sensor can be clean up if last cleaning was before 24 hours
			if ($last_cleanup <= Carbon::now()->minute(0)->second(0)->subDay()) {
				// set time of clean up
				$sensor->cleanup_time = Carbon::now()->minute(0)->second(0);
				$sensor->save();

				$timestamp = (Carbon::now()->minute(0)->second(0)->subDay())->timestamp;

				// day before last cleanup
				$last_cleanup = $last_cleanup->subDay();

				// for every hour from now-24h to last sensor clean up
				for ($timestamp; $timestamp >= $last_cleanup->timestamp; $timestamp-= 3600) {

					// get average temperature for concrete hour
					$avg = $sensor->temperatures()
						->where('time', '>=', Carbon::createFromTimestamp($timestamp-3600)->toDateTimeString())
						->where('time', '<', Carbon::createFromTimestamp($timestamp)->toDateTimeString())
						->avg('temperature');
					// if there was any value
					if ($avg != null) {
						$avg = round($avg, 2);

						// delete old values
						$sensor->temperatures()
							->where('time', '>=', Carbon::createFromTimestamp($timestamp-3600)->toDateTimeString())
							->where('time', '<', Carbon::createFromTimestamp($timestamp)->toDateTimeString())
							->delete();

						// create new average temperature
						$temp = new Temperature;
						$temp->sensor_id = $sensor->id;
						// half hour
						$temp->time = Carbon::createFromTimestamp($timestamp-1800)->toDateTimeString();
						$temp->temperature = $avg;
						$temp->save();
					}
					// no values - go to another hour
					else {
						continue;
					}
				}
			}
		}

		return;
	}

}
