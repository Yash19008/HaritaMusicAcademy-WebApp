<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReminderConfigController extends Controller
{
    public function index()
    {
        return response()->json(\App\Models\ReminderConfig::orderBy('minutes_before')->get());
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'minutes_before' => ['required', 'integer', 'min:1'],
            'notify_student' => ['boolean'],
            'notify_teacher' => ['boolean'],
            'enabled' => ['boolean'],
        ]);

        $data['notify_student'] = $request->boolean('notify_student', true);
        $data['notify_teacher'] = $request->boolean('notify_teacher', true);
        $data['enabled'] = $request->boolean('enabled', true);

        $config = \App\Models\ReminderConfig::create($data);

        return response()->json(['success' => true, 'config' => $config]);
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\ReminderConfig $reminderConfig)
    {
        $data = $request->validate([
            'label' => ['sometimes', 'required', 'string', 'max:255'],
            'minutes_before' => ['sometimes', 'required', 'integer', 'min:1'],
            'notify_student' => ['boolean'],
            'notify_teacher' => ['boolean'],
            'enabled' => ['boolean'],
        ]);

        if ($request->has('notify_student')) $data['notify_student'] = $request->boolean('notify_student');
        if ($request->has('notify_teacher')) $data['notify_teacher'] = $request->boolean('notify_teacher');
        if ($request->has('enabled')) $data['enabled'] = $request->boolean('enabled');

        $reminderConfig->update($data);

        return response()->json(['success' => true, 'config' => $reminderConfig]);
    }

    public function destroy(\App\Models\ReminderConfig $reminderConfig)
    {
        $reminderConfig->delete();
        return response()->json(['success' => true]);
    }
