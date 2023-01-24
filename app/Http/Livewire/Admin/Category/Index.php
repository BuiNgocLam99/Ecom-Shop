<?php

namespace App\Http\Livewire\Admin\Category;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $name;
    public $slug;
    public $description;
    public $image;
    public $meta_title;
    public $meta_keyword;
    public $meta_description;
    public $status;
    public $category_id;
    public $new_image;
    public $uploadedFileUrl;

    public function resetInput()
    {
        $this->name = NULL;
        $this->slug = NULL;
        $this->description = NULL;
        $this->image = NULL;
        $this->status = NULL;
        $this->meta_title = NULL;
        $this->meta_keyword = NULL;
        $this->meta_description = NULL;
        $this->category_id = NULL;
        $this->new_image = NULL;
        $this->uploadedFileUrl = NULL;
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    public function storeCategory()
    {
        $this->validate([
            'name' => 'required|string',
            'slug' => 'required|string',
            'description' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png',
            'meta_title' => 'required|string',
            'meta_keyword' => 'required|string',
            'meta_description' => 'required|string',
            'status' => 'nullable',
        ]);

        if ($this->image) {
            $this->uploadedFileUrl = Cloudinary::upload($this->image->getRealPath())->getSecurePath();
        }

        Category::create([
            'name' => $this->name,
            'slug' => Str::slug($this->slug),
            'description' => $this->name,
            'image' => $this->uploadedFileUrl,
            'meta_title' => $this->name,
            'meta_keyword' => $this->name,
            'meta_description' => $this->name,
            'status' => $this->status == true ? 1 : 0,
        ]);

        session()->flash('message', 'Category Added Successfully');
        $this->dispatchBrowserEvent('close-modal');
        $this->resetInput();
    }

    public function editCategory($category_id)
    {
        $this->category_id = $category_id;
        $category = Category::find($category_id);
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->status = $category->status;
        $this->image = $category->image;
        $this->meta_title = $category->meta_title;
        $this->meta_keyword = $category->meta_keyword;
        $this->meta_description = $category->meta_description;
    }

    public function updateCategory()
    {
        $this->validate([
            'name' => 'required|string',
            'slug' => 'required|string',
            'description' => 'required',
            'new_image' => 'nullable|mimes:jpg,jpeg,png',
            'meta_title' => 'required|string',
            'meta_keyword' => 'required|string',
            'meta_description' => 'required|string',
            'status' => 'nullable',
        ]);

        if ($this->new_image) {
            $this->uploadedFileUrl = Cloudinary::upload($this->image->getRealPath())->getSecurePath();
        }

        Category::find($this->category_id)->update([
            'name' => $this->name,
            'slug' => Str::slug($this->slug),
            'description' => $this->name,
            'image' => $this->uploadedFileUrl ? $this->uploadedFileUrl : $this->image,
            'meta_title' => $this->name,
            'meta_keyword' => $this->name,
            'meta_description' => $this->name,
            'status' => $this->status == true ? 1 : 0,
        ]);

        session()->flash('message', 'Category Updated Successfully');
        $this->dispatchBrowserEvent('close-modal');
        $this->resetInput();
    }

    public function deleteCategory($category_id)
    {
        $this->category_id = $category_id;
    }

    public function destroyCategory()
    {
        $category = Category::find($this->category_id)->delete();

        session()->flash('message', 'Category Deleted Successfully!');
        $this->dispatchBrowserEvent('close-modal');
        $this->resetInput();
    }

    public function render()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(5);
        return view('livewire.admin.category.index', ['categories' => $categories])
            ->extends('layouts.admin')
            ->section('content');
    }
}
