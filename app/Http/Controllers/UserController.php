<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $loggedId = intval(Auth::id());
        $users = User::paginate(10);
        return view('users.index', [
            'users' => $users,
            'loggedId' => $loggedId
        ]);
    }

    public function profile()
    {
       /* $loggedId = intval(Auth::id());
        $users = User::paginate(10);
        return view('users.profile', [
            'users' => $users,
            'loggedId' => $loggedId
        ]);*/

        return view('users.profile');
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->only([
                'name', 'email', 'password', 'password_confirmation'
            ]),
            [
                'name' => 'required|string|max:128',
                'email' => 'required|string|email|max:128|unique:users',
                'password' => 'required|string|min:4|confirmed',

            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->route('users.create')
                ->withErrors($validator)
                ->withInput();
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = password_hash($request->password, PASSWORD_DEFAULT);
        $user->save();

        return redirect()->route('users.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        return redirect()->route('users.edit');
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index');
        }

        $data = $request->only([
            'name', 'email', 'password', 'password_confirmation'
        ]);

        $validator = Validator::make($data, [
            'name' => 'required|string|max:128',
            'email' => 'required|string|email|max:128',
        ]);

        if ($user->email != $data['email']) {
            $hasEmail = User::where('email', $data['email'])->get();
            if (count($hasEmail) === 0) {
                $user->email = $data['email'];
            } else {
                $validator->errors()->add('email', __('validation.unique', [
                    'attribute' => 'email'
                ]));
            }
        }

        if (!empty($data['password'])) {
            if (strlen($data['password']) >= 4) {

                if ($data['password'] === $data['password_confirmation']) {
                    $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
                } else {
                    $validator->errors()->add('password', __('validation.confirmed', [
                        'attribute' => 'password',
                    ]));
                }
            } else {
                $validator->errors()->add('password', __('validation.min.string', [
                    'attribute' => 'password',
                    'min' => 4
                ]));
            }
        }

        if (count($validator->errors()) > 0 || $validator->fails()) {
            return redirect()
                ->route('users.edit', [
                    'user' => $id
                ])
                ->withErrors($validator);
        }

        $user->name = $data['name'];

        $user->save();

        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        $loggedId = intval(Auth::id());

        if ($loggedId !== intval($id)) {
            $user = User::find($id);
            $user->delete();
        }

        return redirect()->route('users.index');
    }
}
