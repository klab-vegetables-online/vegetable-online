<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\userController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\blogController;
use App\Http\Controllers\productcategoryController;
use App\Http\Controllers\productController;
use App\Http\Controllers\stockController;
use App\Http\Controllers\subcategoryController;
use App\Http\Controllers\orderController;
use App\Http\Controllers\BlogSubCategoryController;
use App\Http\Controllers\nutritionistsController;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::get('/', function () {
        return response()->json(['message' => 'Welcome to API']);
    });
    // ------------------Admin routes----------------------
    Route::prefix('Auth')->group(function () {
        Route::post('/signup', [AuthController::class, 'signup']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->get('/logout', [AuthController::class, 'logout']);
    });
    // ------------------User routes----------------------
    Route::prefix('user')->group(function () {
        // ------------------public routes----------------------
        Route::post('/register', [userController::class, 'register']);
        Route::post('/login', [userController::class, 'login']);

        // ------------------private routes----------------------
        Route::middleware('auth:sanctum')->group(function () {
            Route::put('/update', [userController::class, 'update']);
            Route::put('/change-password', [userController::class, 'changePassword']);
            Route::get('/logout', [userController::class, 'logout']);
        });
    });
    // -------------Categories Route--------------------------------
    Route::prefix('/category')->group(function () {
        //------------------public routes----------------------
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('search/{search}', [CategoryController::class, 'search']);
        Route::get('/{id}', [categoryController::class, 'show']);
        // -------------private Route--------------------------------
        Route::middleware('auth:sanctum')->group(function () {

            Route::post('/add', [CategoryController::class, 'store']);
            Route::put('/{id}', [CategoryController::class, 'update']);
            Route::delete('/{id}', [CategoryController::class, 'destroy']);
        });
    });

    // -------------Blog Route--------------------------------
    Route::prefix('/Blog')->group(function () {
        //------------------public routes----------------------
        Route::get('/', [blogController::class, 'getAllBlog']);
        Route::get('/bysubcategory/{id}', [blogController::class, 'getBlogBysubCategory']);
        Route::get('search/{search}', [blogController::class, 'search']);
        Route::get('/{id}', [blogController::class, 'show']);
        // -------------private Route--------------------------------
            Route::post('/add', [blogController::class, 'addBlog']);
            Route::put('/edit/{id}', [blogController::class, 'update']);
            Route::delete('/{id}', [blogController::class, 'destroy']);
        Route::middleware('auth:sanctum')->group(function () {

            
        });
    });


    // -------------product category Route--------------------------------
    Route::prefix('/product-category')->group(function () {
        //------------------public routes----------------------
        Route::get('/', [productcategoryController::class, 'index']);
        Route::get('/{id}', [productcategoryController::class, 'show']);

        // -------------private Route--------------------------------
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/add', [productcategoryController::class, 'store']);
            Route::put('/update/{id}', [productcategoryController::class, 'update']);
            Route::delete('/delete/{id}', [productcategoryController::class, 'destroy']);
        });
    });

    // -------------subcategory Route--------------------------------
    Route::prefix('/subcategory')->group(function () {
        //------------------public routes----------------------
        Route::get('/', [subcategoryController::class, 'index']);
        Route::get('/{id}', [subcategoryController::class, 'show']);
        // -------------protected Route--------------------------------
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/add', [subcategoryController::class, 'store']);
            Route::put('/update/{id}', [subcategoryController::class, 'update']);
            Route::delete('/delete/{id}', [subcategoryController::class, 'destroy']);
        });
    });

    // -------------product Route--------------------------------
    Route::prefix('/product')->group(function () {
        // ------------------public routes----------------------
        Route::get('/', [productController::class, 'index']);
        Route::get('/{id}', [productController::class, 'show']);
        Route::get('/bySubcategory/{id}', [productController::class, 'getProductBySubCategory']);
        Route::get('/byCategory/{id}', [productController::class, 'getProductByCategory']);
        // ------------------protected routes----------------------
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/add', [ProductController::class, 'store']);
            Route::put('/{id}', [ProductController::class, 'update']);
            Route::delete('/{id}', [ProductController::class, 'destroy']);
        });
    });

    // -------------stock Route--------------------------------
    Route::prefix('/stock')->group(function () {
        // ------------------public routes----------------------
        Route::get('/', [stockController::class, 'index']);
        Route::get('/{id}', [stockController::class, 'show']);
        // ------------------protected routes----------------------
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/add', [stockController::class, 'store']);
            Route::put('/out', [stockController::class, 'stockOut']);
            Route::delete('/{id}', [stockController::class, 'destroy']);
        });
    });
    // -------------Blog Subcategory Route--------------------------------
    Route::prefix('/blogsubcategory')->group(function () {
        // ------------------public routes----------------------
        Route::get('search/{search}', [blogController::class, 'search']);
        Route::get('/', [BlogSubCategoryController::class, 'index']);
        Route::get('/{id}', [BlogSubCategoryController::class, 'show']);
        // ------------------protected routes----------------------
            Route::post('/add', [BlogSubCategoryController::class, 'store']);
            Route::put('update/{id}', [BlogSubCategoryController::class, 'update']);
            Route::delete('delete/{id}', [BlogSubCategoryController::class, 'destroy']);
        Route::middleware('auth:sanctum')->group(function () {
            
        });
    });

    // -------------Nutritionists Route--------------------------------
    Route::prefix('Nutritionists')->group(function () {
        Route::middleware('auth:sanctum')->group(
            function () {

                Route::get('/', [nutritionistsController::class, 'getall']);
                Route::post('/Order', [nutritionistsController::class, 'store']);
                Route::get('/{id}', [nutritionistsController::class, 'show']);
                Route::put('update/{id}', [nutritionistsController::class, 'update']);
                Route::delete('delete/{id}', [nutritionistsController::class, 'destroy']);
            }
        );
    });
    // -------------Order Route--------------------------------
    Route::prefix('/order')->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/', [orderController::class, 'index']);
            Route::get('/{id}', [orderController::class, 'show']);
            Route::post('/add', [orderController::class, 'store']);
            Route::put('/{id}', [orderController::class, 'update']);
            Route::delete('/{id}', [orderController::class, 'destroy']);
        });
    });
});




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});