<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\CategoriesController;




Route::prefix('admin')->group(function(){
    Route::prefix('categories')->group(function(){
        Route::get('create',[CategoriesController::class,'create'])->name('admin.categories.create');
        Route::post('',[CategoriesController::class,'store'])->name('admin.categories.store');
        Route::get('',[CategoriesController::class,'all'])->name('admin.categories.all');
        Route::DELETE('{categoury_id}/delete',[CategoriesController::class,'delete'])->name('admin.categories.delete');
        Route::get('{categoury_id}/edit',[CategoriesController::class,'edit'])->name('admin.categories.edit');
        Route::PUT('{categoury_id}/update',[CategoriesController::class,'update'])->name('admin.categories.update');
    });
        Route::prefix('products')->group(function(){
        Route::get('create',[ProductsController::class,'create'])->name('admin.products.create');
        Route::post('',[ProductsController::class,'store'])->name('admin.products.store');
        Route::get('',[ProductsController::class,'all'])->name('admin.products.all');
        Route::get('{product_id}/download/demo',[ProductsController::class,'downloadDemo'])->name('admin.products.download.demo');
        Route::get('{product_id}/download/source',[ProductsController::class,'downloadSource'])->name('admin.products.download.source');
        Route::DELETE('{product_id}/delete',[ProductsController::class,'delete'])->name('admin.products.delete');
        Route::get('{product_id}/edit',[ProductsController::class,'edit'])->name('admin.products.edit');
        Route::PUT('{product_id}/update',[ProductsController::class,'update'])->name('admin.products.update');


    });
});