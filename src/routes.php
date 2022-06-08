<?php

namespace PHPMaker2021\silpa;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

// Handle Routes
return function (App $app) {
    // levels
    $app->any('/levelslist', LevelsController::class . ':list')->add(PermissionMiddleware::class)->setName('levelslist-levels-list'); // list
    $app->any('/levelsadd', LevelsController::class . ':add')->add(PermissionMiddleware::class)->setName('levelsadd-levels-add'); // add
    $app->group(
        '/levels',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '', LevelsController::class . ':list')->add(PermissionMiddleware::class)->setName('levels/list-levels-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '', LevelsController::class . ':add')->add(PermissionMiddleware::class)->setName('levels/add-levels-add-2'); // add
        }
    );

    // permissions2
    $app->any('/permissions2list', Permissions2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('permissions2list-permissions2-list'); // list
    $app->group(
        '/permissions2',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '', Permissions2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('permissions2/list-permissions2-list-2'); // list
        }
    );

    // users
    $app->any('/userslist[/{idd_user}]', UsersController::class . ':list')->add(PermissionMiddleware::class)->setName('userslist-users-list'); // list
    $app->any('/usersadd[/{idd_user}]', UsersController::class . ':add')->add(PermissionMiddleware::class)->setName('usersadd-users-add'); // add
    $app->any('/usersview[/{idd_user}]', UsersController::class . ':view')->add(PermissionMiddleware::class)->setName('usersview-users-view'); // view
    $app->any('/usersedit[/{idd_user}]', UsersController::class . ':edit')->add(PermissionMiddleware::class)->setName('usersedit-users-edit'); // edit
    $app->any('/usersdelete[/{idd_user}]', UsersController::class . ':delete')->add(PermissionMiddleware::class)->setName('usersdelete-users-delete'); // delete
    $app->group(
        '/users',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{idd_user}]', UsersController::class . ':list')->add(PermissionMiddleware::class)->setName('users/list-users-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{idd_user}]', UsersController::class . ':add')->add(PermissionMiddleware::class)->setName('users/add-users-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{idd_user}]', UsersController::class . ':view')->add(PermissionMiddleware::class)->setName('users/view-users-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{idd_user}]', UsersController::class . ':edit')->add(PermissionMiddleware::class)->setName('users/edit-users-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{idd_user}]', UsersController::class . ':delete')->add(PermissionMiddleware::class)->setName('users/delete-users-delete-2'); // delete
        }
    );

    // evaluators
    $app->any('/evaluatorslist[/{idd_evaluator}]', EvaluatorsController::class . ':list')->add(PermissionMiddleware::class)->setName('evaluatorslist-evaluators-list'); // list
    $app->any('/evaluatorsadd[/{idd_evaluator}]', EvaluatorsController::class . ':add')->add(PermissionMiddleware::class)->setName('evaluatorsadd-evaluators-add'); // add
    $app->group(
        '/evaluators',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{idd_evaluator}]', EvaluatorsController::class . ':list')->add(PermissionMiddleware::class)->setName('evaluators/list-evaluators-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{idd_evaluator}]', EvaluatorsController::class . ':add')->add(PermissionMiddleware::class)->setName('evaluators/add-evaluators-add-2'); // add
        }
    );

    // satkers
    $app->any('/satkerslist[/{idd_satker}]', SatkersController::class . ':list')->add(PermissionMiddleware::class)->setName('satkerslist-satkers-list'); // list
    $app->any('/satkersadd[/{idd_satker}]', SatkersController::class . ':add')->add(PermissionMiddleware::class)->setName('satkersadd-satkers-add'); // add
    $app->any('/satkersview[/{idd_satker}]', SatkersController::class . ':view')->add(PermissionMiddleware::class)->setName('satkersview-satkers-view'); // view
    $app->any('/satkersedit[/{idd_satker}]', SatkersController::class . ':edit')->add(PermissionMiddleware::class)->setName('satkersedit-satkers-edit'); // edit
    $app->any('/satkersdelete[/{idd_satker}]', SatkersController::class . ':delete')->add(PermissionMiddleware::class)->setName('satkersdelete-satkers-delete'); // delete
    $app->group(
        '/satkers',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{idd_satker}]', SatkersController::class . ':list')->add(PermissionMiddleware::class)->setName('satkers/list-satkers-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{idd_satker}]', SatkersController::class . ':add')->add(PermissionMiddleware::class)->setName('satkers/add-satkers-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{idd_satker}]', SatkersController::class . ':view')->add(PermissionMiddleware::class)->setName('satkers/view-satkers-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{idd_satker}]', SatkersController::class . ':edit')->add(PermissionMiddleware::class)->setName('satkers/edit-satkers-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{idd_satker}]', SatkersController::class . ':delete')->add(PermissionMiddleware::class)->setName('satkers/delete-satkers-delete-2'); // delete
        }
    );

    // tahapan
    $app->any('/tahapanlist[/{idd_tahapan}]', TahapanController::class . ':list')->add(PermissionMiddleware::class)->setName('tahapanlist-tahapan-list'); // list
    $app->any('/tahapanadd[/{idd_tahapan}]', TahapanController::class . ':add')->add(PermissionMiddleware::class)->setName('tahapanadd-tahapan-add'); // add
    $app->group(
        '/tahapan',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{idd_tahapan}]', TahapanController::class . ':list')->add(PermissionMiddleware::class)->setName('tahapan/list-tahapan-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{idd_tahapan}]', TahapanController::class . ':add')->add(PermissionMiddleware::class)->setName('tahapan/add-tahapan-add-2'); // add
        }
    );

    // wilayah
    $app->any('/wilayahlist[/{idd_wilayah}]', WilayahController::class . ':list')->add(PermissionMiddleware::class)->setName('wilayahlist-wilayah-list'); // list
    $app->any('/wilayahadd[/{idd_wilayah}]', WilayahController::class . ':add')->add(PermissionMiddleware::class)->setName('wilayahadd-wilayah-add'); // add
    $app->any('/wilayahaddopt', WilayahController::class . ':addopt')->add(PermissionMiddleware::class)->setName('wilayahaddopt-wilayah-addopt'); // addopt
    $app->group(
        '/wilayah',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{idd_wilayah}]', WilayahController::class . ':list')->add(PermissionMiddleware::class)->setName('wilayah/list-wilayah-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{idd_wilayah}]', WilayahController::class . ':add')->add(PermissionMiddleware::class)->setName('wilayah/add-wilayah-add-2'); // add
            $group->any('/' . Config("ADDOPT_ACTION") . '', WilayahController::class . ':addopt')->add(PermissionMiddleware::class)->setName('wilayah/addopt-wilayah-addopt-2'); // addopt
        }
    );

    // evaluasi
    $app->any('/evaluasilist[/{idd_evaluasi}]', EvaluasiController::class . ':list')->add(PermissionMiddleware::class)->setName('evaluasilist-evaluasi-list'); // list
    $app->any('/evaluasiadd[/{idd_evaluasi}]', EvaluasiController::class . ':add')->add(PermissionMiddleware::class)->setName('evaluasiadd-evaluasi-add'); // add
    $app->any('/evaluasiview[/{idd_evaluasi}]', EvaluasiController::class . ':view')->add(PermissionMiddleware::class)->setName('evaluasiview-evaluasi-view'); // view
    $app->any('/evaluasiedit[/{idd_evaluasi}]', EvaluasiController::class . ':edit')->add(PermissionMiddleware::class)->setName('evaluasiedit-evaluasi-edit'); // edit
    $app->any('/evaluasidelete[/{idd_evaluasi}]', EvaluasiController::class . ':delete')->add(PermissionMiddleware::class)->setName('evaluasidelete-evaluasi-delete'); // delete
    $app->group(
        '/evaluasi',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{idd_evaluasi}]', EvaluasiController::class . ':list')->add(PermissionMiddleware::class)->setName('evaluasi/list-evaluasi-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{idd_evaluasi}]', EvaluasiController::class . ':add')->add(PermissionMiddleware::class)->setName('evaluasi/add-evaluasi-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{idd_evaluasi}]', EvaluasiController::class . ':view')->add(PermissionMiddleware::class)->setName('evaluasi/view-evaluasi-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{idd_evaluasi}]', EvaluasiController::class . ':edit')->add(PermissionMiddleware::class)->setName('evaluasi/edit-evaluasi-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{idd_evaluasi}]', EvaluasiController::class . ':delete')->add(PermissionMiddleware::class)->setName('evaluasi/delete-evaluasi-delete-2'); // delete
        }
    );

    // apbk
    $app->any('/apbklist[/{idd_evaluasi}]', ApbkController::class . ':list')->add(PermissionMiddleware::class)->setName('apbklist-apbk-list'); // list
    $app->any('/apbkadd[/{idd_evaluasi}]', ApbkController::class . ':add')->add(PermissionMiddleware::class)->setName('apbkadd-apbk-add'); // add
    $app->any('/apbkview[/{idd_evaluasi}]', ApbkController::class . ':view')->add(PermissionMiddleware::class)->setName('apbkview-apbk-view'); // view
    $app->any('/apbkedit[/{idd_evaluasi}]', ApbkController::class . ':edit')->add(PermissionMiddleware::class)->setName('apbkedit-apbk-edit'); // edit
    $app->any('/apbkdelete[/{idd_evaluasi}]', ApbkController::class . ':delete')->add(PermissionMiddleware::class)->setName('apbkdelete-apbk-delete'); // delete
    $app->group(
        '/apbk',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{idd_evaluasi}]', ApbkController::class . ':list')->add(PermissionMiddleware::class)->setName('apbk/list-apbk-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{idd_evaluasi}]', ApbkController::class . ':add')->add(PermissionMiddleware::class)->setName('apbk/add-apbk-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{idd_evaluasi}]', ApbkController::class . ':view')->add(PermissionMiddleware::class)->setName('apbk/view-apbk-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{idd_evaluasi}]', ApbkController::class . ':edit')->add(PermissionMiddleware::class)->setName('apbk/edit-apbk-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{idd_evaluasi}]', ApbkController::class . ':delete')->add(PermissionMiddleware::class)->setName('apbk/delete-apbk-delete-2'); // delete
        }
    );

    // apbkp
    $app->any('/apbkplist[/{idd_evaluasi}]', ApbkpController::class . ':list')->add(PermissionMiddleware::class)->setName('apbkplist-apbkp-list'); // list
    $app->any('/apbkpadd[/{idd_evaluasi}]', ApbkpController::class . ':add')->add(PermissionMiddleware::class)->setName('apbkpadd-apbkp-add'); // add
    $app->any('/apbkpview[/{idd_evaluasi}]', ApbkpController::class . ':view')->add(PermissionMiddleware::class)->setName('apbkpview-apbkp-view'); // view
    $app->any('/apbkpedit[/{idd_evaluasi}]', ApbkpController::class . ':edit')->add(PermissionMiddleware::class)->setName('apbkpedit-apbkp-edit'); // edit
    $app->any('/apbkpdelete[/{idd_evaluasi}]', ApbkpController::class . ':delete')->add(PermissionMiddleware::class)->setName('apbkpdelete-apbkp-delete'); // delete
    $app->group(
        '/apbkp',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{idd_evaluasi}]', ApbkpController::class . ':list')->add(PermissionMiddleware::class)->setName('apbkp/list-apbkp-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{idd_evaluasi}]', ApbkpController::class . ':add')->add(PermissionMiddleware::class)->setName('apbkp/add-apbkp-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{idd_evaluasi}]', ApbkpController::class . ':view')->add(PermissionMiddleware::class)->setName('apbkp/view-apbkp-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{idd_evaluasi}]', ApbkpController::class . ':edit')->add(PermissionMiddleware::class)->setName('apbkp/edit-apbkp-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{idd_evaluasi}]', ApbkpController::class . ':delete')->add(PermissionMiddleware::class)->setName('apbkp/delete-apbkp-delete-2'); // delete
        }
    );

    // pertanggungjawaban
    $app->any('/pertanggungjawabanlist[/{idd_evaluasi}]', PertanggungjawabanController::class . ':list')->add(PermissionMiddleware::class)->setName('pertanggungjawabanlist-pertanggungjawaban-list'); // list
    $app->any('/pertanggungjawabanadd[/{idd_evaluasi}]', PertanggungjawabanController::class . ':add')->add(PermissionMiddleware::class)->setName('pertanggungjawabanadd-pertanggungjawaban-add'); // add
    $app->any('/pertanggungjawabanview[/{idd_evaluasi}]', PertanggungjawabanController::class . ':view')->add(PermissionMiddleware::class)->setName('pertanggungjawabanview-pertanggungjawaban-view'); // view
    $app->any('/pertanggungjawabanedit[/{idd_evaluasi}]', PertanggungjawabanController::class . ':edit')->add(PermissionMiddleware::class)->setName('pertanggungjawabanedit-pertanggungjawaban-edit'); // edit
    $app->any('/pertanggungjawabandelete[/{idd_evaluasi}]', PertanggungjawabanController::class . ':delete')->add(PermissionMiddleware::class)->setName('pertanggungjawabandelete-pertanggungjawaban-delete'); // delete
    $app->group(
        '/pertanggungjawaban',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{idd_evaluasi}]', PertanggungjawabanController::class . ':list')->add(PermissionMiddleware::class)->setName('pertanggungjawaban/list-pertanggungjawaban-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{idd_evaluasi}]', PertanggungjawabanController::class . ':add')->add(PermissionMiddleware::class)->setName('pertanggungjawaban/add-pertanggungjawaban-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{idd_evaluasi}]', PertanggungjawabanController::class . ':view')->add(PermissionMiddleware::class)->setName('pertanggungjawaban/view-pertanggungjawaban-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{idd_evaluasi}]', PertanggungjawabanController::class . ':edit')->add(PermissionMiddleware::class)->setName('pertanggungjawaban/edit-pertanggungjawaban-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{idd_evaluasi}]', PertanggungjawabanController::class . ':delete')->add(PermissionMiddleware::class)->setName('pertanggungjawaban/delete-pertanggungjawaban-delete-2'); // delete
        }
    );

    // rapbk
    $app->any('/rapbklist[/{idd_evaluasi}]', RapbkController::class . ':list')->add(PermissionMiddleware::class)->setName('rapbklist-rapbk-list'); // list
    $app->any('/rapbkadd[/{idd_evaluasi}]', RapbkController::class . ':add')->add(PermissionMiddleware::class)->setName('rapbkadd-rapbk-add'); // add
    $app->any('/rapbkview[/{idd_evaluasi}]', RapbkController::class . ':view')->add(PermissionMiddleware::class)->setName('rapbkview-rapbk-view'); // view
    $app->any('/rapbkedit[/{idd_evaluasi}]', RapbkController::class . ':edit')->add(PermissionMiddleware::class)->setName('rapbkedit-rapbk-edit'); // edit
    $app->any('/rapbkdelete[/{idd_evaluasi}]', RapbkController::class . ':delete')->add(PermissionMiddleware::class)->setName('rapbkdelete-rapbk-delete'); // delete
    $app->group(
        '/rapbk',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{idd_evaluasi}]', RapbkController::class . ':list')->add(PermissionMiddleware::class)->setName('rapbk/list-rapbk-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{idd_evaluasi}]', RapbkController::class . ':add')->add(PermissionMiddleware::class)->setName('rapbk/add-rapbk-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{idd_evaluasi}]', RapbkController::class . ':view')->add(PermissionMiddleware::class)->setName('rapbk/view-rapbk-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{idd_evaluasi}]', RapbkController::class . ':edit')->add(PermissionMiddleware::class)->setName('rapbk/edit-rapbk-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{idd_evaluasi}]', RapbkController::class . ':delete')->add(PermissionMiddleware::class)->setName('rapbk/delete-rapbk-delete-2'); // delete
        }
    );

    // tahun
    $app->any('/tahunlist', TahunController::class . ':list')->add(PermissionMiddleware::class)->setName('tahunlist-tahun-list'); // list
    $app->any('/tahunadd', TahunController::class . ':add')->add(PermissionMiddleware::class)->setName('tahunadd-tahun-add'); // add
    $app->group(
        '/tahun',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '', TahunController::class . ':list')->add(PermissionMiddleware::class)->setName('tahun/list-tahun-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '', TahunController::class . ':add')->add(PermissionMiddleware::class)->setName('tahun/add-tahun-add-2'); // add
        }
    );

    // error
    $app->any('/error', OthersController::class . ':error')->add(PermissionMiddleware::class)->setName('error');

    // personal_data
    $app->any('/personaldata', OthersController::class . ':personaldata')->add(PermissionMiddleware::class)->setName('personaldata');

    // login
    $app->any('/login', OthersController::class . ':login')->add(PermissionMiddleware::class)->setName('login');

    // change_password
    $app->any('/changepassword', OthersController::class . ':changepassword')->add(PermissionMiddleware::class)->setName('changepassword');

    // userpriv
    $app->any('/userpriv', OthersController::class . ':userpriv')->add(PermissionMiddleware::class)->setName('userpriv');

    // logout
    $app->any('/logout', OthersController::class . ':logout')->add(PermissionMiddleware::class)->setName('logout');

    // Swagger
    $app->get('/' . Config("SWAGGER_ACTION"), OthersController::class . ':swagger')->setName(Config("SWAGGER_ACTION")); // Swagger

    // Index
    $app->any('/[index]', OthersController::class . ':index')->add(PermissionMiddleware::class)->setName('index');

    // Route Action event
    if (function_exists(PROJECT_NAMESPACE . "Route_Action")) {
        Route_Action($app);
    }

    /**
     * Catch-all route to serve a 404 Not Found page if none of the routes match
     * NOTE: Make sure this route is defined last.
     */
    $app->map(
        ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        '/{routes:.+}',
        function ($request, $response, $params) {
            $error = [
                "statusCode" => "404",
                "error" => [
                    "class" => "text-warning",
                    "type" => Container("language")->phrase("Error"),
                    "description" => str_replace("%p", $params["routes"], Container("language")->phrase("PageNotFound")),
                ],
            ];
            Container("flash")->addMessage("error", $error);
            return $response->withStatus(302)->withHeader("Location", GetUrl("error")); // Redirect to error page
        }
    );
};
