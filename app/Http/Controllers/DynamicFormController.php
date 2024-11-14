<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DynamicForm;
use App\Models\DefaultUser;
use App\Models\DynamicFormField;
use App\Models\DefaultFormAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Traits\CustomFieldTrait;

class DynamicFormController extends Controller
{
    use CustomFieldTrait;
    public function index()
    {
        $datas=DynamicForm::get();
        return view('home',compact('datas'));
    }

    public function getData()
    {
        $forms=DynamicForm::get();
        $htmlRows = '';
        foreach ($forms as $index => $form) {
            $dynamicLink = '<a href="' . route('user-ui.dynamic', ['slug' => $form->slug]) . '" class="">' . route('user-ui.dynamic', ['slug' => $form->slug]) . '</a>';
            $editLink = '<a href="' . route('dynamic-form.edit', ['id' => $form->id]) . '" class="btn btn-primary">Edit</a>';
            $deleteLink = '<a href="' . route('dynamic-form.delete', ['id' => $form->id]) . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete?\')">Delete</a>';
            $prvLink = '<a href="' . route('dynamic-form.preview', ['id' => $form->slug]) . '" class="btn btn-warning" target="_blank">Preview</a>';
            $htmlRows .= '<tr>' .
                            '<td>' . ($index + 1) . '</td>' .
                            '<td>' . $form->form_name . '</td>' .
                            '<td>' . $dynamicLink . '</td>' .
                            '<td>' .
                            $editLink . ' || ' .
                            $deleteLink . ' || ' .
                            $prvLink .
                        '</td>' .
                         '</tr>';
        }
        return response()->json(['html' => $htmlRows]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'form_name' => 'required|string|max:191',
        ]);
        DB::beginTransaction();
        try {
            $slug = Str::slug($request->form_name);
            $form=DynamicForm::create([
                'form_name'=>$request->form_name,
                'slug'=>$slug,
            ]);
            if ($request->has('label_name') && is_array($request->input('label_name'))) {
                foreach ($request->input('label_name') as $key=>$label_name) {
                    DynamicFormField::create([
                        'dynamic_form_id' => $form->id,
                        'label_name' => $label_name, 
                        'field_name' => Str::snake($label_name), 
                        'field_type' => $request->field_type[$key], 
                        'is_required' => $request->is_required[$key], 
                        'field_option' => $request->field_option[$key], 
                    ]);
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Data stored successfully');
        }catch(\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('error', 'There was an error storing the data');
        }
    }
    public function edit($id)
    {
        $dynamicForm=DynamicForm::findOrFail($id);
        $datas=DynamicForm::get();
        return view('home',compact('datas','dynamicForm'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'form_name' => 'required|string|max:191',
        ]);
        DB::beginTransaction();
        try {
            $form=DynamicForm::findorFail($request->dynamic_form_id);
            $slug = Str::slug($request->form_name);
            $form->update([
                'form_name'=>$request->form_name,
                'slug'=>$slug,
            ]);
            $form->fields()->delete();
            if ($request->has('label_name') && is_array($request->input('label_name'))) {
                foreach ($request->input('label_name') as $key=>$label_name) {
                    DynamicFormField::create([
                        'dynamic_form_id' => $form->id,
                        'label_name' => $label_name, 
                        'field_name' => Str::snake($label_name), 
                        'field_type' => $request->field_type[$key], 
                        'is_required' => $request->is_required[$key], 
                        'field_option' => $request->field_option[$key], 
                    ]);
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Data updated successfully');
        }catch(\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('error', 'There was an error storing the data');
        }
    }
    public function delete($id)
    {
        $dynamicForm=DynamicForm::findOrFail($id);
        $dynamicForm->delete();
        return redirect('home')->with('success', 'Data deleted successfully');
    }

    public function previewForm($slug){
        $form=DynamicForm::where('slug',$slug)->first();
        $fields=$form->fields;
        $formFieldsHtmls = [];
        foreach ($fields as $field) {
            $formFieldsHtmls[] = $this->renderField($field);
        }
        return view('form-preview',compact('form','formFieldsHtmls'));
    }

    public function dynamicForm($slug){
        $form=DynamicForm::where('slug',$slug)->first();
        $fields=$form->fields;
        $formFieldsHtmls = [];
        foreach ($fields as $field) {
            $formFieldsHtmls[] = $this->renderField($field);
        }
        return view('form-preview',compact('form','formFieldsHtmls'));
    }

    public function saveFormData(Request $request){
        DB::beginTransaction();
        try {
            $form=DynamicForm::where('slug',$request->form_slug)->first();
            $user=DefaultUser::where('email',$request->user_email)->first();
            if($user){
                if ($user->name !== $request->full_name) {
                    $user->update(['name' => $request->full_name]);
                }
            }else{
                $user=DefaultUser::create([
                    'name'=>$request->full_name,
                    'email'=>$request->user_email,
                ]);
            }
            $fields=$form->fields;
            foreach($fields as $field){
                if($field->field_type=='file'){
                    $filename=null;
                    if ($request->hasFile($field->field_name))  
                    {
                       $file = $request->file($field->field_name);
                       $originalName = $file->getClientOriginalName();
                       $extension = $file->getClientOriginalExtension();
                       $originalNameWithoutExt = substr($originalName , 0 , strlen($originalName) - strlen($extension) - 1);
                       $number = mt_rand(10000 , 99999);
                       $filename = $originalNameWithoutExt . '-' . $number . '.' . $extension;
                       $p = $file->move(
                           base_path() . '/public/uploads/' , $filename
                       );
                     }
                }
                $answervalue=$request->input($field->field_name);
                if($field->field_type=='checkbox'){
                    $answervalue = implode(',', $answervalue);
                }
                $answer=[
                    'dynamic_form_id'=>$form->id,
                    'dynamic_form_field_id'=>$field->id,
                    'default_user_id'=>$user->id,
                    'answer'=>$field->field_type=='file' ? $filename :$answervalue,
                ];
                DefaultFormAnswer::create($answer);
            }
            DB::commit();
            return redirect('/greeting-page');
        }catch(\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('error', 'There was an error storing the data');
        }
    }
    public function greetingPage(){
        return view('greeting');
    }
    public function formAnswer(Request $request){
        $forms=DynamicForm::get();
        $users=DefaultUser::get();
        return view('form-answer',compact('forms','users'));
    }
    public function formAnswerGetData(Request $request){
        $forms=DefaultFormAnswer::select('dynamic_form_id', 'default_user_id')->with('form','user')->groupBy('dynamic_form_id','default_user_id');
        if($request->form_id){
            $forms=$forms->where('dynamic_form_id',$request->form_id);
        }
        if($request->user_id){
            $forms=$forms->where('default_user_id',$request->user_id);
        }
        $forms=$forms->get();
        $htmlRows = '';
        foreach ($forms as $index => $form) {
            $viewLink = '<a href="' . route('form-answer.details', ['user_id' => $form->default_user_id,'form_id'=>$form->dynamic_form_id]) . '" class="btn btn-primary" target="_blank">View Detail</a>';
            $htmlRows .= '<tr>' .
                            '<td>' . ($index + 1) . '</td>' .
                            '<td>' . $form->form->form_name . '</td>' .
                            '<td>' . $form->user->name . '</td>' .
                            '<td>' . $form->user->email . '</td>' .
                            '<td>' . $viewLink . '</td>' .
                         '</tr>';
        }
        return response()->json(['html' => $htmlRows]);
    }

    public function formAnswerDetails($user_id,$form_id){
        $dUser=DefaultUser::find($user_id);
        $form=DynamicForm::findOrFail($form_id);
        $fields=$form->fields;
        $formFieldsHtmls = [];
        $answers=DefaultFormAnswer::where('dynamic_form_id',$form_id)->where('default_user_id',$user_id)->get();
        foreach ($fields as $field) {
            $value=$answers->where('dynamic_form_field_id',$field->id)->first()->answer;
            $formFieldsHtmls[] = $this->renderField($field,$value);
        }
        $detailPage='detailPage';
        return view('form-preview',compact('form','formFieldsHtmls','answers','detailPage','dUser'));
    }

}
