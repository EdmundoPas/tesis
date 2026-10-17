<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function rooms(Request $request)
    {
        $query = ChatRoom::query()->with('messages.user')->latest();

        if ($request->user()->role !== 'admin') {
            $sections = $this->accessibleSections($request->user());
            $query->whereIn('grado_seccion', $sections);
        }

        return response()->json($query->get());
    }

    public function storeMessage(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'exists:chat_rooms,id'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $room = ChatRoom::findOrFail($validated['room_id']);
        abort_unless(
            $request->user()->role === 'admin'
                || in_array($room->grado_seccion, $this->accessibleSections($request->user()), true),
            403
        );

        $message = ChatMessage::create([
            'room_id' => $room->id,
            'user_id' => $request->user()->id,
            'message' => $validated['message'],
        ]);

        return response()->json($message->load('user'), 201);
    }

    private function accessibleSections(User $user): array
    {
        $sections = match ($user->role) {
            'parent' => $user->parentProfile?->students
                ->map(fn ($student) => [$student->grade.'.° '.$student->section, $student->grade.'° '.$student->section, $student->grade.' '.$student->section])
                ->flatten()
                ->all() ?? [],
            'teacher' => $user->teacher?->courses
                ->map(fn ($course) => [$course->grade.'.° '.$course->section, $course->grade.'° '.$course->section, $course->grade.' '.$course->section])
                ->flatten()
                ->all() ?? [],
            default => [],
        };

        return array_values(array_unique($sections));
    }
}
