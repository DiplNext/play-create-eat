<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\GenderEnum;
use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Symfony\Component\HttpFoundation\Response;

class ChildController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/children",
     *     summary="Create a new child",
     *     tags={"Children"},
     *     security={{"Sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "birth_date", "gender"},
     *             @OA\Property(property="first_name", type="string", maxLength=255, example="John", description="First name of the child"),
     *             @OA\Property(property="last_name", type="string", maxLength=255, example="Doe", description="Last name of the child"),
     *             @OA\Property(property="birth_date", type="string", format="date", example="2015-06-15", description="Birth date of the child in YYYY-MM-DD format"),
     *             @OA\Property(
     *                 property="gender",
     *                 type="string",
     *                 enum={"male", "female", "other"},
     *                 example="male",
     *                 description="Gender of the child (male, female, other)"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Child created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1, description="ID of the created child"),
     *             @OA\Property(property="first_name", type="string", example="John", description="First name of the child"),
     *             @OA\Property(property="last_name", type="string", example="Doe", description="Last name of the child"),
     *             @OA\Property(property="birth_date", type="string", format="date", example="2015-06-15", description="Birth date of the child"),
     *             @OA\Property(property="gender", type="string", example="male", description="Gender of the child"),
     *             @OA\Property(property="family_id", type="integer", example=5, description="Family ID associated with the child"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-06T12:34:56Z", description="Timestamp of creation"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-06T12:34:56Z", description="Timestamp of last update")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", description="An object containing validation errors for specific fields")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender'     => ['required', 'string', new Enum(GenderEnum::class)],
        ]);

        $child = Child::create([...$validated, 'family_id' => auth()->user()->family_id]);

        return response()->json($child, Response::HTTP_CREATED);
    }
}
