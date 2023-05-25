<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\GroupValidationRule;
use App\Rules\NameValidationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('profile');
    }

    public function updateUserData(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', new NameValidationRule],
            'group' => ['nullable', 'string', 'max:255', new GroupValidationRule()],
            'avatar' => 'image|max:8176'
        ]);

        /** @var User */
        $user = auth()->user();

        $user->name = $request->input('name');

        if(
            $user->role->name === 'user' &&
            !$user->isCoworker() &&
            !$user->isAdmin() &&
            !$request->has('group')
        ) {
            throw  ValidationException::withMessages([
                'group' => [__('validation.required')],
            ]);
        }

        if($request->has('group')) {
            $user->group = $request->input('group');
        }

        if($request->hasFile('avatar')) {
            if(Storage::disk('public')->fileExists($user->avatar)
            && !mb_ereg_match('.*default.png$', $user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = 'users/' . now()->format('F') . now()->year;
            $path = $request->file('avatar')->store($path, ['disk' => 'public']);

            if($path) {
                $user->avatar = $path;
            }
        }

        $user->save();

        return back()->with('success', trans('Successfully Updated!'));
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $validator->validate();

        if(!Hash::check($request->input('password'), auth()->user()->password)) {
            return back()->withErrors(
                $validator->getMessageBag()->add('password', trans('auth.password')),
            );
        }

        /** @var User */
        $user = auth()->user();
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return back()->with('success', trans('Changed'));
    }
}
