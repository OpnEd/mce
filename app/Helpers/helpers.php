<?php

use App\Models\Setting;

if(!function_exists('getSettings'))
{
    function getSettings(array $columns, $tenantId)
    {
        $finalResult = [];
        $settings = Setting::whereIn('key', $columns)
            ->with(['settings' => function($query) use ($tenantId) {
                $query->where('team_id', $tenantId);
            }])
            ->get();
        foreach ($columns as $column) {
            $setting = $settings->firstWhere('key', $column);

            if ($setting) {
                $tenantSetting = $setting->settings->first();
                if($setting->type == "file")
                {
                    $finalResult[$column] = $tenantSetting ? asset($tenantSetting->value) : asset($setting->value);
                }
                else
                {
                    $finalResult[$column] = $tenantSetting ? $tenantSetting->value : $setting->value;
                }
            } else {
                $finalResult[$column] = '';
            }
        }
        return $finalResult;
    }
}
