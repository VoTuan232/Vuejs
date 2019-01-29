<?php
namespace App\Http\Controllers\api\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index() {
    	return User::with('roles')->paginate(5);
    }

    public function profile()
    {
        return auth('api')->user();
    }

    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6'
        ]);

        $currentPhoto = $user->avatar;

        
        if($request->avatar != $currentPhoto) {
            $name = time().'.' . explode('/', explode(':', substr($request->avatar, 0, strpos($request->avatar, ';')))[1])[1];
            \Image::make($request->avatar)->save(public_path('images/profile/').$name);
            $request->merge(['avatar' => $name]);
            $userPhoto = public_path('images/profile/').$currentPhoto;

            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }
        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request['password'])]);
        }
        $user->update($request->all());

        return ['message' => $request->all()];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'password' => Hash::make($request->password),
        ]);

        if(!is_null($request->roles)) {
            $data_sync = [];
            foreach ($request->roles as $key => $value) {
                array_push($data_sync, $value['id']);
            }
                $user->roles()->sync($data_sync);
        }

        return $user;
    }

    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['sometimes', 'min:6'],
        ]);

        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }
        DB::beginTransaction();
        try {
            $user->update($request->all());

            if(!is_null($request->roles)) {
                $data_sync = [];
                foreach ($request->roles as $key => $value) {
                    array_push($data_sync, $value['id']);
                }
                $user->roles()->sync($data_sync);
            }
            DB::commit();
        }
        catch(\Exception $e) {
            DB::rollback();
        }
    }

    public function destroy(User $user) {
        $user->delete();
    }
}
