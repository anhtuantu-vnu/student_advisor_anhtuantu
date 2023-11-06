<?php
namespace App\Services;


use App\Models\Class_;
use App\Models\User;
use App\Repositories\ClassRepository;
use App\Repositories\ClassRoleRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FileServices
{
    /**
     * @var ClassRepository
     */
    protected ClassRepository $classRepository;
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;
    /**
     * @var ClassRoleRepository
     */
    protected ClassRoleRepository $classRoleRepository;

    /**
     * @param ClassRepository $classRepository
     * @param UserRepository $userRepository
     * @param ClassRoleRepository $classRoleRepository
     */
    public function __construct(
        ClassRepository $classRepository,
        UserRepository $userRepository,
        ClassRoleRepository $classRoleRepository
    )
    {
        $this->classRepository = $classRepository;
        $this->userRepository = $userRepository;
        $this->classRoleRepository = $classRoleRepository;
    }

    public function importFileStudent ($data) {
        try {
//            DB::beginTransaction();
            $class = $this->classRepository->checkClassInFileUpload($data);
            $users = $this->userRepository->create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'role' => User::ROLE_STUDENT,
                'unique_id' => Str::uuid(),
                'email' => $data['email'],
                'password' => $data['password'],
                'uuid' => Str::uuid(),
                'gender' => User::CONFIG_GENDER[$data['gender']],
                'date_of_birth' => $data['date_of_birth'],
                'active_status' => User::DEFAULT_STATUS_ACTIVE,
                'avatar' => User::LINK_AVA,
                'dark_mode' => User::DEFAULT_DARK_MODE,
                'lang' => User::DEFAULT_LANG,
                'created_at' => Carbon::today(),
                'updated_at' => Carbon::today()
            ]);
            $this->classRepository->create([
                'uuid' => Str::uuid(),
                'user_id' => $users['department_id'],
                'created_at' => Carbon::today(),
                'updated_at' => Carbon::today(),
                'class_id' => $class['department_id']
            ]);
//            DB::commit();
            return true;
        } catch (\Throwable $th){
//            DB::rollBack();
            return $th;
        }
    }
}
