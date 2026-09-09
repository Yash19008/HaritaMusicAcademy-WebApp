<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReminderConfig;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReminderConfigController extends Controller
{
    public function index()
    {
        return response()->json(ReminderConfig::orderBy('minutes_before')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'minutes_before' => ['required', 'integer', 'min:1', Rule::unique('reminder_configs', 'minutes_before')],
            'notify_student' => ['boolean'],
            'notify_teacher' => ['boolean'],
            'enabled' => ['boolean'],
        ]);

        $data['notify_student'] = $request->boolean('notify_student', true);
        $data['notify_teacher'] = $request->boolean('notify_teacher', true);
        $data['enabled'] = $request->boolean('enabled', true);
        $this->ensureRecipientSelected($data['notify_student'], $data['notify_teacher']);

        $config = ReminderConfig::create($data);

        return response()->json(['success' => true, 'config' => $config]);
    }

    public function update(Request $request, ReminderConfig $reminderConfig)
    {
        $data = $request->validate([
            'label' => ['sometimes', 'required', 'string', 'max:255'],
            'minutes_before' => [
                'sometimes',
                'required',
                'integer',
                'min:1',
                Rule::unique('reminder_configs', 'minutes_before')->ignore($reminderConfig),
            ],
            'notify_student' => ['boolean'],
            'notify_teacher' => ['boolean'],
            'enabled' => ['boolean'],
        ]);

        if ($request->has('notify_student')) $data['notify_student'] = $request->boolean('notify_student');
        if ($request->has('notify_teacher')) $data['notify_teacher'] = $request->boolean('notify_teacher');
        if ($request->has('enabled')) $data['enabled'] = $request->boolean('enabled');
        $this->ensureRecipientSelected(
            $data['notify_student'] ?? $reminderConfig->notify_student,
            $data['notify_teacher'] ?? $reminderConfig->notify_teacher
        );

        $reminderConfig->update($data);

        return response()->json(['success' => true, 'config' => $reminderConfig]);
    }

    public function destroy(ReminderConfig $reminderConfig)
    {
        $reminderConfig->delete();
        return response()->json(['success' => true]);
    }

    private function ensureRecipientSelected(bool $notifyStudent, bool $notifyTeacher): void
    {
        if (!$notifyStudent && !$notifyTeacher) {
            throw ValidationException::withMessages([
                'notify_student' => 'Select at least one reminder recipient.',
            ]);
        }
    }
}
