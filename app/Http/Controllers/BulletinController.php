<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BulletinController extends Controller
{
    private $site_db;
    public function __construct()
    {
        $this->site_db = \DB::connection()->getDatabaseName();
    } // constructor

    public function editBulletin()
    {
        // change the connection to Consumables management
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', "Consumables management");
        \DB::purge(env("DB_CONNECTION"));

        \DB::transaction(function () {
            \DB::update();
        }, 5);
    } // editBulletin

    public function deleteBulletin()
    {
        // change the connection to Consumables management
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', "Consumables management");
        \DB::purge(env("DB_CONNECTION"));

        \DB::transaction(function () {
            \DB::delete('delete users where name = ?', ['John']);
        }, 5);
    } // deleteBulletin

    public function addBulletin()
    {
        // change the connection to Consumables management
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', "Consumables management");
        \DB::purge(env("DB_CONNECTION"));

        \DB::transaction(function () {
            \DB::insert();
        }, 5);
    } // addBulletin
}
