<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CallController;
use App\Http\Controllers\OwarehouseController;
use App\Http\Controllers\ImportExcelController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\HomeController;
use App\Models\Login;
use App\Services\MailService; // for testing only

use Illuminate\Http\Request;
use App\Models;
use Illuminate\Database\Eloquent\Model;
use MeiliSearch\Client;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// if using Vue-Router to handle all the front end routes and redirects AKA "SPA, Single Page Application",
// Laravel should only have API routes, and only this one single web routes.
// Vue (front-end) gets all the data from API (Laravel, back-end)
// For authentication, use Laravel Sanctum ( laravel authentication system for SPAs )
//------------------------------------------------------------------------------------------------------------
// Route::get('/{any}', function () {
//     return view('layouts.app');
// })->where("any", ".*");
// --------------- the about code gets any url of our website and intended to pass it to Vue Router ----------

Route::get('/', function (Request $request) {
    if ($request->filled('S')) {
        // the redirected session is seperated from SSO session, so we need to login manaully
        $decrypted_site = \Crypt::decrypt(request()->D);
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $decrypted_site);
        \DB::purge(env("DB_CONNECTION"));
        $decrypted_id = \Crypt::decrypt(request()->S);
        $user = Login::where([
            'username' => $decrypted_id,
        ])->firstOr(function ($site, $id) use ($decrypted_site, $decrypted_id) {
            // returns the first result matching the query or, if no results are found, execute the given closure
            dd($site . "_" . $id);
        });

        // -------------------------------------------------------------------------
        // update the db_list for user that exist before the db_list function is added
        if (is_null($user->available_dblist) || count(explode("_", $user->available_dblist)) <= 1) {
            DB::table('login')
                ->where('username', '=', $user->username)
                ->update(['available_dblist' =>  str_replace(" Consumables management", "", $decrypted_site)]);
        } // if
        // -------------------------------------------------------------------------

        \Auth::login($user);
        $request->session()->regenerate();
        $usernameAuthed = \Auth::user()->username;
        $prior = \Auth::user()->priority;
        $avatarChoice = \Auth::user()->avatarChoice;
        Session::put('username', $usernameAuthed);
        Session::put('priority', $prior);
        Session::put('avatarChoice', $avatarChoice);
        Session::put('department', \Auth::user()->部門);
        session(['database' => $decrypted_site]);

        if (\Auth::user()->preferred_lang == null) {
            Session::put('locale', 'en');
            \App::setLocale('en');
        } else {
            Session::put('locale', \Auth::user()->preferred_lang);
            \App::setLocale(\Auth::user()->preferred_lang);
        } // else

        return view('welcome');
    } // if
    else if (strcmp(env('APP_ENV'), 'production') === 0) {
        // if not, redirect to MIS SSO page
        $userKey = urlencode(base64_encode(env('SSO_Key')));
        $sysType = urlencode(base64_encode(env('SSO_sysType')));
        $ReDirToUrl = env('APP_URL') . "/member/sso";
        $FailTo = env('APP_URL') . "/569";
        return redirect('https://ws.ecomp.pegatroncorp.com/SSO?ReDirTo=' . $ReDirToUrl . '&FailTo=' . $FailTo . '&sysType=' . $sysType . '&userKey=' . $userKey);
    } // else if
    else {
        return view('welcome');
    } // else
})->name('welcome')->withoutMiddleware('auth');

Route::get('/switchSite/{site_name}', [App\Http\Controllers\Auth\LoginController::class, 'switchSite'])->name('switchSite');

Route::get('/hello_world', function () {
    return view('hello_world');
})->name('hello_world')->withoutMiddleware('auth');

Route::get('/testwebsql', function () {
    return view('websql');
})->withoutMiddleware('auth');

Route::get('/test_gitlab', function () {
    $data = '{
        "zen": "Non-blocking is better than blocking.",
        "hook_id": 459163232,
        "hook": {
          "type": "Repository",
          "id": 459163232,
          "name": "web",
          "active": true,
          "events": [
            "push"
          ],
          "config": {
            "content_type": "form",
            "insecure_ssl": "0",
            "url": "http://172.18.220.52/api/v4/projects/3/ref/main/trigger/pipeline?token=glptt-29d17829002b1c30a1df8a676d781be593d8f9af"
          },
          "updated_at": "2024-02-06T04:04:28Z",
          "created_at": "2024-02-06T04:04:28Z",
          "url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/hooks/459163232",
          "test_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/hooks/459163232/test",
          "ping_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/hooks/459163232/pings",
          "deliveries_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/hooks/459163232/deliveries",
          "last_response": {
            "code": null,
            "status": "unused",
            "message": null
          }
        },
        "repository": {
          "id": 742328164,
          "node_id": "R_kgDOLD8HZA",
          "name": "Component-Management-Website",
          "full_name": "VinTheMan/Component-Management-Website",
          "private": true,
          "owner": {
            "login": "VinTheMan",
            "id": 26359435,
            "node_id": "MDQ6VXNlcjI2MzU5NDM1",
            "avatar_url": "https://avatars.githubusercontent.com/u/26359435?v=4",
            "gravatar_id": "",
            "url": "https://api.github.com/users/VinTheMan",
            "html_url": "https://github.com/VinTheMan",
            "followers_url": "https://api.github.com/users/VinTheMan/followers",
            "following_url": "https://api.github.com/users/VinTheMan/following{/other_user}",
            "gists_url": "https://api.github.com/users/VinTheMan/gists{/gist_id}",
            "starred_url": "https://api.github.com/users/VinTheMan/starred{/owner}{/repo}",
            "subscriptions_url": "https://api.github.com/users/VinTheMan/subscriptions",
            "organizations_url": "https://api.github.com/users/VinTheMan/orgs",
            "repos_url": "https://api.github.com/users/VinTheMan/repos",
            "events_url": "https://api.github.com/users/VinTheMan/events{/privacy}",
            "received_events_url": "https://api.github.com/users/VinTheMan/received_events",
            "type": "User",
            "site_admin": false
          },
          "html_url": "https://github.com/VinTheMan/Component-Management-Website",
          "description": "For PEGA BG6 工程支援課",
          "fork": false,
          "url": "https://api.github.com/repos/VinTheMan/Component-Management-Website",
          "forks_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/forks",
          "keys_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/keys{/key_id}",
          "collaborators_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/collaborators{/collaborator}",
          "teams_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/teams",
          "hooks_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/hooks",
          "issue_events_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/issues/events{/number}",
          "events_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/events",
          "assignees_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/assignees{/user}",
          "branches_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/branches{/branch}",
          "tags_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/tags",
          "blobs_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/git/blobs{/sha}",
          "git_tags_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/git/tags{/sha}",
          "git_refs_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/git/refs{/sha}",
          "trees_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/git/trees{/sha}",
          "statuses_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/statuses/{sha}",
          "languages_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/languages",
          "stargazers_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/stargazers",
          "contributors_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/contributors",
          "subscribers_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/subscribers",
          "subscription_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/subscription",
          "commits_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/commits{/sha}",
          "git_commits_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/git/commits{/sha}",
          "comments_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/comments{/number}",
          "issue_comment_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/issues/comments{/number}",
          "contents_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/contents/{+path}",
          "compare_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/compare/{base}...{head}",
          "merges_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/merges",
          "archive_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/{archive_format}{/ref}",
          "downloads_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/downloads",
          "issues_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/issues{/number}",
          "pulls_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/pulls{/number}",
          "milestones_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/milestones{/number}",
          "notifications_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/notifications{?since,all,participating}",
          "labels_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/labels{/name}",
          "releases_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/releases{/id}",
          "deployments_url": "https://api.github.com/repos/VinTheMan/Component-Management-Website/deployments",
          "created_at": "2024-01-12T08:27:11Z",
          "updated_at": "2024-02-05T10:32:45Z",
          "pushed_at": "2024-02-05T10:48:03Z",
          "git_url": "git://github.com/VinTheMan/Component-Management-Website.git",
          "ssh_url": "git@github.com:VinTheMan/Component-Management-Website.git",
          "clone_url": "https://github.com/VinTheMan/Component-Management-Website.git",
          "svn_url": "https://github.com/VinTheMan/Component-Management-Website",
          "homepage": null,
          "size": 7,
          "stargazers_count": 0,
          "watchers_count": 0,
          "language": "Shell",
          "has_issues": true,
          "has_projects": true,
          "has_downloads": true,
          "has_wiki": false,
          "has_pages": false,
          "has_discussions": false,
          "forks_count": 0,
          "mirror_url": null,
          "archived": false,
          "disabled": false,
          "open_issues_count": 0,
          "license": {
            "key": "mit",
            "name": "MIT License",
            "spdx_id": "MIT",
            "url": "https://api.github.com/licenses/mit",
            "node_id": "MDc6TGljZW5zZTEz"
          },
          "allow_forking": true,
          "is_template": false,
          "web_commit_signoff_required": false,
          "topics": [
      
          ],
          "visibility": "private",
          "forks": 0,
          "open_issues": 0,
          "watchers": 0,
          "default_branch": "master"
        },
        "sender": {
          "login": "VinTheMan",
          "id": 26359435,
          "node_id": "MDQ6VXNlcjI2MzU5NDM1",
          "avatar_url": "https://avatars.githubusercontent.com/u/26359435?v=4",
          "gravatar_id": "",
          "url": "https://api.github.com/users/VinTheMan",
          "html_url": "https://github.com/VinTheMan",
          "followers_url": "https://api.github.com/users/VinTheMan/followers",
          "following_url": "https://api.github.com/users/VinTheMan/following{/other_user}",
          "gists_url": "https://api.github.com/users/VinTheMan/gists{/gist_id}",
          "starred_url": "https://api.github.com/users/VinTheMan/starred{/owner}{/repo}",
          "subscriptions_url": "https://api.github.com/users/VinTheMan/subscriptions",
          "organizations_url": "https://api.github.com/users/VinTheMan/orgs",
          "repos_url": "https://api.github.com/users/VinTheMan/repos",
          "events_url": "https://api.github.com/users/VinTheMan/events{/privacy}",
          "received_events_url": "https://api.github.com/users/VinTheMan/received_events",
          "type": "User",
          "site_admin": false
        }
      }';
    $response =
        Http::withBody($data, 'application/x-www-form-urlencoded')
        ->post('http://172.18.220.52/api/v4/projects/3/ref/main/trigger/pipeline?token=glptt-29d17829002b1c30a1df8a676d781be593d8f9af');

    dd($response);
})->withoutMiddleware('auth');

Route::get('/template_test', function () {
    return view('templateChart');
})->name('charts');

Route::get('/dashboard', function () {
    return view('testTemplate');
})->name('dashboard');

Route::get('/vuetest', function () {
    // dd( \Auth::user()->username ); // test
    // return view("layouts.app");
    $databases = config('database_list.databases');

    //MailService::day(); // test
})->withoutMiddleware('auth');

Route::get('/phpinfo', function () {
    phpinfo();
})->withoutMiddleware('auth');

Route::get('/import_excel', [ImportExcelController::class, 'index']);

Route::get('/import_excel/import', [ImportExcelController::class, 'index']);
Route::post('/import_excel/import', [ImportExcelController::class, 'import']);

// language changing routes
Route::get('/lang/{type}', function (Request $request, $type) {
    $session = $request->getSession();
    $session->put('locale', $type);
    \App::setLocale($type);

    if (Auth::check()) {
        // The user is logged in
        \DB::table('login')->where('username', Auth::user()->username)
            ->update(['preferred_lang' => $type]);
    } // if

    return Redirect::back();
})->withoutMiddleware('auth');

Route::get('/home', function () {
    return view("home");
})->name('home');

Route::get('/editNews', function () {
    $user = \Auth::user();
    $response = \Gate::inspect('canPostToOtherSite', $user); // call to EditNewsPolicy
    $database_list = config('database_list.databases');
    $database_names = array();
    $selfDB_name = array("Consumables management", str_replace(" Consumables management", "", DB::connection()->getDatabaseName()));
    $selfDB_list = array("Consumables management", DB::connection()->getDatabaseName());
    foreach ($database_list as $value) {
        $temp = str_replace(" Consumables management", "", $value);
        array_push($database_names, $temp);
    } // for each

    unset($value); // unset the var created in the foreach loop

    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', "Consumables management");
    \DB::purge(env("DB_CONNECTION"));

    $cat_list = [];
    $cat_list = DB::table('bulletins')
        ->select('category')
        ->distinct()
        ->get();

    // get the connection back to original
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $selfDB_list[1]);
    \DB::purge(env("DB_CONNECTION"));

    if ($response->allowed()) {
        // The action is authorized...
        array_push($database_list, "All");
        array_push($database_names, __('templateWords.all'));
        return view('editNewsBoard')->with(['cat_list' => $cat_list, 'database_list' => $database_list, 'database_names' => $database_names]);
    } else {
        return view('editNewsBoard')->with(['cat_list' => $cat_list, 'database_list' => $selfDB_list, 'database_names' => $selfDB_name]);
    } // if else

})->name('editNews');

Route::get('/help', function () {
    return view("help");
})->name('help');

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});

Route::post('/getCurrentDB', function () {
    return DB::connection()->getDatabaseName();
});

Route::post('/getCurrentUsername', function (Request $request) {
    return $request->user()->username;
});

Route::post('/getCurrentUser', function (Request $request) {
    return $request->user();
});

Route::get('/storage/barcodeImg/{filename}', function ($filename) {
    // due to multiple project nginx settings, the php artisan storage:link won't work
    // so we get the storage path ourselves
    $path = storage_path('app/public/barcodeImg/' . $filename);
    $file = File::get($path);
    $type = File::mimeType($path);

    if (!File::exists($path)) {
        abort(404);
    } // if

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});

Route::post('/navbar_quick_search', [HomeController::class, 'insiteSearch']);

Route::get('/meiliSearchCleanUp', function () { // use this route when needed
    $testClient = new Client(env('MEILISEARCH_HOST'));
    // dd($testClient->getAllIndexes());
    $testClient->deleteAllIndexes(); // u might want to clean up meilisearch db first
    dd($testClient->getTasks());
});

// When OA Login Failed on MIS side. Use this route as Failto
Route::get('/569', function () {
    return view("errors.569");
})->name('error569')->withoutMiddleware('auth');
