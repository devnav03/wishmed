<?php

namespace App\Http\Controllers;
/**
 * :: Instruction Videos Controller ::
 * To manage lecture.
 *
 **/
use Auth;
use App\Models\InstructionVideo;
use App\Models\Product;
use Illuminate\Http\Request;

class InstructionVideosController extends Controller{
   
    public function  index() {
        
        return view('admin.instruction_videos.index');
    }

    public function  create() {
        return view('admin.instruction_videos.create');
    }


    public function  store(Request $request) {
        
        $inputs = $request->all();

        try {
              $validator = (new InstructionVideo)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }

            $inputs = $inputs + [
                    'created_by' => Auth::id(),
                ];   

            (new InstructionVideo)->store($inputs);
            return redirect()->route('instruction-videos.index')
                ->with('success', lang('messages.created', lang('Instruction Videos & Presentations')));
        } catch (\Exception $exception) {
     
            return redirect()->route('instruction-videos.create')
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

 
    public function update(Request $request, $id = null) {
        $result = (new InstructionVideo)->find($id);
        if (!$result) {
            abort(401);
        }
        $inputs = $request->all();
        try {
             $validator = (new InstructionVideo)->validate($inputs, $id);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }
            //\DB::beginTransaction();
            $inputs = $inputs + [
                    'updated_by' => Auth::id(),
                ];  
            
            (new InstructionVideo)->store($inputs, $id);
            return redirect()->route('instruction-videos.index')
                ->with('success', lang('messages.updated', lang('Instruction Videos & Presentations')));

        } catch (\Exception $exception) {
            return redirect()->route('instruction-videos.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }


    public function edit($id = null) {
        $result = (new InstructionVideo)->find($id);
        if (!$result) {
            abort(401);
        }

        $product = Product::where('id', $result->product_id)->select('name')->first();

        return view('admin.instruction_videos.create', compact('result', 'product'));
    }


    public function instruction_videosPaginate(Request $request, $pageNumber = null)
    {

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

            $data = (new InstructionVideo)->getInstructionVideo($inputs, $start, $perPage);
            $totalFaq = (new InstructionVideo)->totalInstructionVideo($inputs);
            $total = $totalFaq->total;
        } else {

            $data = (new InstructionVideo)->getInstructionVideo($inputs, $start, $perPage);
            $totalFaq = (new InstructionVideo)->totalInstructionVideo();
            $total = $totalFaq->total;
        }

        return view('admin.instruction_videos.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }


    public function instruction_videosToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = InstructionVideo::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('Instruction Videos & Presentations')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    }


    public function instruction_videosAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('instruction-videos.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('Instruction Videos & Presentations'))));
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

        InstructionVideo::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('instruction-videos.index')
            ->with('success', lang('messages.updated', lang('faq.faq')));
    }


    public function drop($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new InstructionVideo)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new InstructionVideo)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('instruction videos')];
             }
             else {
                 (new InstructionVideo)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('instruction videos'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }
}