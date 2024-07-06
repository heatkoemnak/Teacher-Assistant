<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\StudentModel;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    /**
     * Store attendance records for multiple students.
     *
     * @param  Request  $request
     * @param  ClassModel  $class
     * @param  StudentModel  $student
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, ClassModel $class, StudentModel $student)
    {
        $request->validate([
            'student_model_id' => 'required',
            'date' => 'required',
            'status' => 'required',
        ]);
        Attendance::create([
            'class_model_id' => $class->id,
            'student_model_id' => $student->id,
            'date' => $request->date,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Attendance recorded successfully'], 201);
    }

    /**
     * Update attendance record for a specific student in a class.
     *
     * @param  Request  $request
     * @param  ClassModel  $class
     * @param  StudentModel  $student
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, ClassModel $class, StudentModel $student)
    {
        $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:Present,Absent',
        ]);

        try {
            $attendance = Attendance::where('class_model_id', $class->id)
                ->where('student_model_id', $student->id)
                ->where('date', $request->date)
                ->first();

            if ($attendance) {
                $attendance->update(['status' => $request->status]);
            } else {
                Attendance::create([
                    'class_model_id' => $class->id,
                    'student_model_id' => $student->id,
                    'date' => $request->date,
                    'status' => $request->status,
                ]);
            }

            return response()->json(['message' => 'Attendance updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update attendance', 'message' => $e->getMessage()], 500);
        }
    }

    // Other methods for handling attendance records...

}
