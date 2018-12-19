<?php

namespace App\Http\Controllers;

use App\Booking;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller {
    public function store(Request $request) {
        //Log::info('test');
        $dates = $this->getDates($request->startAt, $request->duration);
        $conflict = $this->checkForDateConflict($request->room, $dates->formattedStart, $dates->formattedEnd);

        if ($conflict) {
            return response()->json([
                'message' => 'This room is already booked for this period of time.'
            ], 409);
        }

        $booking = Booking::create([
            'start_at' => $dates->formattedStart,
            'end_at' => $dates->formattedEnd,
            'user_id' => Auth::id(),
            'room_id' => $request->room,
        ]);

        $data = [
            'data' => $booking,
            'status' => (bool) $booking,
            'message' => $booking ? 'Room booked' : 'Error booking the room',
        ];

        return response()->json($data);
    }

    public function show(Booking $booking) {
        return response()->json($booking);
    }

    public function update(Request $request, Booking $booking) {
        $dates = $this->getDates($request->startAt, $request->duration);
        $conflict = $this->checkForDateConflict($request->room, $dates->formattedStart, $dates->formattedEnd);

        if ($conflict) {
            return response()->json([
                'message' => 'Error booking the room',
                'error' => [
                    'dateConflict' => $conflict,
                ],
            ], 409);
        }

        $status = $booking->update([
            'start_at' => $dates->formattedStart,
            'end_at' => $dates->formattedEnd,
            'room_id' => $request->room,
        ]);

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Booking Updated!' : 'Error Updating Booking'
        ]);
    }

    public function destroy(Booking $booking) {
        $status = $booking->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Booking Deleted!' : 'Error Deleting Booking',
        ]);
    }

    public function getBookingsOfDay($date) {
        $bookings = DB::table('bookings')
                      ->join('meeting_rooms', 'bookings.room_id', '=', 'meeting_rooms.id')
                      ->join('users', 'bookings.user_id', '=', 'users.id')
                      ->select('bookings.*', 'meeting_rooms.name as group_name', 'users.name as user_name', 'users.email')
                      ->where('start_at', '>=', $date . ' 00:00:00')
                      ->where('start_at', '<=', $date . ' 23:59:59')
                      ->orderBy('start_at')
                      ->get();
        return $bookings->groupBy('group_name');
    }

    private function getDates($startAt, $duration) {
        $start = new DateTime($startAt);
        $end = clone $start;
        $end->add(new DateInterval('PT' . $duration . 'M'));

        return (object) [
            'start' => $start,
            'formattedStart' => $start->format('Y-m-d H:i:s'),
            'end' => $end,
            'formattedEnd' => $end->format('Y-m-d H:i:s'),
        ];
    }

    private function checkForDateConflict($room, $start, $end) {
        return DB::table('bookings')
                 ->where('room_id', '=', $room)
                 ->where(function($query) use ($start, $end) {
                     $query
                         ->where(function($queryBetween) use ($start, $end) {
                             $queryBetween
                                 ->whereBetween('start_at', [$start, $end])
                                 ->orWhereBetween('end_at', [$start, $end]);
                         })
                         ->orWhere([
                             ['start_at', '<', $start],
                             ['end_at', '>', $start],
                         ])
                         ->orWhere([
                             ['start_at', '<', $end],
                             ['end_at', '>', $end],
                         ]);
                 })
                 ->exists();
    }
}
