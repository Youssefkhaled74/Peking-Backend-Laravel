<?php

namespace App\Http\Controllers\Auth;

use App\Events\SendResetPassword;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Smartisan\Settings\Facades\Settings;

class ForgotPasswordController extends Controller
{
    public int $pin;

    public function forgotPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'max:255', 'exists:users,phone'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }

        $verify = User::where('phone', $request->post('phone'))->exists();

        if ($verify) {
            $verify = DB::table('password_resets')->where([
                ['email', $request->post('phone')]
            ]);

            if ($verify->exists()) {
                $verify->delete();
            }

            $this->pin = rand(
                pow(10, (int)Settings::group('otp')->get('otp_digit_limit') - 1),
                pow(10, (int)Settings::group('otp')->get('otp_digit_limit')) - 1
            );

            $password_reset = DB::table('password_resets')->insert([
                'email' => $request->post('phone'),
                // 'token' => $this->pin,
                'token' => 1111,
                'created_at' => Carbon::now()
            ]);

            if ($password_reset) {
                SendResetPassword::dispatch(['phone' => $request->post('phone'), 'pin' => $this->pin]);
                return new JsonResponse([
                    'message' => trans('all.message.check_your_phone_for_code')
                ], 200);
            } else {
                return new JsonResponse([
                    'errors' => ['phone' => [trans('all.message.token_created_fail')]]
                ], 400);
            }
        } else {
            return new JsonResponse([
                'errors' => ['phone' => [trans('all.message.phone_does_not_exist')]]
            ], 400);
        }
    }

    public function verifyCode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'max:255', 'exists:users,phone'],
            'code' => ['required'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }

        $check = DB::table('password_resets')->where([
            ['email', $request->post('phone')],
            ['token', $request->post('code')],
        ]);

        if ($check->exists()) {
            $difference = Carbon::now()->diffInSeconds($check->first()->created_at);

            if ($difference > (int)Settings::group('otp')->get('otp_expire_time') * 60) {
                return new JsonResponse([
                    'errors' => ['code' => [trans('all.message.code_is_expired')]]
                ], 400);
            }

            $check->delete();

            return new JsonResponse([
                'message' => trans('all.message.you_can_reset_your_password')
            ], 200);
        } else {
            return new JsonResponse([
                'errors' => ['code' => [trans('all.message.code_is_invalid')]]
            ], 400);
        }
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'max:255', 'exists:users,phone'],
            'code' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:6'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['errors' => $validator->errors()], 422);
        }

        $user = User::where('phone', $request->post('phone'));
        $user->update([
            'password' => Hash::make($request->post('password'))
        ]);

        $this->token = $user->first()->createToken('auth_token')->plainTextToken;

        return new JsonResponse([
            'message' => "Your password has been reset",
            'token' => $this->token
        ], 200);
    }
}