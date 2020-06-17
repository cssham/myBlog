<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.category.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|unique:categories',
            'image' => 'required|mimes:jpg,jpeg,png,bmp'
        ]);

       //get form image
        $image = $request->file('image');
        $slug = Str::slug($request->name);
        if (isset($image)) {
            $currentDate = Carbon::now()->toDateString();
            $imageName =$slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();
            //check category directory exists
            if(!file_exists('public/storage/category/')) {
                mkdir('public/storage/category/',0777,true);
            }
            //resize image for category
            $category = Image::make($image)->resize(1600,479)->save($imageName);
            $category->move('public/storage/category/',$imageName);
            //check category slider directory exists
           if(!file_exists('public/storage/category/slider/')) {
                mkdir('public/storage/category/slider/',0777,true);
            }
            //resize image for category slider
            $slider = Image::make($image)->resize(500, 333)->save($imageName);
            $slider->move('public/storage/category/',$imageName);
        }else
        {
            $imageName = 'default.png';
        }
        $category = new Category();
        $category->name = $request->name;
        $category->slug = $slug;
        $category->image = $imageName;
        $category->save();
        Toastr::success('Category saved successfully', 'success');
        return redirect()->route('admin.category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        return view('admin.category.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'image' => 'mimes:jpg,jpeg,png,bmp'
        ]);

        //get form image
        $image = $request->file('image');
        $slug = Str::slug($request->name);
        $category = Category::find($id);
        if (isset($image)) {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug . '-' . $currentDate . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            //check category directory exists
           if(!file_exists('public/storage/category/')) {
                mkdir('public/storage/category/',0777,true);
            }
            //delete old image
            if(file_exists('public/storage/category/'.$category->image)) {
                unlink('public/storage/category/' . $category
                    ->image);
            }
            //resize image for category
            $categoryName = Image::make($image)->resize(1600, 479)->save($imageName);
            $categoryName->move('public/storage/category/',$imageName);
            //check category slider directory exists
            //check category directory exists
           if(!file_exists('public/storage/category/slider/')) {
                mkdir('public/storage/category/slider/',0777,true);
            }
            //delete old image
            if(file_exists('public/storage/category/slider/'.$category->image)) {
                unlink('public/storage/category/slider/' . $category
                    ->image);
            }
            //resize image for category
            $slider = Image::make($image)->resize(500, 333)->save($imageName);
            $slider->move('public/storage/category/slider/',$imageName);
        } else {
            $imageName = $category->image;
        }

        $category->name = $request->name;
        $category->slug = $slug;
        $category->image = $imageName;
        $category->save();
        Toastr::success('Category updated successfully', 'success');
        return redirect()->route('admin.category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if(file_exists('public/storage/category/' . $category
            ->image)){
        unlink('public/storage/category/'.$category
        ->image);
            }
        if(file_exists('public/storage/category/slider/' . $category
            ->image)){
        unlink('public/storage/category/slider/'.$category
        ->image);
            }
        $category->delete();
        Toastr::success('Category deleted successfully', 'success');
        return redirect()->back();
    }
}
