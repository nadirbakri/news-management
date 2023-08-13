<?php

namespace App\Http\Controllers;

use App\Events\UserDeleted;
use App\Events\UserLogin;
use App\Events\UserRegistered;
use App\Events\UserUpdate;
use App\Events\UserUpdateAdmin;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepository->get(10);

        if ($users->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No users found',
                'data' => [],
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Users retrieved successfully',
            'data' => UserResource::collection($users),
        ], 200);
    }

    public function show(string $id)
    {
        try {
            $users = $this->userRepository->find($id);

            return response()->json([
                'status' => 'success',
                'message' => 'User retrieved successfully',
                'data' => new UserResource($users),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User not found',
                'data' => null,
            ], 404);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'name'      => 'required',
                'password'  => 'required|min:8',
                'confirm'   => 'required|same:password',
            ]);

            $user = $this->userRepository->update($id, $validatedData);

            event(new UserUpdate($user));

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => new UserResource($user),
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->errors(),
                'data' => null,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred while updating the news',
                'data' => null,
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $user = $this->userRepository->delete($id);

            event(new UserDeleted($user));

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully',
                'data' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred while updating the news',
                'data' => null,
            ], 500);
        }
    }

    public function statusUpdate(string $id): JsonResponse
    {
        try {
            $data['isAdmin'] = true;

            $user = $this->userRepository->update($id, $data);

            event(new UserUpdateAdmin($user));

            return response()->json([
                'status' => 'success',
                'message' => 'User has been granted admin privileges',
                'data' => new UserResource($user),
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->errors(),
                'data' => null,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred while updating the news',
                'data' => null,
            ], 500);
        }
    }

    public function register(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'name'      => 'required',
                'email'     => 'required|email|unique:users',
                'password'  => 'required|min:8',
                'confirm'   => 'required|same:password',
            ]);

            $user = $this->userRepository->create([
                'name'      => $validatedData['name'],
                'email'     => $validatedData['email'],
                'isAdmin'   => false,
                'password'  => bcrypt($validatedData['password']),
            ]);

            $token = $user->createToken('API')->accessToken;

            $response = [
                'token' => $token,
                'name' => $user->name,
            ];

            event(new UserRegistered($user));

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'data' => new UserResource($response),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->errors(),
                'data' => null,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            if (!auth()->attempt($validatedData)) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $user = auth()->user();
            $token = $user->createToken('API')->accessToken;

            $response = [
                'token' => $token,
                'name' => $user->name,
            ];

            event(new UserLogin($user));

            return response()->json([
                'status' => 'success',
                'message' => 'Login success',
                'data' => new UserResource($response),
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->errors(),
                'data' => null,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
