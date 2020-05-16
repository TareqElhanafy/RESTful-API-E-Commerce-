<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\UserCreated;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['client.credentials'])->only(['store','resend']);
        $this->middleware(['auth:api'])->except(['verify','store','resend']);
        $this->middleware('manage-account')->only(['show','update']);
        $this->middleware('can:view,user')->only(['index','show']);
        $this->middleware('can:create,user')->only(['store']);
        $this->middleware('can:delete,user')->only(['destroy']);



    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users=User::all();
        return $this->showAll($users);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules=[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8|confirmed'
        ];

        $this->validate($request,$rules);
        $data=$request->all();
        $data['password']=bcrypt($request->password);
        $data['verified']=User::UNVERIFIED_USER;
        $data['verification_token']=User::generateVerificationToken();
        $data['role']=User::REGULAR_USER;
        $user=User::create($data);
        return $this->showOne($user);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
         return $this->showOne($user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $UpdRules=[
            'email'=>'email|unique:users',
            'password'=>'min:8|confirmed',

        ];
        $this->validate($request,$UpdRules);

        if ($request->has('name')) {
            $user->name=$request->name;
        }
        
        if ($request->has('password')) {
            $user->password=bcrypt($request->password);

        }

        if ($request->has('email')) {
            $user->verified=User::UNVERIFIED_USER;
            $user->verification_token=User::generateVerificationToken();
            $user->email=$request->email;

        }

        if ($request->has('role')) {
            if (!$user->isVerified()) {
                return $this->errorResponse(['error'=>'only verified user can do this'],409);
            }
            $user->role=$request->role;
        }

        // if ($user->isClean()) {
        //     return $this->errorResponse(['error'=>'you should enter the required data'],422);

        // }

        $user->save();
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->showOne($user);
    }



    public function verify($token){

        $user=User::where('verification_token',$token)->firstOrFail();

        $user->verified=User::VERIFIED_USER;
        $user->verification_token=null;
        $user->save();
        return $this->showMessage(['message'=>'user is successfully verified'],200);
    }

    public function resend(User $user){
        if (!$user->isVerified()) {
           Mail::to($user->email)->send(new UserCreated($user));
           return $this->showMessage(['message'=>'please check your mail'],409);
        }
        return $this->showMessage(['message'=>'this user is already verified'],409);
    }
}
