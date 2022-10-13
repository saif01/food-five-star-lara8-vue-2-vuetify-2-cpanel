<?php

use Illuminate\Support\Facades\Route;

// Route::get('redirects', 'App\Http\Controllers\HomeController@index');


// Auth Route Start
Route::namespace('App\Http\Controllers')->group(function(){
    // Auth 
    Route::namespace('Auth')->group(function(){

        Route::post('/login_action', 'LoginController@login_action');
        Route::get('/logout', 'LoginController@logout')->name('logout');
        Route::get('/login/{any?}', 'IndexController@index')->name('login');
        // for reset
        Route::post('/reset_pass', 'ResetController@reset_pass');
        // verify
        Route::post('/verify_otp', 'ResetController@verify_otp');
        // set new password
        Route::post('/new_password', 'ResetController@new_password');
        // auth_check
        Route::get('/auth_check', 'IndexController@auth_check');

        Route::get('/test_sms', 'ResetController@test_sms');
        // block user
        Route::post('/block_user/{id}', 'BlockController@block_user');
        
    });

    // test schedule message 
    Route::get('/message', 'Food\Message\IndexController@SentOwnerDailyMessage');
    Route::get('/message_line', 'Food\Message\LineSmsSendController@test');

  

    // Auth Route Start 
   
    // Food Start  ['auth', 'redirect']
    Route::middleware('auth', 'online')->namespace('Food')->group(function(){

        // Common
        Route::prefix('common')->group(function(){
            Route::get('/all_outlets', 'CommonController@all_outlets');
            Route::get('/all_zones', 'CommonController@all_zones');

            Route::get('/all_assigned_zones_cvcode', 'CommonController@all_assigned_zones_cvcode');
            Route::get('/all_assigned_outlets_byzone', 'CommonController@all_assigned_outlets_byzone');

            // For Maintance Mode
            Route::post('app_maintance_mode', 'CommonController@app_maintance_mode');

        });
        
        //log view
        Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);



         // Test API Data  
        Route::namespace('API')->prefix('orcl-api-test')->group(function(){
            Route::get('/invoice', 'InvoiceController@invoice');
            Route::get('/outlet', 'OutletController@outlet');
        });

        
        // Admin 
        // Route::middleware('can:Admin')->namespace('Admin')->prefix('admin')->group(function(){
        Route::middleware('redirect:Admin')->namespace('Admin')->prefix('admin')->group(function(){

            Route::get('/dashboard_data', 'IndexController@dashboard_data');

            //dd('admin');

           

            Route::namespace('User')->prefix('user')->group(function(){

                Route::middleware('permit:admin-view')->group(function(){
                    Route::get('/index', 'IndexController@index');
                    Route::post('/store', 'IndexController@store')->middleware('permit:admin-add');
                    Route::put('/update/{id}', 'IndexController@update')->middleware('permit:admin-edit');
                    Route::post('/status/{id}', 'IndexController@status')->middleware('permit:admin-status');
                    Route::delete('/destroy/{id}', 'IndexController@destroy')->middleware('administration');
                    Route::delete('/delete_temp/{id}', 'IndexController@delete_temp')->middleware('administration');
                });
                
                Route::get('/managers', 'IndexController@managers');
                

                // role
                Route::get('/roles_data', 'IndexController@roles_data');
                // Role Assign
                Route::post('/roles_update', 'IndexController@roles_update')->middleware('permit:admin-role-assign');
                // Zones Assign
                Route::post('/zones_update', 'IndexController@zones_update')->middleware('permit:admin-rone-assign');

                // owner
                Route::middleware('permit:owner-view')->prefix('owner')->group(function(){
                    Route::get('/index', 'OwnerController@index');
                    Route::post('/store', 'OwnerController@store')->middleware('permit:owner-add');
                    Route::put('/update/{id}', 'OwnerController@update')->middleware('permit:owner-edit');
                    Route::post('/status/{id}', 'OwnerController@status')->middleware('permit:owner-status');
                    Route::get('/get_cvcode/{id}', 'OwnerController@get_cvcode');
                    Route::get('/not_assign_outlets', 'OwnerController@not_assign_outlets');
                    Route::delete('/delete_temp/{id}', 'OperatorController@delete_temp')->middleware('permit:owner-delete');
                    
                });

                // operator
                Route::middleware('permit:operator-view')->prefix('operator')->group(function(){
                    Route::get('/index', 'OperatorController@index');
                    Route::post('/store', 'OperatorController@store')->middleware('permit:operator-add');
                    Route::put('/update/{id}', 'OperatorController@update')->middleware('permit:operator-edit');
                    Route::post('/status/{id}', 'OperatorController@status')->middleware('permit:operator-status');
                    Route::delete('/delete_temp/{id}', 'OperatorController@delete_temp')->middleware('permit:operator-delete');
                    Route::delete('/destroy/{id}', 'OperatorController@destroy')->middleware('administration');
                });

                Route::middleware('administration')->prefix('role')->group(function(){
                    Route::get('/index', 'RoleController@index');
                    Route::post('/store', 'RoleController@store');
                    Route::put('/update/{id}', 'RoleController@update');
                    Route::delete('/destroy/{id}', 'RoleController@destroy');
                    Route::delete('/delete_temp/{id}', 'RoleController@delete_temp');
                });

                Route::middleware('permit:zone-view')->prefix('zone')->group(function(){
                    Route::get('/index', 'ZoneController@index');
                    Route::post('/store', 'ZoneController@store')->middleware('permit:zone-add');
                    Route::put('/update/{id}', 'ZoneController@update')->middleware('permit:zone-edit');
                    Route::post('/status/{id}', 'ZoneController@status')->middleware('permit:zone-status');
                    Route::delete('/delete_temp/{id}', 'ZoneController@delete_temp')->middleware('permit:zone-delete');
                });


                // Logs
                Route::middleware('permit:Login-Log-View')->prefix('logs')->group(function(){
                    Route::get('/index', 'LoginLogController@index');
                });
            });


            Route::middleware('permit:outlet-view')->namespace('Outlet')->prefix('outlet')->group(function(){

                Route::get('/index', 'IndexController@index');
                Route::post('/store', 'IndexController@store')->middleware('permit:outlet-add');
                Route::put('/update/{id}', 'IndexController@update')->middleware('permit:outlet-edit');
                Route::post('/status/{id}', 'IndexController@status')->middleware('permit:outlet-status');
                Route::delete('/delete_temp/{id}', 'IndexController@delete_temp')->middleware('permit:outlet-delete');
                Route::delete('/destroy/{id}', 'IndexController@destroy')->middleware('administration');

                Route::get('/check_cv_code', 'IndexController@check_cv_code');
                // get_manager_and_officer
                Route::get('/get_user', 'IndexController@get_manager_and_officer');
                // get food item
                Route::get('/item', 'IndexController@item');
               
            });



            // Product
            Route::namespace('Product')->prefix('product')->group(function(){

                Route::middleware('permit:product-sale-view')->group(function(){
                    Route::get('/index', 'SaleProductController@index');
                    Route::post('/store', 'SaleProductController@store')->middleware('permit:product-sale-add');
                    Route::put('/update/{id}', 'SaleProductController@update')->middleware('permit:product-sale-edit');
                    Route::delete('/delete_temp/{id}', 'SaleProductController@delete_temp')->middleware('permit:product-sale-delete');
                    Route::post('/status/{id}', 'SaleProductController@status')->middleware('permit:product-sale-status');
                    Route::delete('/destroy/{id}', 'SaleProductController@destroy')->middleware('administration');
                });
                // type
                Route::get('/type', 'SaleProductController@type');
                // sales_product
                Route::get('/sales_product', 'SaleProductController@sales_product');
                // free_item
                Route::get('/free_item', 'SaleProductController@free_item');
                


                Route::middleware('permit:product-order-view')->prefix('order_product')->group(function(){
                    Route::get('/index', 'OrderProductController@index');
                    Route::post('/store', 'OrderProductController@store')->middleware('permit:product-order-add');
                    Route::put('/update/{id}', 'OrderProductController@update')->middleware('permit:product-order-edit');
                    Route::delete('/delete_temp/{id}', 'OrderProductController@delete_temp')->middleware('permit:product-order-delete');
                    Route::post('/status/{id}', 'OrderProductController@status')->middleware('permit:product-order-status');
                    Route::delete('/destroy/{id}', 'OrderProductController@destroy')->middleware('administration');

                    Route::get('/type', 'OrderProductController@type');
                });

            });

            Route::middleware('permit:product-sale-category-view')->namespace('ProductSaleType')->prefix('sale_type')->group(function(){
                Route::get('/index', 'IndexController@index');
                Route::post('/store', 'IndexController@store')->middleware('permit:product-sale-category-add');
                Route::put('/update/{id}', 'IndexController@update')->middleware('permit:product-sale-category-edit');
                Route::delete('/delete_temp/{id}', 'IndexController@delete_temp')->middleware('permit:product-sale-category-delete');
                Route::post('/status/{id}', 'IndexController@status')->middleware('permit:product-sale-category-status');
                Route::delete('/destroy/{id}', 'IndexController@destroy')->middleware('administration');
            });

            Route::middleware('permit:product-order-category-view')->namespace('ProductOrderType')->prefix('order_type')->group(function(){
                Route::get('/index', 'IndexController@index');
                Route::post('/store', 'IndexController@store')->middleware('permit:product-order-category-add');
                Route::put('/update/{id}', 'IndexController@update')->middleware('permit:product-order-category-edit');
                Route::delete('/delete_temp/{id}', 'IndexController@delete_temp')->middleware('permit:product-order-category-delete');
                Route::post('/status/{id}', 'IndexController@status')->middleware('permit:product-order-category-status');
                Route::delete('/destroy/{id}', 'IndexController@destroy')->middleware('administration');
            });

            
           
            Route::middleware('permit:order-view')->namespace('Order')->prefix('orders')->group(function(){

                Route::get('/index', 'IndexController@index');
                Route::post('/store', 'IndexController@store')->middleware('permit:order-place-order');
                Route::post('/update', 'IndexController@update')->middleware('permit:order-modify-order');
                Route::post('/status/{id}', 'IndexController@status')->middleware('permit:order-status');
                Route::post('/order_details', 'IndexController@order_details')->middleware('permit:order-details-view');

                Route::post('/order_approved_manager', 'IndexController@order_approved_manager');

                Route::get('/food_item_with_modify_order', 'IndexController@food_item_with_modify_order');
                Route::get('/food_item_list', 'IndexController@food_item_list');

                Route::middleware('permit:order-payment')->prefix('payment')->group(function(){
                    Route::post('/store', 'PaymentController@store');
                });

                Route::prefix('approve')->group(function(){
                    Route::post('/manager', 'ApprovalController@manager')->middleware('permit:order-manager-approve');
                    Route::post('/admin', 'ApprovalController@admin')->middleware('permit:order-admin-approve');
                });

                // sku report
                Route::get('/sku', 'IndexController@sku');

                Route::prefix('adjustOrder')->group(function(){
                    Route::get('/index', 'AdjustOrderController@index');
                    Route::post('/modify', 'AdjustOrderController@modify');
                });


            });

            // Announcement 
            Route::middleware('permit:announcement-view')->namespace('Announcement')->prefix('announcement')->group(function(){
                Route::get('/index', 'IndexController@index');
                Route::post('/store', 'IndexController@store')->middleware('permit:announcement-add');
                Route::put('/update/{id}', 'IndexController@update')->middleware('permit:announcement-edit');
                Route::delete('/delete_temp/{id}', 'IndexController@delete_temp')->middleware('permit:announcement-delete');
                Route::post('/status/{id}', 'IndexController@status')->middleware('permit:announcement-status');
                Route::delete('/destroy/{id}', 'IndexController@destroy')->middleware('administration');
            });

            // message
            Route::middleware('permit:message-view')->namespace('message')->prefix('message')->group(function(){
                Route::get('/index', 'MessageController@index');
                Route::post('/store', 'MessageController@store')->middleware('permit:message-add');
                Route::delete('/delete_temp/{id}', 'MessageController@delete_temp')->middleware('permit:message-delete');
                Route::put('/update/{id}', 'MessageController@update')->middleware('administration');
                Route::post('/status/{id}', 'MessageController@status')->middleware('administration');
                Route::delete('/destroy/{id}', 'MessageController@destroy')->middleware('administration');
            });
            
            
            // report
            Route::middleware('permit:report')->namespace('Report')->prefix('report')->group(function(){

                
                Route::prefix('outlet')->group(function(){
                    Route::get('/index', 'OutletController@index');
                    Route::get('/export_data', 'OutletController@export_data_outlet');
                });

                Route::prefix('zone')->group(function(){
                    Route::get('/index', 'ZoneController@index');
                    Route::get('/export_data', 'ZoneController@export_data_zone');
                });

                Route::prefix('hour')->group(function(){
                    Route::get('/index', 'HourController@index');
                    Route::get('/export_data', 'HourController@export_data_hour');
                });

                Route::prefix('sku')->group(function(){
                    Route::get('/index', 'SkuController@index');
                    Route::get('/export_data', 'SkuController@export_data_sku');
                });

                
                Route::get('/zone_report', 'IndexController@zone_report');
                Route::get('/hourly_report', 'IndexController@hourly_report');


                Route::get('/get_zone', 'IndexController@get_zone');

                 // Common
                Route::get('/product_sale_items', 'CommonReportController@product_sale_items');
                Route::get('/get_all_zone', 'CommonReportController@get_all_zone');
                Route::get('/shop', 'CommonReportController@shop');
                
                
            });


            // settings
            Route::middleware('administration')->namespace('Setting')->group(function(){

                // schedule_timer
                Route::prefix('schedule_timer')->group(function(){
                    Route::get('/index', 'ScheduleTimerController@index');
                    Route::post('/store', 'ScheduleTimerController@store');
                    Route::put('/update/{id}', 'ScheduleTimerController@update');
                    Route::post('/status/{id}', 'ScheduleTimerController@status');
                });


                // Table data transfer
                Route::prefix('transfer_data')->group(function(){
                    Route::get('/table_name', 'TransferDataController@table_name');
                    Route::post('/table_transfer', 'TransferDataController@table_transfer');
                });

            });

            

            Route::get('{any?}', 'IndexController@index')->name('admin.dashboard');
        });

        // Route::get('/test', 'CommonController@test');

        

        // Owner 
        Route::middleware('redirect:Owner')->namespace('Owner')->prefix('owner')->group(function(){

            Route::get('/dashboard_data', 'IndexController@dashboard_data');

            // announcement
            Route::prefix('announcement')->group(function(){
                Route::get('/get_announcement', 'IndexController@get_announcement');
            });

            Route::namespace('Report')->prefix('report')->group(function(){
                Route::get('/index', 'IndexController@index');
                Route::get('/get_all_shop', 'IndexController@get_all_shop');
                Route::get('/export_data', 'IndexController@export_data');
                Route::get('/shop', 'IndexController@shop');
                
            });

            Route::namespace('Profile')->prefix('view-profile')->group(function(){
                Route::get('/index', 'IndexController@index');
                Route::get('/shop', 'IndexController@shop');
            });

            Route::namespace('Reset')->prefix('reset')->group(function(){
                Route::post('/index', 'IndexController@index');
                
            });

            // stock
            Route::namespace('Stock')->prefix('stock')->group(function(){
                Route::get('/index', 'IndexController@index');
            });


            Route::get('{any?}', 'IndexController@index')->name('owner.dashboard');
        });


        // Operator
        Route::middleware('redirect:Operator')->namespace('Operator')->group(function(){

            // announcement
            Route::prefix('announcement')->group(function(){
                Route::get('/get_announcement', 'IndexController@get_announcement');
            });

            Route::namespace('Item')->prefix('item')->group(function(){
                Route::get('/index', 'IndexController@index');
                Route::get('/sales_type', 'IndexController@sales_type');
                Route::post('/store', 'IndexController@store');
                Route::put('/update/{id}', 'IndexController@update');
                Route::delete('/destroy/{id}', 'IndexController@destroy');
                Route::delete('/delete_temp/{id}', 'IndexController@delete_temp');
                Route::post('/status/{id}', 'IndexController@status');
                Route::get('/order_type', 'IndexController@order_type');
            });

            Route::namespace('Modify')->prefix('modify')->group(function(){
                Route::get('/index', 'IndexController@index');
                Route::put('/update/{id}', 'IndexController@update');
                Route::post('/order_details', 'IndexController@order_details');
                Route::post('/order_items', 'IndexController@order_items');

                Route::get('/quick_search', 'IndexController@quick_search');
            });

            Route::namespace('Sale')->prefix('sale')->group(function(){
                Route::post('/store', 'IndexController@store');
                Route::put('/update', 'IndexController@update');
            });

            Route::namespace('Shop')->prefix('shop')->group(function(){
                Route::get('/index', 'IndexController@index');
                Route::post('/update', 'IndexController@update');
                
            });

            Route::namespace('Reset')->prefix('reset')->group(function(){
                Route::post('/reset', 'IndexController@index');
                
            });

            Route::namespace('Order')->prefix('order')->group(function(){
                Route::get('/index', 'IndexController@index');
                Route::post('/store', 'IndexController@store');
                Route::post('/update', 'IndexController@update');
                Route::post('/order_details', 'IndexController@order_details');
                Route::post('/get_previous', 'IndexController@get_previous');
                Route::get('/food_item_with_modify_order', 'IndexController@food_item_with_modify_order');
                Route::get('/food_type', 'IndexController@food_type');
                Route::get('/food_item_for_order', 'IndexController@food_item_for_order');


                
                Route ::prefix('payment')->group(function(){
                    Route::post('/store', 'PaymentController@store');
                });
                
            });

            // stock
            Route::namespace('Stock')->prefix('stock')->group(function(){
                Route::get('/index', 'IndexController@index');
            });

            Route::namespace('Report')->prefix('report')->group(function(){
                Route::get('/index', 'IndexController@index');
                Route::get('/export_data', 'IndexController@export_data');

            });

            // setLanguage
            Route::post('/setLanguage/{lang}', 'IndexController@setLanguage');
            
            Route::get('{any?}', 'IndexController@index')->name('dashboard');
        });


        

    });
    // food End
 
    

});