<?php

namespace App\Http\Controllers;

use App\Test;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function createDatabase()
    {
        return view('createdatabase');
    }

    public function newDatabase(Request $request)
    {
        $new =  DB::table('newdatabase')->insert(
            ['db_name' =>$request->name]
        );

        DB::statement("CREATE DATABASE $request->name");
        DB::statement("CREATE TABLE $request->name.user (
            id bigint NOT NULL AUTO_INCREMENT,
            name varchar(255),
            email varchar(255),
            password varchar(255),
            remember_token varchar(255),
            created_at timestamp,
            updated_at timestamp,
            PRIMARY KEY (id)
        )");
        DB::statement("CREATE TABLE $request->name.test (
            id bigint NOT NULL AUTO_INCREMENT,
            name varchar(255),
            created_at timestamp,
            updated_at timestamp,
            PRIMARY KEY (id)
        )");

        if ($new) {
            return back()->with('success', 'database created successfully!');
        }
    }
    public function test()
    {
        return view('test');
    }
    public function storeTest(Request $request)
    {
        $new = new Test;

        $new->name = $request->name;

        $var =$new->save();
        if ($var) {
            return back()->with('success', 'data store successfully!');
        }
    }
    public function newUser()
    {
        $name = DB::table('newdatabase')->get();
        return view('newuser', compact('name'));
    }
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

        $db_name = $request['db_name'];
        $name = $request['name'];
        $email = $request['email'];
        $password = Hash::make($request['password']);
        $table=$db_name.'.user';
        // DB::statement("INSERT INTO $db_name.user (name, email, password) VALUES ($name,$email,$password)");
        DB::table($table)->insert(
            ['name' => $name,'email' => $email, 'password' => $password]
        );

        $new = User::create([
            'db_name' => $request['db_name'],
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $password
        ]);
        if ($new) {
            return back()->with('success', 'User Create Successfully!');
        }
    }
}
