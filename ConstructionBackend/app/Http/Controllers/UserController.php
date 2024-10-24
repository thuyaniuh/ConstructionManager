<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $users = User::paginate(2);
        $users = User::limit(10)->latest("user_id")->get();
        // Duyệt qua từng người dùng và thêm đường dẫn đầy đủ của avatar (nếu có)
        $users->map(function ($user) {
            if ($user->avatar) {
                $user->avatar_url = asset('storage/' . $user->avatar);
            }
            return $user;
        });

        return response()->json($users, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'active_status' => 'required|in:active,locked',
            // 'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048' // Kiểm tra file ảnh
        ]);

        // Xử lý upload ảnh
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public'); // Lưu ảnh trong thư mục avatars
        }

        // Tạo người dùng mới
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'active_status' => $validated['active_status'] ?? "active",
            'avatar' => $avatarPath,
            'birth' => Carbon::parse($request->dateOfBirth),
            'phone' => $request->phone ?? "",
        ]);

        // return true;/
        return response()->json($user, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        // Thêm đường dẫn đầy đủ của ảnh vào response
        if ($user->avatar) {
            $user->avatar_url = asset('storage/' . $user->avatar);
        }

        return response()->json($user, 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:8',
            'active_status' => 'required|in:active,locked',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048' // Kiểm tra file ảnh
        ]);

        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu có
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Upload ảnh mới
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }
}
