<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    // Get paginated members with search and transactions (migrated from native index.php)
    public function index(Request $request)
    {
        //check if user is authenticated
        $authCheck = $this->validateMemberToken($request, ['admin']);
        if ($authCheck instanceof JsonResponse) {
            return $authCheck; // Returns 401 or 403 response immediately if invalid
        }

        $name = $request->query('name', '');
        $page = (int) $request->query('page', 1);
        $limit = 6;
        $offset = ($page - 1) * $limit;

        // Base query: role must be 'member' and match optional search term
        $query = Member::with('transactions.book')
            ->where('role', 'member')
            ->when($name, function ($q) use ($name) {
                return $q->where('full_name', 'like', '%' . $name . '%');
            });

        // Get total count matching the criteria for pagination
        $totalMembers = $query->count();

        // Apply limit and offset matching your native logic
        $members = $query->skip($offset)
            ->take($limit)
            ->get();

        return response()->json([
            "members" => $members,
            "total" => $totalMembers
        ], 200);
    }

    // Create a new member (migrated from members/create.php)
    public function store(Request $request)
    {   
        //check if authenticated and if admin
        $authCheck = $this->validateMemberToken($request, ['admin']);
        if ($authCheck instanceof JsonResponse) {
            return $authCheck; // Returns 401 or 403 response immediately if invalid
        }

        $validated = $request->validate([
            'full_name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^\p{L}[\p{L}\p{M} .\'–-]*$/u'
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:members,email'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:64'
            ],
            'phone_number' => [
                'required',
                'string',
                'regex:/^09\d{9}$/'
            ],
            'address' => [
                'required',
                'string',
                'min:5',
                'max:255',
                'not_regex:/[\x00-\x1F\x7F]/'
            ],
            'role' => 'sometimes|in:admin,member',
        ], [
            // Custom error messages to match native behavior
            'full_name.required' => 'Full Name Is Required',
            'full_name.min' => 'Full Name must be 2 to 100 characters',
            'full_name.max' => 'Full Name must be 2 to 100 characters',
            'full_name.regex' => 'Full Name contains invalid characters',
            
            'email.required' => 'Email Is Required',
            'email.email' => 'Invalid Email',
            'email.max' => 'Email must not exceed 255 characters',
            'email.unique' => 'Email already exists.',
            
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.max' => 'Password must not exceed 64 characters',
            
            'phone_number.required' => 'Phone Number Is Required',
            'phone_number.regex' => 'Invalid Philippine Phone Number',
            
            'address.required' => 'Address Is Required',
            'address.min' => 'Address must be 5 to 255 characters',
            'address.max' => 'Address must be 5 to 255 characters',
            'address.not_regex' => 'Address contains invalid characters',
        ]);

        try {
            $member = Member::create([
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'password_hash' => Hash::make($validated['password']),
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
                'role' => $validated['role'] ?? 'member',
                'api_token' => bin2hex(random_bytes(40)),
            ]);

            return response()->json([
                'message' => 'Member "' . $member->full_name . '" added successfully.'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to add member.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    // Update a member (migrated from members/update.php)
    public function update(Request $request, string $id)
    {
        //check if authenticated and if admin
        $authCheck = $this->validateMemberToken($request, ['admin']);
        if ($authCheck instanceof JsonResponse) {
            return $authCheck; // Returns 401 or 403 response immediately if invalid
        }

        //Find the member first
        $member = Member::find($id);

        if (!$member) {
            return response()->json([
                'message' => 'Member not found.'
            ], 404);
        }

        // Validate input
        // Notice 'unique:members,email,' . $id ignores the current member's own email
        $validated = $request->validate([
            'full_name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^\p{L}[\p{L}\p{M} .\'–-]*$/u'
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:members,email,' . $id
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'max:64'
            ],
            'phone_number' => [
                'required',
                'string',
                'regex:/^09\d{9}$/'
            ],
            'address' => [
                'required',
                'string',
                'min:5',
                'max:255',
                'not_regex:/[\x00-\x1F\x7F]/'
            ],
        ], [
            // Custom error messages mirroring native validation
            'full_name.required' => 'Full Name Is Required',
            'full_name.min' => 'Full Name must be 2 to 100 characters',
            'full_name.max' => 'Full Name must be 2 to 100 characters',
            'full_name.regex' => 'Full Name contains invalid characters',
            
            'email.required' => 'Email Is Required',
            'email.email' => 'Invalid Email',
            'email.max' => 'Email must not exceed 255 characters',
            'email.unique' => 'Email already exists.',
            
            'password.min' => 'Password must be at least 8 characters',
            'password.max' => 'Password must not exceed 64 characters',
            
            'phone_number.required' => 'Phone Number Is Required',
            'phone_number.regex' => 'Invalid Philippine Phone Number',
            
            'address.required' => 'Address Is Required',
            'address.min' => 'Address must be 5 to 255 characters',
            'address.max' => 'Address must be 5 to 255 characters',
            'address.not_regex' => 'Address contains invalid characters',
        ]);

        try {
            // Update fields
            $member->full_name = $validated['full_name'];
            $member->email = $validated['email'];
            $member->phone_number = $validated['phone_number'];
            $member->address = $validated['address'];

            // Update password only if a new one is provided and not empty/whitespace
            if (!empty($validated['password'])) {
                $member->password_hash = Hash::make($validated['password']);
            }

            $member->save();

            return response()->json([
                'message' => 'ID: ' . $id . ' Edited Successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update member.'
            ], 500);
        }
    }

    // Delete a member (migrated from members/delete.php)
    public function destroy(Request $request, string $id)
    {
        //check if authenticated and if admin
        $authCheck = $this->validateMemberToken($request, ['admin']);
        if ($authCheck instanceof JsonResponse) {
            return $authCheck; // Returns 401 or 403 response immediately if invalid
        }

        // Find the member
        $member = Member::find($id);

        if (!$member) {
            return response()->json([
                'message' => 'Member not found.'
            ], 404);
        }

        // Check if the member has existing transaction records using Eloquent relationship
        if ($member->transactions()->exists()) {
            return response()->json([
                'message' => 'Cannot Delete "' . $member->full_name . '" This Member Has Existing Transaction Record'
            ], 409);
        }

        $memberName = $member->full_name;

        //Delete the member record
        $member->delete();

        return response()->json([
            'message' => 'Member "' . $memberName . '" Deleted Successfully.'
        ], 200);
    }

    //Helper function for checking session token and authorization
    private function validateMemberToken(Request $request, array $allowedRoles = ['admin', 'member'])
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'No token provided'], 401);
        }

        // Find member by token stored in database
        $member = Member::where('api_token', $token)->first();

        if (!$member) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        // Check if their role is permitted for this action
        if (!in_array($member->role, $allowedRoles)) {
            return response()->json(['message' => 'Forbidden: Insufficient privileges'], 403);
        }

        return $member; // Returns the authenticated user if valid
    }
}
