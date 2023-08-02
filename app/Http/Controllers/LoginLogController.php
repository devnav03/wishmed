<?php

namespace App\Http\Controllers;

use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\LoginLog;
use App\User;
use Illuminate\Http\Request;

class LoginLogController extends  Controller{
    
    public function index() {

        return view('admin.login_log.index');
    }

    public function login_logsPaginate(Request $request, $pageNumber = null) {

        if (!\Request::isMethod('post') && !\Request::ajax()) { //
            return lang('messages.server_error');
        }

        $inputs = $request->all();
        $page = 1;
        if (isset($inputs['page']) && (int)$inputs['page'] > 0) {
            $page = $inputs['page'];
        }

        $perPage = 20;
        if (isset($inputs['perpage']) && (int)$inputs['perpage'] > 0) {
            $perPage = $inputs['perpage'];
        }

        $start = ($page - 1) * $perPage;
        if (isset($inputs['form-search']) && $inputs['form-search'] != '') {
            $inputs = array_filter($inputs);
            unset($inputs['_token']);

            $data = (new LoginLog)->getlogin_logs($inputs, $start, $perPage);
            $totalproduct = (new LoginLog)->totallogin_logs($inputs);
            $total = $totalproduct->total;
        } else {

            $data = (new LoginLog)->getlogin_logs($inputs, $start, $perPage);
            $totalProduct = (new LoginLog)->totallogin_logs();
            $total = $totalProduct->total;
        }

        return view('admin.login_log.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

    public function login_logsAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('login-logs.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('Login Logs'))));
        }

        $ids = '';
        foreach ($inputs['tick'] as $key => $value) {
            $ids .= $value . ',';
        }

        $ids = rtrim($ids, ',');
        $status = 0;
        if (isset($inputs['active'])) {
            $status = 1;
        }

        LoginLog::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('login-logs.index')
            ->with('success', lang('messages.updated', lang('Login Logs')));
    }

 
    

}
