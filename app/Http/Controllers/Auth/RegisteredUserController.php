<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use DB;

use App\Models\Users\Subjects;
use App\Models\Users\User;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $subjects = Subjects::all();
        return view('auth.register.register', compact('subjects'));
    }


    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterUserRequest $request)
    {
        // 生年月日チェック（FormRequestでは難しいためここで実施）
        if (!checkdate((int)$request->old_month, (int)$request->old_day, (int)$request->old_year)) {
            return back()->withErrors(['birth_day' => '正しい生年月日を選択してください'])->withInput();
        }

        DB::beginTransaction();
        try {
            $birth_day = $request->old_year . '-' . $request->old_month . '-' . $request->old_day;

            $user_get = User::create([
                'over_name' => $request->over_name,
                'under_name' => $request->under_name,
                'over_name_kana' => $request->over_name_kana,
                'under_name_kana' => $request->under_name_kana,
                'mail_address' => $request->mail_address,
                'sex' => $request->sex,
                'birth_day' => $birth_day,
                'role' => $request->role,
                'password' => bcrypt($request->password)
            ]);

            if ($request->role == 4) {
                $user_get->subjects()->attach($request->subject);
            }

            DB::commit();
            return view('auth.login.login');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('loginView')->withErrors(['error' => '登録処理中にエラーが発生しました。']);
        }
    }
}
