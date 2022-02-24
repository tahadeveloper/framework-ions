<?php

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ions\Bundles\Path;

class SchemaCommand extends Command
{
    protected $signature = 'make:schema {name}';
    protected $description = 'Create schema for database table.';

    public function handle(): void
    {
        $name = $this->argument('name'); // ExampleTextSchema
        $name_cap = Str::remove('Schema', $name); // ExampleText
        $name_snake = Str::snake($name_cap); // Example_Text
        $name_lower = Str::lower($name_snake); // example_text

        if (!Storage::exists(Path::database('Schema'))) {
            Storage::makeDirectory(Path::database('Schema'));
        }

        $new_file = Path::database('Schema/'.$name.'.php');
        Storage::copy(Path::bin('commands/stubs/schema.stub'), $new_file);

        $replace = Str::replace(
            ['{{ namespace }}', '{{ class }}', '{{ table }}'],
            ['App\\Database\\Schema', $name, $name_lower],
            Storage::get($new_file));

        Storage::put($new_file, $replace);

        $this->info('Schema created successfully, happy to see you.');
    }
}
