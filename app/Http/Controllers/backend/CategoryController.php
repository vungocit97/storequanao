<?php

namespace App\Http\Controllers\backend;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCategoryRequest;
use App\Http\Requests\EditCategoryRequest;
use Illuminate\Http\Request;
use App\models\Category;

class CategoryController extends Controller
{
    //
    public function GetCategory()
    {    $data['categories']=Category::all()->toarray();
         return view('backend.category.category',$data);
    }
    public function EditCategory($id)
    {
        $data['cate']=Category::find($id);
        $data['categories']=Category::all()->toarray();
        return view('backend.category.editcategory',$data);
    }
    public function PostCategory(AddCategoryRequest $request)
    {
        $cate= new Category;
        $cate->name=$request->name;
        $cate->parent=$request->parent;
        $cate->save();
        return redirect()->back()->with('thongbao','Đã thêm thành công !');
    }
    public function PostEditCategory(EditCategoryRequest $request,$id)
    {
        $cate=Category::find($id);
        $cate->name=$request->name;
        $cate->parent=$request->parent;
        $cate->save();
        return redirect()->back()->with('thongbao','Đã sửa thành công !');
    }
    function DelCategory($id)
    {
        $cate=Category::find($id);
        $parent=$cate->parent;
        Category::where('parent',$cate->id)->update(['parent'=>$parent]);
        Category::destroy($id);
        return redirect()->back()->with('thongbao','Đã xóa thành công !');
    }

}
