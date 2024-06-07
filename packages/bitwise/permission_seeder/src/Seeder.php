<?php

namespace Bitwise\PermissionSeeder;

use App\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use ReflectionClass;

class Seeder
{
    protected $permissions = [];
    protected $classes = [];

    public function __construct()
    {
        $this->initPermissions();
    }

    protected function initPermissions()
    {
        $this->classes = $this->getModelNames();
    }

    public function clearTable(){
        $tableName = config('permission_seeder.permission_table','permissions');
        DB::table($tableName)->delete();
        if(env('DB_CONNECTION') == 'sqlsrv'){
            DB::unprepared("DBCC CHECKIDENT ($tableName, RESEED, 0) ");
        }elseif(env('DB_CONNECTION') == 'pgsql'){
            DB::unprepared("TRUNCATE ONLY {$tableName} RESTART IDENTITY CASCADE;");
        }else{
            DB::unprepared("ALTER TABLE {$tableName} AUTO_INCREMENT = 1; ");
        }
        return $this;
    }

    protected function createPermissions()
    {
        $this->classes->each(function ($class) {
            $instance = $class->newInstance();
            if ($class->hasMethod('getPermissionDisplayName')) {
                $modelName = $instance->getPermissionDisplayName();
                $className = $class->getShortName();

                collect($instance->getPermissionActions())->each(function ($action) use ($class, $className, $modelName) {
                    $this->permissions[] =  [
                        'name' => $className . '-' . $action,
                        'display_name' => $action . ' ' . $modelName,
                        'description' => $modelName//$class->getName()
                    ];
                });
            }
        });
    }
    public function save(){
        $this->createPermissions();
        $permissions = collect($this->permissions);
        $permissionModel = config('permission_seeder.permission_model','App\Permission');
        $permissions->each(function($item)use($permissionModel){
            $permissionModel::firstOrCreate($item);
        });
    }


    protected function getModelNames()
    {
        $modelClasses = $this->loadModelClasses();
        return  collect([])->merge($modelClasses);
    }

    protected function loadModelClasses()
    {
        $classes = collect([]);

        $path = app_path();
            $classes = collect($this->getDirContents($path))
            ->map(function ($item) use ($path) {
                return $this->cleanFileName($path . "/", $item);
            })
            ->map(function ($item) {
                return $this->cleanFileName(".php", $item);
            })
            ->map(function ($item) {
                if((PHP_OS) == "WINNT"){
                    $className = substr($item,strpos($item,"app"));
                    $className = $this->cleanFileName("app\\", $className,'');
                    $className = "App\\" . $this->cleanFileName("/", $className, "\\");
                    return $className;
                }else{
                    return "App\\" .$this->cleanFileName("/", $item, "\\");

                }
            })
            ->map(function ($item) {
                    return new ReflectionClass($item);
            })
            ->filter(function ($item) {
                return $item->implementsInterface(PermissionSeederContract::class);
            });
        return $classes;
    }

    protected function getDirContents($dir, &$results = array())
    {
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = $path;
            } else if ($value != "." && $value != "..") {
                $this->getDirContents($path, $results);
                if (!is_dir($path)) {
                    $results[] = $path;
                }
            }
        }

        return $results;
    }

    protected function cleanFileName($search, $file, $replace = "")
    {
        return str_replace($search, $replace, $file);
    }
}
