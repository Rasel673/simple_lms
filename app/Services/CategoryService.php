<?php

namespace App\Services;

use App\Models\Category;
use App\Support\PublicImageStorage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CategoryService
{
    public function getActiveCategoriesWithCount(): Collection
    {
        return Category::where('is_active', true)
            ->withCount(['courses' => function ($q) {
                $q->where('status', 'published');
            }])
            ->get();
    }

    public function getAllCategories(): Collection
    {
        return Category::withCount('courses')->get();
    }

    public function createCategory(array $data, $imageFile = null): Category
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        $originalSlug = $data['slug'];
        $count = 1;
        while (Category::where('slug', $data['slug'])->exists()) {
            $data['slug'] = "{$originalSlug}-" . $count++;
        }

        if ($imageFile) {
            $data['image'] = PublicImageStorage::store($imageFile, 'categories');
        }

        return Category::create($data);
    }

    public function updateCategory(Category $category, array $data, $imageFile = null): Category
    {
        if (!empty($data['slug'])) {
            $data['slug'] = Str::slug($data['slug']);
            $originalSlug = $data['slug'];
            $count = 1;
            while (Category::where('slug', $data['slug'])->where('id', '!=', $category->id)->exists()) {
                $data['slug'] = "{$originalSlug}-" . $count++;
            }
        }

        if ($imageFile) {
            PublicImageStorage::delete($category->image);
            $data['image'] = PublicImageStorage::store($imageFile, 'categories');
        }

        $category->update($data);
        return $category;
    }

    public function deleteCategory(Category $category): array
    {
        // Prevent deleting if still in use
        if ($category->courses()->exists()) {
            return [
                'success' => false,
                'message' => 'Cannot delete this category because it has active courses assigned to it. Please reassign or delete the courses first.',
            ];
        }

        PublicImageStorage::delete($category->image);

        $category->delete();

        return [
            'success' => true,
            'message' => 'Category deleted successfully.',
        ];
    }
}
