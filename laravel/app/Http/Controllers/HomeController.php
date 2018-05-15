<?php
/**
 * Controller for home site
 *
 * @autor Jakub Handzus
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Sensor;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $sensors = Sensor::where('user_id', Auth::user()->id)->get();

        $info = array('active' => 0, 'inactive' => 0, 'not_confirmed' => 0, 'exceeded' => 0);
        foreach ($sensors as $sensor) {
            if ($sensor->last_temperature != null && $sensor->temperature_time >= Carbon::now()->subMinutes(10)) {
                $info['active']++;
            }
            elseif ($sensor->confirmed == 1 || $sensor->last_temperature != null && $sensor->temperature_time < Carbon::now()->subMinutes(10)) {
                $info['inactive']++;
            }
            elseif ($sensor->confirmed == 0) {
                $info['not_confirmed']++;
            }
            if (($sensor->max && $sensor->last_temperature >= $sensor->max) || ($sensor->min && $sensor->last_temperature <= $sensor->min)) {
                $info['exceeded']++;
            }
        }

        return view('home', compact('sensors', 'info'));
    }

}
