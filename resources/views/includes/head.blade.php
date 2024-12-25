<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ get_setting('site_name', config('app.name')) }}</title>


<!-- https://laravel.com/docs/11.x/vite#loading-your-scripts-and-styles -->
@vite(['resources/css/app.css'])

@stack('scriptsHead')

@stack('stylesHead')



