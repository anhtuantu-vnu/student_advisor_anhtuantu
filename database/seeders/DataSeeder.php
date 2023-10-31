<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class DataSeeder extends Seeder
{
    function abbreviateString($input) {
        $words = explode(' ', $input);
        $abbreviations = array();
    
        foreach ($words as $word) {
            $abbreviations[] = substr($word, 0, 1);
        }
    
        return strtoupper(implode('', $abbreviations));
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            $departmentsToInsert = [
                ["name" => '{"vi": "Công Nghệ Thông Tin", "en": "Information Technology"}', 'uuid' => Str::uuid()->toString()],
                ["name" => '{"vi": "Trí tuệ nhân tạo", "en": "Artificial Intelligence"}', 'uuid' => Str::uuid()->toString()],
                ["name" => '{"vi": "Khoa học máy tính", "en": "Computer Science"}', 'uuid' => Str::uuid()->toString()],
                ["name" => '{"vi": "Hàng Không vũ trụ", "en": "Aerospace Engineering"}', 'uuid' => Str::uuid()->toString()],
                ["name" => '{"vi": "Kĩ thuật robot", "en": "Robot technology"}', 'uuid' => Str::uuid()->toString()],
            ];
    
            foreach($departmentsToInsert as $department) {
                DB::table('departments')->insert([
                    'name' => $department['name'],
                    'description' => json_decode($department['name'], true)["vi"],
                    'uuid' => $department['uuid'],
                    'created_at' => new \Datetime(),
                    'updated_at' => new \Datetime(),
                ]);
            }
    
            $classesToInsert = [
                ["name" => json_decode($departmentsToInsert[0]['name'], true)["vi"] . ' K55', 'uuid' => Str::uuid()->toString(), 'code' => $this->abbreviateString(json_decode($departmentsToInsert[0]['name'], true)["en"]) . ' - K55', 'department_id' => $departmentsToInsert[0]['uuid'], 'start_year' => '2019', 'end_year' => '2023'],
                ["name" => json_decode($departmentsToInsert[1]['name'], true)["vi"] . ' K55', 'uuid' => Str::uuid()->toString(), 'code' => $this->abbreviateString(json_decode($departmentsToInsert[1]['name'], true)["en"]) . ' - K55', 'department_id' => $departmentsToInsert[1]['uuid'], 'start_year' => '2019', 'end_year' => '2023'],
                ["name" => json_decode($departmentsToInsert[2]['name'], true)["vi"] . ' K55', 'uuid' => Str::uuid()->toString(), 'code' => $this->abbreviateString(json_decode($departmentsToInsert[2]['name'], true)["en"]) . ' - K55', 'department_id' => $departmentsToInsert[2]['uuid'], 'start_year' => '2019', 'end_year' => '2023'],
                ["name" => json_decode($departmentsToInsert[3]['name'], true)["vi"] . ' K55', 'uuid' => Str::uuid()->toString(), 'code' => $this->abbreviateString(json_decode($departmentsToInsert[3]['name'], true)["en"]) . ' - K55', 'department_id' => $departmentsToInsert[3]['uuid'], 'start_year' => '2019', 'end_year' => '2023'],
                ["name" => json_decode($departmentsToInsert[4]['name'], true)["vi"] . ' K55', 'uuid' => Str::uuid()->toString(), 'code' => $this->abbreviateString(json_decode($departmentsToInsert[4]['name'], true)["en"]) . ' - K55', 'department_id' => $departmentsToInsert[4]['uuid'], 'start_year' => '2019', 'end_year' => '2023'],
            ];
    
            foreach($classesToInsert as $class_) {
                $class_['created_at'] = new \Datetime();
                $class_['updated_at'] = new \Datetime();
                DB::table('class_')->insert($class_);
            }
    
            $subjectsToInsert = [
                ["name" => '{"vi": "Tin học đại cương", "en": "General information"}', 'uuid' => Str::uuid()->toString(), 'code' => 'GI001', 'department_id' => $departmentsToInsert[0]['uuid']],
                ["name" => '{"vi": "Cấu trúc dữ liệu & thuật toán I", "en": "Data Structures & Algorithms I"}', 'uuid' => Str::uuid()->toString(), 'code' => 'DSA001', 'department_id' => $departmentsToInsert[0]['uuid']],
                ["name" => '{"vi": "Toán cao cấp", "en": "Advanced math"}', 'uuid' => Str::uuid()->toString(), 'code' => 'AM001', 'department_id' => ''],
                ["name" => '{"vi": "Triết học I", "en": "Philosophy I"}', 'uuid' => Str::uuid()->toString(), 'code' => 'PHI001', 'color' => 'red', 'department_id' => ''],
                ["name" => '{"vi": "Triết học II", "en": "Philosophy II"}', 'uuid' => Str::uuid()->toString(), 'code' => 'PHI002', 'color' => 'pink', 'department_id' => ''],
                ["name" => '{"vi": "Tiếng Anh chuyên ngành 1", "en": "Technical English 1"}', 'uuid' => Str::uuid()->toString(), 'code' => 'TE001', 'color' => 'green', 'department_id' => ''],
                ["name" => '{"vi": "Tiếng Anh chuyên ngành 2", "en": "Technical English 2"}', 'uuid' => Str::uuid()->toString(), 'code' => 'TE002', 'color' => 'blue', 'department_id' => ''],
            ];
    
            foreach($subjectsToInsert as $s) {
                $s['created_at'] = new \Datetime();
                $s['updated_at'] = new \Datetime();
                DB::table('subjects')->insert($s);
            }
    
            $intakesToInsert = [
                ['uuid' => Str::uuid()->toString(), 'code' => 'GI001-082023', 'subject_id' => $subjectsToInsert[0]['uuid'], 'start_date' => '2023-08-01', 'end_date' => '2023-12-31', 'duration_weeks' => 17, 'start_hour' => 7, 'start_minute' => 0, 'end_hour' => 8, 'end_minute' => 45, 'week_days' => '2,4,6'],
                ['uuid' => Str::uuid()->toString(), 'code' => 'DSA001-082023', 'subject_id' => $subjectsToInsert[1]['uuid'], 'start_date' => '2023-08-01', 'end_date' => '2023-12-31', 'duration_weeks' => 17, 'start_hour' => 9, 'start_minute' => 15, 'end_hour' => 11, 'end_minute' => 0, 'week_days' => '2,4,6'],
                ['uuid' => Str::uuid()->toString(), 'code' => 'AM001-082023', 'subject_id' => $subjectsToInsert[2]['uuid'], 'start_date' => '2023-08-01', 'end_date' => '2023-12-31', 'duration_weeks' => 17, 'start_hour' => 13, 'start_minute' => 15, 'end_hour' => 15, 'end_minute' => 0, 'week_days' => '2,4,6'],
                ['uuid' => Str::uuid()->toString(), 'code' => 'PHI001-082023', 'subject_id' => $subjectsToInsert[3]['uuid'], 'start_date' => '2023-08-01', 'end_date' => '2023-12-31', 'duration_weeks' => 17, 'start_hour' => 7, 'start_minute' => 0, 'end_hour' => 8, 'end_minute' => 45, 'week_days' => '3,5,7'],
                ['uuid' => Str::uuid()->toString(), 'code' => 'PHI002-082023', 'subject_id' => $subjectsToInsert[4]['uuid'], 'start_date' => '2023-08-01', 'end_date' => '2023-12-31', 'duration_weeks' => 17, 'start_hour' => 9, 'start_minute' => 15, 'end_hour' => 11, 'end_minute' => 0, 'week_days' => '3,5,7'],
                ['uuid' => Str::uuid()->toString(), 'code' => 'TE001-082023', 'subject_id' => $subjectsToInsert[5]['uuid'], 'start_date' => '2023-08-01', 'end_date' => '2023-12-31', 'duration_weeks' => 17, 'start_hour' => 13, 'start_minute' => 15, 'end_hour' => 15, 'end_minute' => 0, 'week_days' => '3,5,7'],
                ['uuid' => Str::uuid()->toString(), 'code' => 'TE002-082023', 'subject_id' => $subjectsToInsert[6]['uuid'], 'start_date' => '2023-08-01', 'end_date' => '2023-12-31', 'duration_weeks' => 17, 'start_hour' => 14, 'start_minute' => 15, 'end_hour' => 16, 'end_minute' => 30, 'week_days' => '3,5,7'],
            ];
    
            foreach($intakesToInsert as $i) {
                $i['created_at'] = new \Datetime();
                $i['updated_at'] = new \Datetime();
                DB::table('intakes')->insert($i);
            }
    
            $usersToInsert = [
                ["first_name" => "Minh Quyen", "last_name" => "Le", 'role' => 'student', 'gender' => 1, 'unique_id' => '0002', 'date_of_birth' => '2000-10-19', 'uuid' => Str::uuid()->toString()],
                ["first_name" => "Minh Sieu", "last_name" => "Le", 'role' => 'student', 'gender' => 1, 'unique_id' => '0003', 'date_of_birth' => '2000-10-19', 'uuid' => Str::uuid()->toString()],
                ["first_name" => "Anh Tuan", "last_name" => "Nguyen", 'role' => 'student', 'gender' => 1, 'unique_id' => '0004', 'date_of_birth' => '2000-10-19', 'uuid' => Str::uuid()->toString()],
                ["first_name" => "Hai Long", "last_name" => "Nguyen", 'role' => 'student', 'gender' => 1, 'unique_id' => '0005', 'date_of_birth' => '2000-10-19', 'uuid' => Str::uuid()->toString()],
                ["first_name" => "Thuc Anh", "last_name" => "Le", 'role' => 'student', 'gender' => 2, 'unique_id' => '0006', 'date_of_birth' => '2000-10-19', 'uuid' => Str::uuid()->toString()],
                ["first_name" => "Minh Anh", "last_name" => "Le", 'role' => 'student', 'gender' => 2, 'unique_id' => '0007', 'date_of_birth' => '2000-10-19', 'uuid' => Str::uuid()->toString()],
                ["first_name" => "Nam", "last_name" => "Nguyen", 'role' => 'student', 'gender' => 1, 'unique_id' => '0008', 'date_of_birth' => '2000-10-19', 'uuid' => Str::uuid()->toString()],
                ["first_name" => "Hoai Nam", "last_name" => "Le", 'role' => 'student', 'gender' => 1, 'unique_id' => '0009', 'date_of_birth' => '2000-10-19', 'uuid' => Str::uuid()->toString()],
                ["first_name" => "Thuy Linh", "last_name" => "Nguyen", 'role' => 'student', 'gender' => 2, 'unique_id' => '0010', 'date_of_birth' => '2000-10-19', 'uuid' => Str::uuid()->toString()],
                ["first_name" => "Yuki", "last_name" => "Kimura", 'role' => 'student', 'gender' => 1, 'unique_id' => '0011', 'date_of_birth' => '2000-10-19', 'uuid' => Str::uuid()->toString()],
                ["first_name" => "Genki", "last_name" => "Kimura", 'role' => 'student', 'gender' => 1, 'unique_id' => '0012', 'date_of_birth' => '2000-10-19', 'uuid' => Str::uuid()->toString()],
                ["first_name" => "Ngoc Linh", "last_name" => "Doan", 'role' => 'teacher', 'gender' => 2, 'unique_id' => '1001', 'date_of_birth' => '1998-10-19', 'uuid' => Str::uuid()->toString()],
                ["first_name" => "Minh Quan", "last_name" => "Nguyen", 'role' => 'teacher', 'gender' => 1, 'unique_id' => '1002', 'date_of_birth' => '1998-10-19', 'uuid' => Str::uuid()->toString()],
                ["first_name" => "Amin", "last_name" => "System", 'role' => 'admin', 'gender' => 3, 'unique_id' => '2000', 'date_of_birth' => '1998-03-30', 'uuid' => Str::uuid()->toString()],
            ];
    
            $currentDepartmentId = 0;
            $currentUserIndex = 0;
            foreach($usersToInsert as $u) {
                $u['password'] = Hash::make('Admin123');
                if($u['role'] != 'admin') {
                    $u['department_id'] = $departmentsToInsert[$currentDepartmentId]['uuid'];
                }
    
                $u['email'] = $u['unique_id'] . '@gmail.com';
                $u['created_at'] = new \Datetime();
                $u['updated_at'] = new \Datetime();

                DB::table('user')->insert($u);
    
                $currentDepartmentId++;
                if($currentDepartmentId > 4) {
                    $currentDepartmentId = 0;
                }
    
                // create class member
                if($u['role'] == 'student') {
                    $classRole['uuid'] = Str::uuid()->toString();
                    $classRole['user_id'] = $u['uuid'];
                    $classRole['role'] = 'student';

                    if($currentUserIndex == 0 || $currentUserIndex == 1) {
                        $classRole['class_id'] = $classesToInsert[0]['uuid'];
                    }
                    if($currentUserIndex == 2 || $currentUserIndex == 3) {
                        $classRole['class_id'] = $classesToInsert[1]['uuid'];
                    }
                    if($currentUserIndex == 4 || $currentUserIndex == 5) {
                        $classRole['class_id'] = $classesToInsert[2]['uuid'];
                    }
                    if($currentUserIndex == 6 || $currentUserIndex == 7) {
                        $classRole['class_id'] = $classesToInsert[3]['uuid'];
                    }
                    if($currentUserIndex == 8 || $currentUserIndex == 9 || $currentUserIndex == 10) {
                        $classRole['class_id'] = $classesToInsert[4]['uuid'];
                    }
    
                    $classRole['created_at'] = new \Datetime();
                    $classRole['updated_at'] = new \Datetime();
                    DB::table('class_roles')->insert($classRole);
                }
    
                if($u['role'] == 'teacher') {
                    foreach($classesToInsert as $class_) {
                        $classRole['uuid'] = Str::uuid()->toString();
                        $classRole['user_id'] = $u['uuid'];
                        $classRole['role'] = 'teacher';
                        $classRole['class_id'] = $class_['uuid'];
                        $classRole['created_at'] = new \Datetime();
                        $classRole['updated_at'] = new \Datetime();
                        DB::table('class_roles')->insert($classRole);
                    }
                }
    
                // insert intake members
                if($u['role'] != 'admin') {
                    foreach($intakesToInsert as $intake) {
                        $intakeMember['uuid'] = Str::uuid()->toString();
                        $intakeMember['user_id'] = $u['uuid'];
                        $intakeMember['intake_id'] = $intake['uuid'];
                        $intakeMember['role'] = $u['role'];
                        if($u['role'] == 'student') {
                            $intakeMember['attendance_points'] = rand(1, 10);
                            $intakeMember['mid_term_points'] = rand(1, 10);
                            $intakeMember['last_term_points'] = rand(1, 10);
                        }
                        $intakeMember['created_at'] = new \Datetime();
                        $intakeMember['updated_at'] = new \Datetime();
                        DB::table('intake_members')->insert($intakeMember);
                    }
                }
    
                $currentUserIndex++;
            }
            
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
        }
    }
}
