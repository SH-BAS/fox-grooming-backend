<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\WeeklySchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WeeklyScheduleController extends Controller
{
    protected array $days = [
        'mon' => 'Monday',
        'tue' => 'Tuesday',
        'wed' => 'Wednesday',
        'thu' => 'Thursday',
        'fri' => 'Friday',
        'sat' => 'Saturday',
        'sun' => 'Sunday',
    ];

    public function index()
    {
        $dayOrder = array_flip(array_keys($this->days));

        $schedulesByFranchise = WeeklySchedule::with('franchise')
            ->get()
            ->groupBy('franchise_id')
            ->map(function ($days) use ($dayOrder) {
                return $days->sortBy(fn ($s) => $dayOrder[$s->day_of_week] ?? 99)->values();
            });

        return view('admin.weekly-schedules.index', [
            'schedulesByFranchise' => $schedulesByFranchise,
            'days' => $this->days,
        ]);
    }

    public function create()
    {
        $franchises = Franchise::orderBy('name')->get();
        $scheduledFranchiseIds = WeeklySchedule::query()->distinct()->pluck('franchise_id');

        return view('admin.weekly-schedules.create', [
            'days' => $this->days,
            'franchises' => $franchises,
            'scheduledFranchiseIds' => $scheduledFranchiseIds,
            'existingSchedules' => collect(),
            'selectedFranchiseId' => null,
            'isEditWeek' => false,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'franchise_id' => 'required|exists:franchises,id',
            'weekly_schedules' => 'required|array',
            'weekly_schedules.*.is_open' => 'nullable',
            'weekly_schedules.*.opening_time' => 'required_if:weekly_schedules.*.is_open,1',
            'weekly_schedules.*.closing_time' => 'required_if:weekly_schedules.*.is_open,1',
        ]);

        foreach ($request->weekly_schedules as $day => $schedule) {
            $data = [
                'is_open' => isset($schedule['is_open']),
                'opening_time' => null,
                'closing_time' => null,
                'break_start' => null,
                'break_end' => null,
            ];

            $tz = session('timezone', 'UTC');

            if (!empty($schedule['opening_time'])) {
                $data['opening_time'] = Carbon::parse($schedule['opening_time'], $tz)->setTimezone('UTC')->format('H:i:s');
            }
            if (!empty($schedule['closing_time'])) {
                $data['closing_time'] = Carbon::parse($schedule['closing_time'], $tz)->setTimezone('UTC')->format('H:i:s');
            }

            WeeklySchedule::updateOrCreate(
                [
                    'franchise_id' => $request->franchise_id,
                    'day_of_week' => $day,
                ],
                $data
            );
        }

        return redirect()->route('admin.weekly-schedules.index')
            ->with('success', 'Weekly schedule saved successfully.');
    }

    public function editWeek(Franchise $franchise)
    {
        $existing = WeeklySchedule::where('franchise_id', $franchise->id)
            ->get()
            ->keyBy('day_of_week');

        $franchises = Franchise::orderBy('name')->get();

        return view('admin.weekly-schedules.create', [
            'days' => $this->days,
            'franchises' => $franchises,
            'scheduledFranchiseIds' => collect(),
            'existingSchedules' => $existing,
            'selectedFranchiseId' => $franchise->id,
            'isEditWeek' => true,
        ]);
    }

    public function destroyWeek(Franchise $franchise)
    {
        WeeklySchedule::where('franchise_id', $franchise->id)->delete();

        return redirect()->route('admin.weekly-schedules.index')
            ->with('success', 'Franchise weekly schedule deleted successfully.');
    }

    public function edit(WeeklySchedule $weeklySchedule)
    {
        // Redirect single-day edit to full-week editor
        return redirect()->route('admin.weekly-schedules.editWeek', $weeklySchedule->franchise_id);
    }

    public function update(Request $request, WeeklySchedule $weeklySchedule)
    {
        return $this->store($request);
    }

    public function destroy(WeeklySchedule $weeklySchedule)
    {
        $weeklySchedule->delete();

        return redirect()->route('admin.weekly-schedules.index')
            ->with('success', 'Schedule deleted successfully.');
    }
}
