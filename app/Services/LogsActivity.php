<?php

namespace App\Services;

use App\Http\Api\UtilitiesFunctions;
use App\Http\Controllers\Helpers\AdminAuth;
use App\Models\ActivityLog;
use App\Models\Admin\User;
use App\Models\Client;
use App\Models\Inspector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Ions\Foundation\Kernel;
use Ions\Support\Str;

trait LogsActivity
{
    protected bool $loggingDisabled = false;

    public static function bootLogsActivity(): void
    {
        static::eventsToBeRecorded()->each(function ($eventName) {
            static::$eventName(static function (Model $model) use ($eventName) {
                $model->logActivity($eventName);
            });
        });
    }

    protected static function eventsToBeRecorded(): Collection
    {
        $events = collect([
            'created',
            'updated',
        ]);

        if (collect(class_uses_recursive(static::class))->contains(SoftDeletes::class)) {
            $events->push('softDeleted');
            $events->push('restored');
            $events->push('forceDeleted');
        } else {
            $events->push('deleted');
        }

        return $events;
    }

    public function disableLogging(): void
    {
        $this->loggingDisabled = true;
    }

    public function enableLogging(): void
    {
        $this->loggingDisabled = false;
    }

    protected function logActivity(string $event): void
    {
        if ($this->loggingDisabled) {
            return;
        }

        $descriptions = [
            'created' => 'A new record has been created.',
            'updated' => 'A record has been updated.',
            'deleted' => 'A record has been deleted.',
            'softDeleted' => 'A record has been soft deleted.',
            'restored' => 'A record has been restored.',
            'forceDeleted' => 'A record has been permanently deleted.',
        ];

        $description = $descriptions[$event] ?? 'An event has occurred.';

        $causerInfo = $this->getCauserInfo();

        ActivityLog::create(
            [
                'log_name' => 'default',
                'description' => $description,
                'subject_type' => get_class($this),
                'subject_id' => $this->id,
                'properties' => $this->getChanges(),
                'batch_uuid' => Str::uuid(),
                'event' => $event,
            ] + $causerInfo,
        );
    }

    public function getChanges(): array
    {
        return [
            'attributes' => $this->getAttributes(),
            'old' => $this->getOriginal(),
        ];
    }

    /**
     * @return array
     */
    protected function getCauserInfo(): array
    {
        $request = Kernel::request();
        $causerInfo = [];
        $token = $request->header('token');
        if ($token) {
            $authTokenData = TokenService::verifyAccessToken($token);
            if ($authTokenData && isset($authTokenData['type']) && $authTokenData['type'] === 'inspector') {
                $causerInfo = [
                    'causer_type' => Inspector::class,
                    'causer_id' => $authTokenData['id'],
                ];
            } else {
                $causerInfo = [
                    'causer_type' => Client::class,
                    'causer_id' => $authTokenData['id'],
                ];
            }
        } elseif (AdminAuth::userData()) {
            $causerInfo = [
                'causer_type' => User::class,
                'causer_id' => AdminAuth::userData()->id,
            ];
        }
        return $causerInfo;
    }

}