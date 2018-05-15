<?php
/**
 * Controller for all sensor operations
 *
 * @author Jakub Handzus
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Sensor;
use App\Temperature;
use Auth;
use App;
use Carbon\Carbon;


class SensorsController extends Controller
{
    /**
     * List all user's sensors
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function index() {

		$sensors = Sensor::where('user_id', Auth::user()->id)->get();
		return view('sensors.index', compact('sensors')); 
	}

    /**
     * List all user's active sensors
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function indexActive() {
		$sensors = Sensor::where([
			['user_id', '=', Auth::user()->id],
			['confirmed', '=', '1'],
			['temperature_time', '>=', Carbon::now()->subMinutes(10)]
		])->get();
		return view('sensors.index', compact('sensors')); 
	}

    /**
     * List all user's inactive sensors
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function indexInactive() {
		$sensors = Sensor::where([
			['user_id', '=', Auth::user()->id],
			['confirmed', '=', '1'],
		])->where(function($q){
			$q->WhereNull('last_temperature')
			->orWhere('temperature_time', '<', Carbon::now()->subMinutes(10));
		})->get();

		return view('sensors.index', compact('sensors')); 
	}

    /**
     * List all user's not confirmed sensors
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function indexNotConfirmed() {
		$sensors = Sensor::where([
			['user_id', '=', Auth::user()->id],
			['confirmed', '=', '0'],
		])->get();

		return view('sensors.index', compact('sensors')); 
	}

    /**
     * List all user's sensors, which has exceeded value
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function indexExceeded() {
		$sensors = Sensor::where('user_id', '=', Auth::user()->id)
		->whereNotNull('last_temperature')
		->where(function($q){
			$q->whereNotNull('last_temperature')
			->whereRaw('last_temperature <= min');
		})->orWhere(function($q) {
			$q->whereNotNull('last_temperature')
			->whereRaw('last_temperature >= max');
		})->get();

		return view('sensors.index', compact('sensors')); 
	}

    /**
     * Show create form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function create() {

		return view('sensors.create');
	}

    /**
     * Store new sensor
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function store() {

		// form data validation 
		$this->validate(request(), [
			'name' => 'required|string|max:20',
			'min'  => 'nullable|numeric|min:-99.9|max:99.9',
			'max'  => 'nullable|numeric|min:-99.9|max:99.9'
		]);
		if (request('min') != null && request('max') != null && request('min') >= request('max')) {
			return redirect()->back()->withErrors(['min' => 'Must be lesser then max', 'max' => 'Must be greater then min'])->withInput(Input::all());
		}
		
		// create new sensor 
		$sensor = new Sensor;
		$sensor->name = request('name');
		// generate Api-key
		$sensor->api_key = implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));
		$sensor->user_id = Auth::user()->id;
		$sensor->temperature_time = null;
		// optional
		$sensor->max = request('max');
		$sensor->min = request('min');

		$sensor->save();

		// redirect to scan QR code
		return redirect('sensors/'. $sensor->id .'/qrcode');
	}

    /**
     * View edit sensor form
     *
     * @param Sensor $sensor
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function edit(Sensor $sensor) {
		if ($sensor->user_id == Auth::user()->id) {
			return view('sensors.edit', compact('sensor'));
		}
		else {
			App::abort(404);
		} 

	}

    /**
     * Update sensor
     *
     * @param Sensor $sensor
     * @return \Illuminate\Http\RedirectResponse
     */
	public function update(Sensor $sensor) {
		if ($sensor->user_id == Auth::user()->id) {
			// form data validation 
			$this->validate(request(), [
				'name' => 'required|string|max:20',
				'min'  => 'nullable|numeric|min:-99.9|max:99.9',
				'max'  => 'nullable|numeric|min:-99.9|max:99.9'
			]);
			if (request('min') != null && request('max') != null && request('min') >= request('max')) {
			return redirect()->back()->withErrors(['min' => 'Must be lesser then max', 'max' => 'Must be greater then min'])->withInput(Input::all());
			}
			$sensor->name = request('name');
			// optional
			$sensor->max = request('max');
			$sensor->min = request('min');

			$sensor->save();

			return redirect()->route('sensors');
		}
		else {
			App::abort(404);
		} 

	}

    /**
     * Delete sensor
     *
     * @param Sensor $sensor
     * @return \Illuminate\Http\RedirectResponse
     */
	public function delete(Sensor $sensor) {
		if ($sensor->user_id == Auth::user()->id) {
			$sensor->temperatures()->delete();
            try {
                $sensor->delete();
            } catch (\Exception $e) {
                return redirect()->route('sensors');
            }

            return redirect()->route('sensors');
		}
		else {
			App::abort(404);
		} 
	}

    /**
     * Show sensor site
     *
     * @param Sensor $sensor
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function show(Sensor $sensor) {
		if ($sensor->user_id == Auth::user()->id) {
		    // Not confirmed sensors haven't any values - redirect to QR code site
			if ($sensor->confirmed == '0') {
				return view('sensors.qrcode', compact('sensor'));
			}
			else {
				$user = Auth::user();
				return view('sensors.show', compact('user', 'sensor'));
			}
		}
		else {
			App::abort(404);
		} 
	}

    /**
     * Show site with instruction and QR code
     *
     * @param Sensor $sensor
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function qrcode(Sensor $sensor) {
		if ($sensor->user_id == Auth::user()->id) {
			return view('sensors.qrcode', compact('sensor'));
		}
		else {
			App::abort(404);
		} 
	}

    /**
     * Identifying sensor's first message
     *
     * @param Request $request
     * @return string ('OK' or 'ERROR!')
     */
	public function identificate(Request $request) {
		if (isset($request['id']) && isset($request['api_key'])) {
			$sensor = Sensor::where('api_key', $request['api_key'])->first();

			if (!$sensor->confirmed && $sensor->device_id == null) {
				$sensor->update([$sensor->device_id = $request['id']]);
			}

			return "OK";
		}
		else {
			return "ERROR!";
		}
	}

    /**
     * Confirm sensor registration
     *
     * @param Sensor $sensor
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function confirm(Sensor $sensor) {
		if ($sensor->user_id == Auth::user()->id) {
			if ($sensor->device_id != null && $sensor->confirmed == 0) {
				$sensor->update([$sensor->confirmed = 1]);
			}
			return redirect('sensors');
		}
		else {
			App::abort(404);
		}
	}

    /**
     * Return sensor's temperature data in JSON from specific time
     *
     * @param Sensor $sensor
     * @return JSON message
     */
	public function json(Sensor $sensor) {
		if ($sensor->user_id == Auth::user()->id) {
		    // validate time
			$this->validate(request(), [
				'time' => 'required|date'
			]);

			$time = new Carbon(request('time'));
			$data = $sensor->temperaturesFrom($time)->get();

			if ($data->count() != 0) {

			    // details
				$details['last']= $sensor->temperaturesFrom($time)->orderBy('time', 'desc')->first();
				$details['min'] = $sensor->temperaturesFrom($time)->where('temperature', $sensor->temperaturesFrom($time)->min('temperature'))->orderBy('time', 'desc')->first();
				$details['max'] = $sensor->temperaturesFrom($time)->where('temperature', $sensor->temperaturesFrom($time)->max('temperature'))->orderBy('time', 'desc')->first();
				$details['avg'] = $sensor->temperaturesFrom($time)->avg('temperature');

				// last value is in interval => add to JSON
				if ($sensor->temperature_time > $time) {
					$temp = new Temperature;
					$temp->time = $sensor->temperature_time;
					$temp->temperature = $sensor->last_temperature;
					$data->push($temp);
					$details['last']= $temp;
					if ($temp->temperature > $details['max']->temperature) {
						$details['max'] = $temp;
					}
					if ($temp->temperature < $details['min']->temperature) {
						$details['min'] = $temp;
					}
				}
			}

			return compact('data', 'details');
		}
		else {
			App::abort(404);
		}
	}

}
