<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;


class SectionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:show-section')->only(['index', 'show']);
        $this->middleware('permission:create-section')->only(['create', 'store']);
        $this->middleware('permission:edit-section')->only(['edit', 'update']);
        $this->middleware('permission:delete-section')->only(['destroy']);
    }
//    use SoftDeletes;
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $sections = Section::latest()->with('user')->simplePaginate(250);
        $total = Section::count();
        return view('sections.index' , compact('sections' , 'total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userVaidation = $request->validate([
            'title' => 'required',
            'body' => 'required',

        ]);
        $is_exist = Section::where('title' , $request->title)->exists();
        if($is_exist){
            return redirect()->back()->withErrors(['title' => 'title is already exits']);
        }
        Section::create([
            'title'=> $request->title,
            'body'=> $request->body,
            'user_id'=> Auth::user()->id
        ]);
        return redirect()->back()->with('success' , 'Section created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section)
    {
        DB::beginTransaction();
        try {

             $request->validate([
                'title' => 'required|string|max:255|unique:sections,title,' . $section->id,
                'body' => 'required|string|min:10',
            ], [
                'title.required' => 'عنوان القسم مطلوب.',
                'title.unique' => 'هذا العنوان مستخدم بالفعل في قسم آخر.',
                'body.required' => 'وصف القسم مطلوب.',
                'body.min' => 'الوصف يجب أن يكون طوله على الأقل 10 أحرف.'
            ]);
            $is_exist = Section::where('title', $request['title'])
                ->where('id', '!=', $section->id)
                ->exists();
            if ($is_exist) {
                return redirect()->back()->withErrors(['title' => 'title is already exits']);
            }

            $section->update([
                'title' => $request['title'],
                'body' => $request['body'],
                'user_id' => Auth::user()->id,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'تم تحديث القسم بنجاح.');

        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء تحديث القسم. يرجى المحاولة مرة أخرى.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public  function destroy(Section $section)
    {
        try {
            $section = Section::findOrFail($section->id);
            $section->delete();
            return redirect()->back()->with('success', 'Section deleted successfully.');
        }catch (\Exception $e){
            return redirect()->back()->withErrors(['error' => 'An error occurred while deleting the section.']);
        }
    }


//        public function restore(Section $section)
//    {
//        $section = Section::withTrashed()->findOrFail($section);
//        $section->restore();
//
//        return redirect()->back()->with('success', 'Section restored successfully.');
//    }

}
