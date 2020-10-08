<?php

namespace App\Http\Controllers\backend;


use App\Http\Controllers\Controller;
use App\Http\Requests\AddAttrRequest;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\AddValueRequest;
use App\Http\Requests\EditAttrRequest;
use App\Http\Requests\EditProductRequest;
use App\models\attribute;
use App\Models\Category;
use App\Models\Product;
use App\models\values;
use App\models\variant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;



class ProductController extends Controller
{
    //
    public function ListProduct()
    {

            $data['products']=Product::paginate(3);
            return view('backend.product.listproduct',$data);
    }
    public function PostProduct(AddProductRequest $request)
    {
        $product=new Product;
        $product->product_code=$request->product_code;
        $product->name= $request->product_name;
        $product->price= $request->product_price;
        $product->featured=$request->featured;
        $product->state= $request->product_state;
        $product->info= $request->info;
        $product->describe= $request->description;
        if($request->hasFile('product_img'))
        {
        $file = $request->product_img;
        $fileName=Str::slug($request->product_name,'-').'.'.$file->getClientOriginalExtension();
        $file->move('backend/img',$fileName);
        $product->img= $fileName;
        }
        else {
           $product->img='no-img.jpg';
        }
        $product->category_id=$request->category;
        $product->save();
        $mang=array();
        foreach($request->attr as $value)
        {
           foreach($value as $item)
           {
              $mang[]= $item;
           }
        }
        $product->values()->Attach($mang);
        $variant=get_combinations($request->attr);

   foreach($variant as $var)
   {
      $vari=new variant;
      $vari->product_id=$product->id;
      $vari->save();
      $vari->values()->Attach($var);
   }
   return redirect('admin/product/add-variant/'.$product->id);
    }

    public function EditProduct($id)
   {
     $data['product']=Product::find($id);
     $data['categories']=Category::all();
     $data['attrs']=attribute::all();
    return view('backend.product.editproduct',$data);
   }

   public function PostEditProduct(EditProductRequest $request,$id)
   {

    $product = product::find($id);
    $product->product_code=$request->product_code;
    $product->name= $request->product_name;
    $product->price= $request->product_price;
    $product->featured=$request->featured;
    $product->state= $request->product_state;
    $product->info= $request->info;
    $product->describe= $request->description;
    if($request->hasFile('product_img'))
       {
          if($product->img!='no-img.jpg')
          {
             unlink('backend/img/'.$product->img);
          }

          $file = $request->product_img;
          $fileName=Str::slug($request->product_name,'-').'.'.$file->getClientOriginalExtension();
          $file->move('backend/img',$fileName);
          $product->img= $fileName;
       }

    $product->category_id= $request->category;
    $product->save();

    $mang=array();
    foreach($request->attr as $value)
    {
       foreach($value as $item)
       {
          $mang[]= $item;
       }
    }
    $product->values()->Sync($mang);
    $variant=get_combinations($request->attr);
    foreach($variant as $var)
    {
       if(check_var($product,$var))
       {
       $vari=new variant;
       $vari->product_id=$product->id;
       $vari->save();
       $vari->values()->Attach($var);
       }

    }
    return redirect()->back()->with('thongbao','Đã sửa thành công!');
   }
   public function DelProduct($id)
   {
     Product::destroy($id);
     return redirect()->back()->with('thongbao','Đã xoá thành công!');
   }
    public function AddProduct()
    {
        $data['categories']=Category::all();
        $data['attrs']=attribute::all();
        return view('backend.product.addproduct',$data);
    }
    public function DetailAttr()
    {
        $data['attrs']=attribute::all();
        return view('backend.attr.attr',$data);
    }
    public function EditAttr($id)
    {
        $data['attr']=attribute::find($id);
        return view('backend.attr.editattr',$data);
    }
    public function PostAttr($id,EditAttrRequest $request)
    {
        $attr =attribute::find($id);
        $attr->name=$request->attr_name;
        $attr->save();

        return redirect()->back()->with('thongbao','Đã sửa thành công!');

    }
    public function DelAttr($id)
    {
        attribute::destroy($id);

        return redirect('/admin/product/detail-attr')->with('thongbao','Đã xóa thành công!');

    }
    public function EditValue()
    {
        return view('backend.attr.editvalue');
    }
    public function AddVariant($id)
    {
        $data['product']=Product::find($id);
        return view('backend.variant.addvariant',$data);
    }
    public function EditVariant($id)
    {
        $data['product']=Product::find($id);
        return view('backend.variant.editvariant',$data);
    }
    public function PostAddVariant(request $request,$id)
    {
        foreach($request->variant as $key=>$value)
        {
            $vari=variant::find($key);
            $vari->price=$value;
            $vari->save();
        }
        return redirect('/admin/product')->with('thongbao','Đã thêm thành công!');
    }
    public function DelVariant($id)
    {
        variant::destroy($id);
        return redirect()->back()->with('thongbao','Đã xóa thành công!');
    }
    public function PostEditVariant(request $request,$id)
    {
        foreach($request->variant as $key=>$value)
        {
            $vari=variant::find($key);
            $vari->price=$value;
            $vari->save();
        }
        return redirect('/admin/product')->with('thongbao','Đã sửa thành công!');
    }

    public function AddAttr(AddAttrRequest $request)
    {
        $attr= new attribute;
        $attr->name=$request->attr_name;
        $attr->save();

      return redirect()->back()->with('thongbao','Đã thêm thành công thuộc tính:'.$request->attr_name);
    }
    public function AddValue(AddValueRequest $request)
    {
        $value=new values;
    $value->attr_id=$request->attr_id;
    $value->value=$request->value_name;
    $value->save();
    return redirect()->back()->with('thongbao','Đã thêm giá trị:'.$request->value_name);
    }

}
