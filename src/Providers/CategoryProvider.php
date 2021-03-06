<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Ions\Builders\QueryBuilder;
use Ions\Foundation\ProviderController;
use Ions\Support\Arr;
use Ions\Support\DB;
use JetBrains\PhpStorm\Pure;
use stdClass;
use Throwable;

class CategoryProvider extends ProviderController
{
    private static string $tbl = 'category';
    private static array $columns = ['id', 'name', 'email', 'created_at', 'updated_at'];

    /**
     * validation rules that apply to request
     *
     * @return string[]
     */
    #[Pure]
    private static function rules($items = []): array
    {
        $rules = [
            'name' => 'required',
            'id' => 'required|numeric|not_in:0|exists:' . static::$tbl . ',id',
            'ids' => 'required|array'
        ];

        return $items ? Arr::only($rules, $items) : $rules;
    }

    public static function store(stdClass $param): static
    {
        if ($validated = validate($param, static::rules(['name']))) {
            self::badRequest($validated);
            return new self();
        }

        try {
            DB::table('roles')->insert([
                'title' => $param->title,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            self::createdResponse(['response' => trans('create_success', [], 'provider')]);
        } catch (Throwable $exception) {
            self::serverError($exception->getMessage());
        }

        return new self();
    }

    public static function single($id): static
    {
        if ($validated = validate(['id' => $id], self::rules(['id']))) {
            self::badRequest($validated);
            return new self();
        }

        $item = QueryBuilder::for(static::$tbl)
            ->allowedFields(self::$columns)
            ->allowFilters()
            ->sole($id);
        self::successResponse($item);

        return new self();
    }

    public static function show(): static
    {
        $data = QueryBuilder::for(static::$tbl)
            ->allowedFields(self::$columns)
            ->allowFilters()
            ->allowedSorts(self::$columns)
            ->get();
        $paging = ['total' => $data['total'], 'limit' => $data['limit'], 'offset' => $data['offset']];

        self::successResponse(array_merge($paging, ['items' => $data['items']]));
        return new self();
    }

    public static function update(stdClass $param): static
    {
        if ($validated = validate($param, static::rules(['id', 'name']))) {
            self::badRequest($validated);
            return new self();
        }

        try {
            DB::table(static::$tbl)
                ->where('id', $param->id)->update([
                    'title' => $param->title,
                    'updated_at' => Carbon::now()
                ]);
            self::updatedResponse(['response' => trans('update_success', [], 'provider')]);
        } catch (Throwable $exception) {
            self::serverError($exception->getMessage());
        }

        return new self();
    }

    public static function delete($id): static
    {
        if ($validation = validate(['id' => $id], self::rules(['id']))) {
            self::badRequest($validation);
            return new self();
        }

        try {
            DB::table(static::$tbl)->where('id', $id)->delete();
            self::deletedResponse(['response' => trans('delete_success', [], 'provider')]);
        } catch (Throwable $exception) {
            self::serverError($exception->getMessage());
        }
        return new self();
    }

    public static function deleteAll($ids): static
    {
        if ($validation = validate(['ids' => $ids], self::rules(['ids']))) {
            self::badRequest($validation);
            return new self();
        }

        try {
            DB::table(static::$tbl)->whereIn('id', $ids)->delete();
            self::deletedResponse(['response' => trans('delete_success', [], 'provider')]);
        } catch (Throwable $exception) {
            self::serverError($exception->getMessage());
        }
        return new self();
    }
}