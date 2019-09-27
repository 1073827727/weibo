<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;

class UsersController extends Controller
{

    public function __construct(){
        //未登录无法访问下面的页面
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index','confirmEmail']
        ]);

        //只让未登陆访问的页面
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    public function index()
    {
        $users = User::paginate(5);
        return view('users.index', compact('users'));
    }

    public function create(){

        return view('users.create');

    }


    public function show(User $user)
    {
        $statuses = $user->statuses()->orderBy('created_at','desc')->paginate(10);
        //取出之前关联数据表一对多：一个用户关联多条微博，微博数据按时间倒数排序
        return view('users.show', compact('user','statuses'));
        //compact接收多个参数，将$user数据，$statuses数据传到视图上
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
       // Auth::login($user);
       // session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
       // return redirect()->route('users.show', [$user]);
        $this->sendEmailConfirmationTo($user);
        //激活邮箱的发送操作
        session()->flash('success','验证邮件已发送到你的注册邮箱上，请注意查收');
        return redirect('/');
    }

    public function edit(User $user){
        $this->authorize('update',$user);
        return view('users.edit' ,compact('user'));

    }

    public function update(User $user,Request $request){
        $this->authorize('upadte',$user);
        $this->validate($request,[
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] =bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success','个人资料更新成功');

        return redirect()->route('users.show',$user->id);

    }
    public function destroy(User $user,Request $request){
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success','成功删除');
        return back();

    }


    protected function sendEmailConfirmationTo($user){
        $view = 'emails.confirm';
        //包含邮件消息的视图名称。
        $data = compact('user');
        //传递给该视图的数据数组
        $from = '123@123.com';
        $name = '1';
        $to = $user->email;
        $subject = "感谢注册 Weibo 应用！请确认你的邮箱。";
        //接收邮件消息实例的闭包回调，我们可以在该回调中自定义邮件消息的发送者、接收者、邮件主题等信息
        mail::send($view , $data , function ($message) use ($from,$name,$to,$subject){
            $message->from($from,$name)->to($to)->subject($subject);
        });
    }


    public function confirmEmail($token){
        $user = User::where('activation_token',$token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success','恭喜你，激活成功');
        return redirect()->route('users.show',[$user]);

    }


    public function followings(User $user){
        //$users = $user->followings()->paginate(30);
        $users = $user->followings()->paginate(30);
        $title = $user->name.'关注的人';
        return view('users.show_follow', compact('users', 'title'));

    }


    public function followers(User $user){
        $users = $user->followers()->paginate(30);
        $title = $user->name."的粉丝";

        return view('users.show_follow', compact('users', 'title'));
    }









}
