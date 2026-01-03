<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $today  = Carbon::today()->format('Y-m-d');
        $future = Carbon::today()->addDays(5)->format('Y-m-d');
        $past   = Carbon::today()->subDays(5)->format('Y-m-d');
        $now    = Carbon::now();

        $events = [
            [
                'title' => 'Morning Standup',
                'description' => 'Daily team synchronization.',
                'date' => $today,
                'time' => '10:00:00',
                'location' => 'Zoom',
            ],
            [
                'title' => 'Client Lunch',
                'description' => 'Lunch meeting with new client.',
                'date' => $today,
                'time' => '13:00:00',
                'location' => 'City Cafe',
            ],
            [
                'title' => 'Code Review',
                'description' => 'Reviewing the pull requests.',
                'date' => $today,
                'time' => '16:00:00',
                'location' => 'Office A',
            ],

            [
                'title' => 'Project Deadline',
                'description' => 'Final submission day.',
                'date' => $future,
                'time' => '17:00:00',
                'location' => 'Online Portal',
            ],
            [
                'title' => 'Team Outing',
                'description' => 'Annual team trip.',
                'date' => Carbon::today()->addDays(10)->format('Y-m-d'),
                'time' => '08:00:00',
                'location' => 'Beach Resort',
            ],
            [
                'title' => 'New Year Plan',
                'description' => 'Strategy meeting for next year.',
                'date' => Carbon::today()->addMonth()->format('Y-m-d'),
                'time' => '10:00:00',
                'location' => 'Board Room',
            ],

            [
                'title' => 'Project Kickoff',
                'description' => 'Initial meeting for the project.',
                'date' => $past,
                'time' => '10:00:00',
                'location' => 'Hall B',
            ],
            [
                'title' => 'Old Workshop',
                'description' => 'Laravel basics workshop.',
                'date' => Carbon::today()->subDays(15)->format('Y-m-d'),
                'time' => '11:00:00',
                'location' => 'Auditorium',
            ],
            [
                'title' => 'Yesterday Debugging',
                'description' => 'Fixed critical bugs.',
                'date' => Carbon::yesterday()->format('Y-m-d'),
                'time' => '15:00:00',
                'location' => 'Lab 1',
            ],
        ];

        foreach ($events as $event) {
            DB::table('events')->updateOrInsert(
                [
                    'title' => $event['title'],
                    'date'  => $event['date'],
                    'time'  => $event['time'],
                ],
                [
                    'description' => $event['description'],
                    'location'    => $event['location'],
                    'updated_at'  => $now,
                    'created_at'  => $now,
                ]
            );
        }
    }
}
