<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function register(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'กรุณากรอกชื่อ',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัว',
            'password.confirmed' => 'การยืนยันรหัสผ่านไม่ตรงกัน',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $validator->validated();
            Log::info('Validated data:', $data);

            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);

            Log::info('Created user:', ['id' => $user->id]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'สมัครสมาชิกสำเร็จ',
                'user' => $user,
                'token' => $token,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Register error: ' . $e->getMessage());
            return response()->json([
                'error' => 'เกิดข้อผิดพลาดในการสมัครสมาชิก',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        // ตรวจสอบข้อมูลเบื้องต้น
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        try {
            // พยายาม login และสร้าง token
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'], 401);
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            // จับ error ที่อาจเกิดจาก JWT
            return response()->json(['error' => 'ไม่สามารถสร้าง token ได้'], 500);
        }

        // ส่ง token กลับพร้อมข้อมูลผู้ใช้ (optional)
        return response()->json([
            'message' => 'เข้าสู่ระบบสำเร็จ',
            'token' => $token,
            'user' => auth()->user(),
        ]);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'ออกจากระบบสำเร็จ']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'ไม่สามารถออกจากระบบได้'], 500);
        }
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
