<?php

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ions\Bundles\Path;

class ProviderCommand extends Command
{
    protected $signature = 'make:provider {name} {--api= : add provider to api and route api}';
    protected $description = 'Create provide for full control tbl and service for object.';

    public function handle(): void
    {
        $name = $this->argument('name'); // ExampleTextProvider
        $name_cap = Str::remove('Provider', $name); // ExampleText
        $name_snake = Str::snake($name_cap); // Example_Text
        $name_lower = Str::lower($name_snake); // example_text

        if (!Storage::exists(Path::src('Providers'))) {
            Storage::makeDirectory(Path::src('Providers'));
        }

        $new_file = Path::src('Providers/' . $name . '.php');
        Storage::copy(Path::bin('commands/stubs/provider.stub'), $new_file);

        try {
            $fields = Schema::connection('default')->getColumnListing($name_lower);
            collect($fields)->map(function ($item) {
                return '"' . $item . '"';
            });
            $fields = "'" . implode("','", $fields) . "'";
        } catch (Throwable $exception) {
            ray($exception->getMessage());
            $fields = '';
        }

        $replace = Str::replace(
            ['{{ namespace }}', '{{ class }}', '{{ table }}', '{{ columns }}'],
            ['App\\Providers', $name, $name_lower, $fields],
            Storage::get($new_file));

        Storage::put($new_file, $replace);

        $this->info('1. Provider created successfully, happy to see you.');
        $api = $this->input->getOption('api');
        if ($api) {
            $method_api_replace = Str::replace(
                ['{{ name }}', '{{ class }}'],
                [Str::camel($name_cap), $name],
                Storage::get(Path::bin('commands/stubs/api_method.stub')));
            Storage::put(Path::root('api/' . $api . '/Api.php'),
                Str::replaceLast('}', $method_api_replace, Storage::get(Path::root('api/' . $api . '/Api.php'))));

            $namespace_api_replace = Str::replace(
                ['{{ namespace }}'],
                ['App\\Providers\\' . $name],
                Storage::get(Path::bin('commands/stubs/api_namespace.stub')));
            Storage::put(
                Path::root('api/' . $api . '/Api.php'),
                Str::replace(
                    PHP_EOL.'class Api extends ApiController',
                    $namespace_api_replace,
                    Storage::get(Path::root('api/' . $api . '/Api.php'))
                ),null
            );

            $this->info('2. Added to api');
        }

    }
}
