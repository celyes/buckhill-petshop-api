<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateUserRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\EditUserAccountRequest;
use App\Http\Requests\LogoutRequest;
use App\Http\Requests\ViewUserRequest;
use App\Http\Resources\UserResource;
use App\Services\AccountService;
use App\Services\JwtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;


/**
 * @OA\Tag(
 *      name="User",
 *      description="Authentication related endpoints"
 *  ),
 */
class UserController extends Controller
{
    protected JwtService $jwtService;
    protected AccountService $accountService;

    public function __construct()
    {
        $this->accountService = App::make(AccountService::class);
        $this->jwtService = App::make(JwtService::class);
    }


    /**
     * @param AuthenticateUserRequest $request
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/user/login",
     *     summary="Authenticate a user",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="User logged in successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function login(AuthenticateUserRequest $request): JsonResponse
    {
        if (
            $accountData = $this->accountService->authenticate(
                $request->input('email'),
                $request->input('password')
            )
        ) {
            return response()->json($accountData);
        }
        abort(400, 'Invalid credentials');
    }

    /**
     * @param CreateUserRequest $request
     * @return JsonResponse|void
     * @OA\Post(
     *      path="/api/v1/user/create",
     *      summary="Register a new user",
     *      tags={"User"},
     * @OA\Parameter(
     *           name="first_name",
     *           in="query",
     *           description="User's first name",
     *           required=true,
     *           @OA\Schema(type="string")
     *       ),
     * @OA\Parameter(
     *            name="last_name",
     *            in="query",
     *            description="User's last name",
     *            required=true,
     *            @OA\Schema(type="string")
     *        ),
     * @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="User's email",
     *          required=true,
     *          @OA\Schema(type="string")
     *      ),
     * @OA\Parameter(
     *          name="password",
     *          in="query",
     *          description="User's password",
     *          required=true,
     *          @OA\Schema(type="string")
     *      ),
     * @OA\Parameter(
     *           name="password_confirmation",
     *           in="query",
     *           description="User's password confirmation",
     *           required=true,
     *           @OA\Schema(type="string")
     *       ),
     * @OA\Parameter(
     *            name="password_confirmation",
     *            in="query",
     *            description="User's password confirmation",
     *            required=true,
     *            @OA\Schema(type="string")
     *        ),
     * @OA\Parameter(
     *             name="address",
     *             in="query",
     *             description="User's address",
     *             required=true,
     *             @OA\Schema(type="string")
     *         ),
     * @OA\Parameter(
     *              name="phone_number",
     *              in="query",
     *              description="User's phone number",
     *              required=true,
     *              @OA\Schema(type="string")
     *          ),
     * @OA\Parameter(
     *              name="avatar",
     *              in="query",
     *              description="User's avatar URL",
     *              required=true,
     *              @OA\Schema(type="string")
     *          ),
     *  @OA\Parameter(
     *               name="is_marketing",
     *               in="query",
     *               description="User is associated with marketing department",
     *               required=true,
     *               @OA\Schema(type="boolean")
     *           ),
     * @OA\Response(response="201", description="User registered successfully"),
     * @OA\Response(response="422", description="Validation errors")
     *  )
     */
    public function create(CreateUserRequest $request)
    {
        try {
            $accountData = $this->accountService->create($request->validated());
            return response()->json($accountData, 201);
        } catch (HttpException) {
            abort(401, 'Invalid credentials');
        }
    }

    /**
     * @param ViewUserRequest $request
     * @return UserResource
     * @OA\Get(
     *      path="/api/v1/user",
     *      summary="View a user account",
     *      tags={"User"},
     *      @OA\Response(response="200", description="Success"),
     *      security={{"bearerAuth":{}}}
     *  )
     */
    public function view(ViewUserRequest $request)
    {
        return new UserResource($request->user());
    }

    /**
     * @param EditUserAccountRequest $request
     * @return UserResource
     * @OA\Put(
     *       path="/api/v1/user/edit",
     *       summary="Edit use raccount",
     *       tags={"User"},
     *  @OA\Parameter(
     *            name="first_name",
     *            in="query",
     *            description="User's first name",
     *            required=false,
     *            @OA\Schema(type="string")
     *        ),
     *  @OA\Parameter(
     *             name="last_name",
     *             in="query",
     *             description="User's last name",
     *             required=false,
     *             @OA\Schema(type="string")
     *         ),
     *  @OA\Parameter(
     *           name="email",
     *           in="query",
     *           description="User's email",
     *           required=false,
     *           @OA\Schema(type="string")
     *       ),
     *  @OA\Parameter(
     *           name="password",
     *           in="query",
     *           description="User's password",
     *           required=false,
     *           @OA\Schema(type="string")
     *       ),
     *  @OA\Parameter(
     *            name="password_confirmation",
     *            in="query",
     *            description="User's password confirmation",
     *            required=false,
     *            @OA\Schema(type="string")
     *        ),
     *  @OA\Parameter(
     *             name="password_confirmation",
     *             in="query",
     *             description="User's password confirmation",
     *             required=false,
     *             @OA\Schema(type="string")
     *         ),
     *  @OA\Parameter(
     *              name="address",
     *              in="query",
     *              description="User's address",
     *              required=false,
     *              @OA\Schema(type="string")
     *          ),
     *  @OA\Parameter(
     *               name="phone_number",
     *               in="query",
     *               description="User's phone number",
     *               required=false,
     *               @OA\Schema(type="string")
     *           ),
     *  @OA\Parameter(
     *               name="avatar",
     *               in="query",
     *               description="User's avatar URL",
     *               required=false,
     *               @OA\Schema(type="string")
     *           ),
     *   @OA\Parameter(
     *                name="is_marketing",
     *                in="query",
     *                description="User is associated with marketing department",
     *                required=false,
     *                @OA\Schema(type="boolean")
     *            ),
     *  @OA\Response(response="201", description="User account updated successfully"),
     *  @OA\Response(response="422", description="Validation errors")
     *   )
     */
    public function edit(EditUserAccountRequest $request): JsonResource
    {
        $user = $request->user();
        $user->fill($request->validated());
        $user->save();
        return new UserResource($user);
    }

    /**
     * @param LogoutRequest $request
     * @return JsonResponse
     * @OA\Get(
     *       path="/api/v1/user/logout",
     *       summary="Log out",
     *       tags={"User"},
     *       @OA\Response(response="200", description="Logged out successfully"),
     *       security={{"bearerAuth":{}}}
     *   )
     * /
     */
    public function logout(LogoutRequest $request): JsonResponse
    {
        if ($this->accountService->logout($request->bearerToken())) {
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);
        }
        abort(400, "Invalid credentials");
    }

    /**
     * @param DeleteUserRequest $request
     * @return JsonResponse|void
     * @OA\Delete(
     *       path="/api/v1/user",
     *       summary="View a user account",
     *       tags={"User"},
     *       @OA\Response(response="200", description="Account deleted successfully"),
     *       security={{"bearerAuth":{}}}
     *   )
     * /
     */
    public function delete(DeleteUserRequest $request)
    {
        $isDeleted = $this->accountService->deleteAccount($request->user());
        if ($isDeleted) {
            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully'
            ]);
        }
        abort(400, "Invalid credentials");
    }
}
