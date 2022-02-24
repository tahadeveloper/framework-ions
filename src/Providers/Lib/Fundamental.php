<?php

namespace App\Providers\Lib;

use Carbon\Carbon;
use Exception;
use Fcm\Exception\FcmClientException;
use Fcm\FcmClient;
use Fcm\Push\Notification;
use Ions\Bundles\IonUpload;
use Ions\Bundles\Path;
use Ions\Foundation\Kernel;
use Ions\Foundation\ProviderController;
use Ions\Support\DB;
use function env;
use function validate;

class Fundamental extends ProviderController
{
    public static function uploadFiles($param): static
    {
        $valid = validate($param, ['file' => 'required|file']);
        if (!empty($valid)) {
            self::badRequest($valid);
            return new self();
        }

        $upload_files = Kernel::request()->files; //alternative default =  $_FILES
        $data = [];
        foreach ($upload_files as $key => $temp_file) {
            $file_output = IonUpload::store($temp_file, Path::files('dump'))->response();
            if ((int)$file_output['error'] === 0) {
                $file_output['path_url'] = Path::files('dump/' . $file_output['store_name'], true);
            }
            $data[] = $file_output;
            unset($param->$key);
        }

        self::successResponse(['files' => $data] + (array)$param);


        return new self();
    }

    public static function notificationFcm($param): static
    {
        try {
            self::badRequest(['error' => 'No notification connected here']);
            if (env('FCM_API_TOKEN') && env('FCM_SENDER_ID')) {
                $options = ["http_errors" => env('APP_DEBUG', false)];
                $fcm_client = new FcmClient(env('FCM_API_TOKEN'), env('FCM_SENDER_ID'), $options);

                self::badRequest(['error' => 'No device id send']);

                if (isset($param->device_id)) {
                    $notification = new Notification();
                    $notification
                        ->addRecipient($param->device_id)
                        ->setClickAction("FCM_PLUGIN_ACTIVITY")
                        ->setTitle($param->title)
                        ->setBody($param->body)
                        ->addData('item_type', $param->item_type)
                        ->addData('item_id', $param->item_id)
                        ->addData('item_name', $param->item_name);
                    $send_response = $fcm_client->send($notification);

                    if ($send_response['success'] === 1) {
                        DB::table('notification_fcm')->insert([
                            'title' => $param->title,
                            'item_id' => $param->item_id,
                            'item_type' => $param->item_type,
                            'item_name' => $param->item_name,
                            'body' => $param->body,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                    }

                    self::successResponse($send_response);
                }
            }
        } catch (FcmClientException|Exception $exception) {
            self::serverError(['error' => $exception->getMessage()]);
        }
        return new self();
    }

}
