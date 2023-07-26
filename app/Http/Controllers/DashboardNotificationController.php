<?php

namespace App\Http\Controllers;

use App\Models\DashboardNotification;


class DashboardNotificationController extends Controller
{
    public function update()
    {
        $dashboard_notification = DashboardNotification::findOrFail(request('id'))->update([
            'status' => 1
        ]);

        return $dashboard_notification;
    }
}
