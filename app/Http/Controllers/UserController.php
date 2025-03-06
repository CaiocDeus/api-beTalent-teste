<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return JsonResource::collection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $user = new User;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->role = $request->role;
        $user->save();

        return response()->json([
            "id" => $user->id,
            "message" => "Usuário criado"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new JsonResource(User::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $user->email = is_null($request->email) ? $user->email : $request->email;
        $user->password = is_null($request->password) ? $user->password : $request->password;
        $user->role = is_null($request->role) ? $user->role : $request->role;
        $user->save();

        return response()->json([
            "message" => 'Usuário atualizado'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json([
            "message" => "Usuário deletado"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function login(Request $request)
    {
        $user = User::where([
            ['email', '=', $request->email]
        ])->firstOrFail();

        if (!Hash::check($request->password, $user->password)) {
            throw new UnauthorizedException("Senha inválida");
        }

        $token = $user->createToken('Token usuário', [$user->role]);

        return response()->json([
            "token" => $token->plainTextToken
        ]);
    }
}
