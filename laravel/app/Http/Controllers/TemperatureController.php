<?php
/**
 * Receive sensor message
 *
 * @author Jakub Handzus
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sensor;
use App\Temperature;
use Carbon\Carbon;
use Mail;
use App\Mail\Notification;
use App\User;

class TemperatureController extends Controller
{
    /**
     * Create new temperature in database and create response message
     *
     * @param Request $request
     * @return string for response message
     */
    public function create(Request $request) {

        // request must include:
        if (isset($request['api_key']) && isset($request['temperature']) && isset($request['hash']) && is_numeric($request['temperature'])) {

            $sensor = Sensor::where('api_key', $request['api_key'])->first();

            // if sensor exists, was confirmed and hash is the same
            if ($sensor != null && $sensor['confirmed'] == 1 && $request['hash'] == strtoupper(hash("sha256", $request['temperature'] . $request['api_key'] . $sensor['device_id']))) {

                // check for notification
                // high
                if ($request['temperature'] >= $sensor->max && $sensor->last_temperature < $sensor->max) {
                    // send mail
                    $user = User::where('id', $sensor->user_id)->first();
                    $mail_data = array(
                        'name' => $sensor->name,
                        'temperature' => round($request['temperature'], 1),
                        'time' => Carbon::now()->toDateTimeString(),
                        'sensor_id' => $sensor->id,
                        'url' => config('app.url').':'.config('app.port').'/sensor/'.$sensor->id.'/view',
                        'min_max' => 'maximum',
                        'min_max_value' => $sensor->max,
                        'user_name' => $user->name,
                        'user_surname' => $user->surname
                    );
                    Mail::to($user->email)->queue(new Notification($mail_data));
                }
                // min
                elseif ($request['temperature'] <= $sensor->min && $sensor->last_temperature > $sensor->min) {
                    // send mail
                    $user = User::where('id', $sensor->user_id)->first();
                    $mail_data = array(
                        'name' => $sensor->name,
                        'temperature' => round($request['temperature'], 1),
                        'time' => Carbon::now()->toDateTimeString(),
                        'sensor_id' => $sensor->id,
                        'url' => config('app.url').':'.config('app.port').'/sensor/'.$sensor->id.'/view',
                        'min_max' => 'minimum',
                        'min_max_value' => $sensor->min,
                        'user_name' => $user->name,
                        'user_surname' => $user->surname
                    );
                    Mail::to($user->email)->queue(new Notification($mail_data));
                }

                // check if received temperature is store to DB
                if ($sensor->temperature_time == null || $sensor->lastTemp()->time < Carbon::now()->subMinutes(10)) {
                    $temp = new Temperature;
                    $temp->temperature = $request['temperature'];
                    $temp->sensor_id = $sensor['id'];
                    $temp->save();
                }

                // update last sensor value
                $sensor->temperature_time = Carbon::now()->toDateTimeString();
                $sensor->last_temperature = $request['temperature'];
                $sensor->save();

                return "OK";
            }
            else {
                // Prepared for future development: save ip address of potential attackers
                return "Invalid sensor";
            }

        }
        else {
            return "Invalid message";
        }

    }

}
