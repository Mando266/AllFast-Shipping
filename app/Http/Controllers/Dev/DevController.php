<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Artisan;

class   DevController extends Controller
{

    private $devPass = 414141;

    public function __construct()
    {
//        dd(request()->all());
        if (request()->dev_pass != $this->devPass) {
            abort(401);
        }
        view()->composer('dev_tools/*', fn($v) => $v->with("devPass", request()->dev_pass));
    }

    public function index()
    {
        return view('dev_tools.index');
    }

    public function selectSqlForm()
    {
        return view('dev_tools.select-sql');
    }

    public function selectSqlPost()
    {
        $sql = request()->sql;
        return DB::select($sql);
//        $selectStatements = preg_match('/^\s*SELECT.*\bFROM\b/i', $sql);
//
//        if ($selectStatements) {
//        } else {
//            return 'BETHABEB EH FL DATABASEEEEEEEEEEEEEEE!!!!!!!!!!!!!!!!!!!!!!';
//        }
    }


    public function migrateTables()
    {
        Artisan::call('migrate');
        dd('migrations done!');
    }
}
