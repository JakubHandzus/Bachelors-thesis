@component('mail::message')
# Warning

Hi {{$data['user_name']}} {{$data['user_surname']}}. Your temperature sensor {{$data['name']}} has reached temperature of {{$data['temperature']}} °C.
This mail was sent to you, because you set {{$data['min_max']}} value to {{$data['min_max_value']}}.

@component('mail::button', ['url' => $data['url']])
Check sensor
@endcomponent

Have a nice day.
@endcomponent
