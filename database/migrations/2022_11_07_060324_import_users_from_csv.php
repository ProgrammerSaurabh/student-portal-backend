<?php

use App\Models\Assignment;
use App\Models\Role;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        $filename = database_path('dataset' . DIRECTORY_SEPARATOR . 'assignments.csv');

        if (!file_exists($filename)) {
            throw new Exception('File doesn\'t exists');
        }

        $file = fopen($filename, "r");

        if (!$file) {
            throw new Exception('Cannot open file for reading');
        }

        $users = [];
        $marks = [];
        $assignents = [];
        $users = [];

        $password = bcrypt('password');

        $row = 0;
        while (($data = fgetcsv($file, 100, ",")) !== FALSE) {
            if ($row === 0) {
                $row++;

                $this->createSubject(data: [
                    $data[4],
                    $data[5],
                    $data[6],
                    $data[7],
                    $data[8],
                    $data[9],
                    $data[10],
                ]);
                continue;
            }

            $users[] = [
                'role_id' => Role::USER,
                'role_no' => $data[0],
                'first_name' => $data[1],
                'last_name' => $data[2],
                'location' => $data[3],
                'email' => strtolower($data[1]) . '_' . strtolower($data[2]) . '@gmail.com',
                'password' => $password,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $marks[$data[0]] = [
                $data[4],
                $data[5],
                $data[6],
                $data[7],
                $data[8],
                $data[9],
                $data[10],
            ];
        }

        fclose($file);

        User::insert($users);

        $users = User::query()
            ->pluck('users.id', 'users.role_no')
            ->toArray();

        $subjects = Subject::query()
            ->pluck('subjects.id')
            ->toArray();

        foreach ($users as $role => $user) {
            $assignents = array_merge(
                $assignents,
                collect($marks[$role])
                    ->map(fn ($assignent, $key) => [
                        'subject_id' => $subjects[$key],
                        'user_id' => $user,
                        'score' => $assignent,
                        'created_at' => now(),
                        'updated_at' => now()
                    ])
                    ->toArray()
            );
        }

        Assignment::insert($assignents);
    }

    private function createSubject(array $data): void
    {
        Subject::insert(
            collect($data)
                ->map(fn ($subject) => ['name' => $subject, 'created_at' => now(), 'updated_at' => now()])
                ->toArray()
        );
    }
};
