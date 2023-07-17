<?php

use App\Http\Controllers\AddonController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AizUploadController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\CrmReport;
use App\Models\Quotation;
use App\Addons\MultiVendor\Http\Controllers\MultiVendorController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmailSystemController;
use App\Http\Controllers\SmsPanelController;
use App\Http\Controllers\CustomerLogController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CRMController;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::post('/update', [UpdateController::class, 'step0'])->name('update');
Route::get('/update/step1', [UpdateController::class, 'step1'])->name('update.step1');
Route::get('/update/step2', [UpdateController::class, 'step2'])->name('update.step2');
Route::get('/convert_for_update', [UpdateController::class, 'convertForMultivendor']);

Route::get('/refresh-csrf', function () {
    return csrf_token();
});

Route::get('/clear-cache-all', function() {
    Artisan::call('cache:clear');
    dd("Cache Clear All");

});

// Route::get('/refresh-rrr/3', function () {
//      return view('backend.product.quotation.check');
//     //dd(Quotation::all());
//});

Route::post('/aiz-uploader', [AizUploadController::class, 'show_uploader']);
Route::post('/aiz-uploader/upload', [AizUploadController::class, 'upload']);
Route::get('/aiz-uploader/get_uploaded_files', [AizUploadController::class, 'get_uploaded_files']);
Route::delete('/aiz-uploader/destroy/{id}', [AizUploadController::class, 'destroy']);
Route::post('/aiz-uploader/get_file_by_ids', [AizUploadController::class, 'get_preview_files']);
Route::get('/aiz-uploader/download/{id}', [AizUploadController::class, 'attachment_download'])->name('download_attachment');


Route::get('/demo/cron_1', [DemoController::class, 'cron_1']);
Route::get('/demo/cron_2', [DemoController::class, 'cron_2']);
Route::get('/insert_trasnalation_keys', [DemoController::class, 'insert_trasnalation_keys']);
Route::get('/customer-products/admin', [SettingController::class, 'initSetting']);

Auth::routes(['register' => false]);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/quotation/list/details/{quotation_number}', [CategoryController::class, 'quotation_list_details'])->name('quotation.list.details');
Route::get('/quotationc/list/details/{quotation_number}', [CategoryController::class, 'quotation_list_detailsc'])->name('quotationc.list.details');
Route::get('/quotationc/list/liflet/{quotation_number}', [CategoryController::class, 'quotation_list_liflet'])->name('quotationc.list.liflet');
Route::get('/quotation/list/prayer/{quotation_number}', [CategoryController::class, 'prayer_view_quotation'])->name('quotation.list.prayer.view');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {

    Route::get('/', [AdminController::class, 'admin_dashboard'])->name('admin.dashboard');

    Route::post('/language', [LanguageController::class, 'changeLanguage'])->name('language.change');

    Route::resource('categories', CategoryController::class);
    Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::get('/categories/destroy/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/featured', [CategoryController::class, 'updateFeatured'])->name('categories.featured');


    //quotation routes start
    Route::get('/quotation/home', [CategoryController::class, 'quotation_create'])->name('quotation.home');
    Route::get('/quotation/list', [CategoryController::class, 'quotation_list'])->name('quotation.list');
    Route::get('/quotation/list/delete/{quotation_number}', [CategoryController::class, 'delete_quotation'])->name('quotation.list.delete');
    Route::get('/quotation/list/edit/{quotation_number}', [CategoryController::class, 'edit_quotation'])->name('quotation.list.edit');
    Route::get('/quotation/search', [CategoryController::class, 'product_search'])->name('quotation.search');
    Route::post('/quotation/store', [CategoryController::class, 'storeQuotaiton'])->name('quotation.storeQuotaiton');
    Route::get('/quotation/list/details/{quotation_number}', [CategoryController::class, 'quotation_list_details'])->name('quotation.list.details');
    Route::get('/quotation/list/duplicate/{quotation_number}', [CategoryController::class, 'duplicate_quotation'])->name('quotation.list.duplicate');
    
    
    //quotation routes end

    //pos route start
    Route::get('/pos/dashboard',[CategoryController::class,'pos_dashboard'])->name('pos.dashboard');
    Route::get('/pos/search',[CategoryController::class,'pos_search'])->name('pos.search'); //ajax path
    Route::get('/pos/customer_search',[CategoryController::class,'customer_search'])->name('pos.customer_search');
    Route::get('/pos/staff_search',[CategoryController::class,'staff_search'])->name('pos.staff_search');
    Route::post('/pos_sys/customer_store',[CategoryController::class,'customer_store'])->name('pos.customer_store');
    Route::get('/pos_sys/product_select_search',[CategoryController::class,'product_select_search'])->name('pos.product_select_search');
    Route::post('/pos_sys/create_order',[CategoryController::class,'create_order'])->name('pos.create_order');

    //pos route end
    Route::post('/pos_sys/match_inventory_product',[CategoryController::class,'match_inventory_product'])->name('pos.match_inventory_product');
    Route::get('/pos_sys/serial_search',[CategoryController::class,'match_product_serial'])->name('pos.serial_search');
    Route::get('/pos_sys/manual_barcode_search',[CategoryController::class,'match_product_barcode_manually'])->name('pos.barocode_search_manually');
   

    //inventory route start
    Route::get('pos_sys/inventory/home',[CategoryController::class,'inventory_index'])->name('pos.inventory.home');
    Route::post('pos_sys/inventory/check_duplicate',[CategoryController::class,'check_duplicate_product'])->name('pos.inventory.check_duplicate_product');
    Route::post('pos_sys/inventory/store',[CategoryController::class,'store_inventory_data'])->name('pos.inventory.store');
    Route::get('/pos/supplier_search',[CategoryController::class,'supplier_search'])->name('pos.inventory.supplier_search');
    Route::post('/pos/supplier_store',[CategoryController::class,'supplier_information_store'])->name('pos.inventory.supplier_store');
    //inventory route end

    //purchase return product start
    Route::get('inventory/purchase_return_product',[CategoryController::class,'purhcase_return_product'])->name('purchase.return.product');
    Route::post('inventory/purchase_return_product_store',[CategoryController::class,'purhcase_return_product_store'])->name('purchase.return.product.store');
    Route::get('inventory/purchase_return_product_list',[CategoryController::class,'purhcase_return_product_list'])->name('purchase.return.product.list');
    Route::get('inventory/purchase_return_product_details/{purchase_invoices}',[CategoryController::class,'purhcase_return_product_details'])->name('purchase.return.product.details');
    Route::get('inventory/purchase_return_product_delete/{purchase_invoices}',[CategoryController::class,'purhcase_return_product_delete'])->name('purchase.return.product.delete');
    //purchase return product end



     //delivery booking start
     Route::get('/booking',[BookingController::class,'index'])->name('booking');
     Route::post('/booking',[BookingController::class,'store'])->name('booking');
     Route::get('/booking_list',[BookingController::class,'bookList'])->name('booking_list');
     Route::get('/edit/{i}',[BookingController::class,'edit'])->name('edit');
     Route::post('/update/{id}',[BookingController::class,'updatedata']);
     Route::post('delete/{id}', [BookingController::class,'delete'])->name('delete');
     Route::post('/book/search', [BookingController::class, 'Book_search'])->name('book.search'); //ajax path

     //test ajax
     Route::POST('/ajaxData',[BookingController::class,'getUserData']);
     Route::resource('/userData',BoookingController::class);

     //delivery booking end

    // Report Route Start
    Route::get('/summary_report',[CategoryController::class,'summary_report'])->name('summary.report');
    Route::post('/summary_report/bydate',[CategoryController::class,'summary_report_by_date'])->name('summary.report.bydate');
    Route::post('/summary_report/bymonth',[CategoryController::class,'summary_report_by_month'])->name('summary.report.bymonth');
    Route::post('/summary_report/byyear',[CategoryController::class,'summary_report_by_year'])->name('summary.report.byyear');
    Route::get('/product_sale_report',[CategoryController::class,'product_sale_report'])->name('product_sale.report');
    Route::post('/product_sale_report_date_Wise',[CategoryController::class,'product_sale_report_report_wise'])->name('product_sale.report_date_wise');
    Route::get('/stock_report',[CategoryController::class,'stock_report'])->name('stock_report');
    Route::get('/cash_report',[CategoryController::class,'cash_report'])->name('cash_report');
    Route::get('/metrics_report',[CategoryController::class,'metrics_report'])->name('metrics_report');
    Route::get('/product_report_by_barcode',[CategoryController::class,'product_report_by_barocode'])->name('product.report_by_barocde');
    Route::post('/product_report_by_barcode/match_serial',[CategoryController::class,'product_report_by_barocode_match_serial'])->name('report_by_barocde.match_serial');
    // Report Route End

    //Employee Manage Route start
    Route::get('/employee_manage/attendance_generate_report_view',[EmployeeController::class,'attendence_report_generate_view'])->name('employee.attendance.report_generate_view');
    Route::post('/employee_manage/attendance_generate_report_store',[EmployeeController::class,'attendence_report_generate_excell_store'])->name('employee.attendance.report_generate_store');
    Route::get('/employee_manage/attendance_generate_report',[EmployeeController::class,'attendence_report_generate'])->name('employee.attendance.report_generate');
    Route::post('/employee_manage/store_attendance_report_info',[EmployeeController::class,'store_attendence_report_info'])->name('employee.attendance.store_info');
    Route::get('/employee_manage/attendance_list',[EmployeeController::class,'employee_attendence_list'])->name('employee.attendance.list');
    Route::get('/employee_manage/attendance_print/{attendance_id}',[EmployeeController::class,'employee_attendence_print'])->name('employee.attendance.print');
    Route::get('/employee_manage/attendance_edit/{attendance_id}',[EmployeeController::class,'employee_attendence_edit'])->name('employee.attendance.edit');
    Route::post('/employee_manage/attendance_update/{attendance_id}',[EmployeeController::class,'employee_attendence_update'])->name('employee.attendance.update');
    Route::get('/employee_manage/attendance_delete/{attendance_id}',[EmployeeController::class,'employee_attendence_delete'])->name('employee.attendance.delete');
    Route::get('/employee_manage/automate_attendance',[EmployeeController::class,'automate_attendance'])->name('employee.automate_attendance');
    Route::post('/employee_manage/automate_attendance_generate',[EmployeeController::class,'automate_attendance_generate'])->name('employee.automate_attendance_generate');
    Route::get('/employee_manage/automate_attendance_show',[EmployeeController::class,'automate_attendance_show'])->name('employee.automate_attendance_show');
    Route::get('/employee_manage/automate_attendance_list',[EmployeeController::class,'automate_attendance_list'])->name('employee.automate_attendance.list');
    Route::get('/employee_manage/automate_attendance_edit/{user_id}/{year}/{month}',[EmployeeController::class,'automate_attendance_edit'])->name('employee.automate_attendance.edit');
    Route::get('/employee_manage/automate_attendance_delete/{user_id}/{year}/{month}',[EmployeeController::class,'automate_attendance_delete'])->name('employee.automate_attendance.delete');
    Route::post('/employee_manage/automate_attendance_store_edited_in_out_time_data',[EmployeeController::class,'automate_attendance_store_edited_in_out_time_data'])->name('employee.automate_attendance.store_edited_in_out_time_data');
    Route::get('/employee_manage/automate_attendance_generate_salary/{user_id}/{year}/{month}',[EmployeeController::class,'automate_attendance_generate_salary'])->name('employee.automate_attendance.generate_salary');
    Route::post('/employee_manage/automate_attendance_generate_salary_store',[EmployeeController::class,'automate_attendance_generate_salary_store'])->name('employee.automate_attendance.generate_salary.store');
    Route::get('/employee_manage/automate_attendance_print/{user_id}/{year}/{month}',[EmployeeController::class,'automate_attendance_print'])->name('employee.automate_attendance.print');
    Route::get('/employee_manage/employee_panel_list',[EmployeeController::class,'employee_panel_list'])->name('employee.employee_panel.list');
    Route::get('/employee_manage/employee_panel_edit/{id}',[EmployeeController::class,'employee_panel_edit'])->name('employee.employee_panel.edit');
    Route::post('/employee_manage/employee_panel_update',[EmployeeController::class,'employee_panel_update'])->name('employee.employee_panel.update');
    Route::get('/employee_manage/employee_current_attendance_data',[EmployeeController::class,'employee_current_attendance_data'])->name('employee.employee_current_attendance.data');
    Route::get('/employee_manage/employee_current_attendance_details/{user_id}/{year}/{month}',[EmployeeController::class,'employee_current_attendance_details'])->name('employee.automate_current_attendance.details');

    //Manage Payslip
    Route::get('/manage_payslip/create/{user_id}/{year}/{month}',[EmployeeController::class,'payslip_create'])->name('payslip.create');
    Route::get('/manage_payslip/edit/{user_id}/{year}/{month}',[EmployeeController::class,'payslip_edit'])->name('payslip.edit');
    Route::post('/manage_payslip/store',[EmployeeController::class,'payslip_store'])->name('payslip.store');
    Route::post('/manage_payslip/store/{user_id}/{year}/{month}',[EmployeeController::class,'payslip_update'])->name('payslip.update');
    Route::get('/manage_payslip/print/{user_id}/{year}/{month}',[EmployeeController::class,'payslip_print'])->name('payslip.print');

    Route::get('/udpate_attendance_data_to_server',[EmployeeController::class,'udpate_attendance_data']);
    //Employee Manage Route End

    //Email system Panel start
    Route::get('/email_system/email_system_view',[EmailSystemController::class,'email_system_view'])->name('email_system.view');
    Route::post('/email_system/user_email_info_store',[EmailSystemController::class,'user_email_info_store'])->name('email_system.user_email_info.store');
    Route::get('/email_system/user_email_info_list',[EmailSystemController::class,'user_email_info_list'])->name('email_system.user_email_info.list');
    Route::post('/email_system/sent_single_mail',[EmailSystemController::class,'send_single_mail'])->name('email_system.user_email_info.singlemail');
    Route::get('/email_system/single_mail_write_body/{customer_email}',[EmailSystemController::class,'send_single_mail_write_body'])->name('email_system.singlemail_write_body');
    Route::post('/email_system/save_temporary_email',[EmailSystemController::class,'save_temporary_email'])->name('email_system.save_temporary_email');
    Route::get('/email_system/multiple_mail_write_body',[EmailSystemController::class,'multiple_mail_write_body'])->name('email_system.multiple_write_body');
    Route::post('/email_system/sent_multiple_mail',[EmailSystemController::class,'send_multiple_mail'])->name('email_system.send_multiple_email');
    Route::get('/email_system/template_mail_body/{customer_email}',[EmailSystemController::class,'template_single_mail_body'])->name('email_system.template_single_body');
    Route::post('/email_system/send_template_mail_body',[EmailSystemController::class,'send_template_single_mail_body'])->name('email_system.send_template_single_body');
    Route::get('/email_system/choose_mail_type_body',[EmailSystemController::class,'choose_email_type_body'])->name('email_system.choose_email_type');
    Route::get('/email_system/multiple_template_mail_write_body',[EmailSystemController::class,'multiple_template_mail_write_body'])->name('email_system.multiple_template_mail_write_body');
    Route::post('/email_system/send_multiple_template_mail',[EmailSystemController::class,'send_multiple_template_mail'])->name('email_system.send_multiple_template_mail');

    //Email system panel end

    //SMS system panel start
     Route::get('/sms_system/sms_system_view',[SmsPanelController::class,'customer_information_list_sms_view'])->name('customer_info_list_sms.view');
     Route::get('/sms_system/single_sms_body/{customer_mobile_number}',[SmsPanelController::class,'single_sms_body_view'])->name('single_sms_body.view');
     Route::post('/sms_system/send_single_sms',[SmsPanelController::class,'send_signle_sms'])->name('single_sms.send');
     Route::get('/sms_system/choose_sms_receiver',[SmsPanelController::class,'choose_sms_receiver_view'])->name('choose_sms_receiver.view');
     Route::post('/sms_system/store_temporary_sms_number',[SmsPanelController::class,'store_temporary_sms_number'])->name('temporary_sms_number.store');
     Route::post('/sms_system/send_multiple_sms',[SmsPanelController::class,'send_multiple_sms'])->name('multiple_sms.send');
     Route::get('/sms_system/mulitple_sms_body',[SmsPanelController::class,'send_multiple_sms_body_view'])->name('multiple_sms_body.view');
     Route::get('/sms_system/sms_sending_report',[SmsPanelController::class,'sms_sending_report'])->name('sms_sending.report');
     Route::post('/sms_system/sms_sending_date_wise_report',[SmsPanelController::class,'sms_sending_date_wise_report'])->name('sms_sending_report.date_wise');
     Route::post('/sms_system/sms_sending_month_wise_report',[SmsPanelController::class,'sms_sending_month_wise_report'])->name('sms_sending_report.month_wise');
   
    //SMS system panel end



    Route::resource('brands', BrandController::class);
    Route::get('/brands/edit/{id}', [BrandController::class, 'edit'])->name('brands.edit');
    Route::get('/brands/destroy/{id}', [BrandController::class, 'destroy'])->name('brands.destroy');

    Route::resource('attributes', AttributeController::class)->except(['destroy']);
    Route::get('/attributes/edit/{id}', [AttributeController::class, 'edit'])->name('attributes.edit');

    Route::resource('attribute_values', AttributeValueController::class)->except(['destroy']);;
    Route::get('/attribute_values/edit/{id}', [AttributeValueController::class, 'edit'])->name('attribute_values.edit');




    // Product
    Route::resource('/product', ProductController::class);
    //wastage products routes
    Route::get('/wastage', [ProductController::class, 'wastage_home'])->name('product.wastage_home');
    Route::get('/wastage/show_list', [ProductController::class, 'wastage_list'])->name('product.wastage_list');
    Route::get('/wastage/show_details/{id}', [ProductController::class, 'wastage_details'])->name('product.wastage_details');
    Route::post('/wastage/store', [ProductController::class, 'store_wastage_product'])->name('product.wastage_store');
    
    Route::group(['prefix' => 'product'], function () {
        Route::post('/new-attribte', [ProductController::class, 'new_attribute'])->name('product.new_attribute');
        Route::post('/get-attribte-value', [ProductController::class, 'get_attribute_values'])->name('product.get_attribute_values');
        Route::post('/new-option', [ProductController::class, 'new_option'])->name('product.new_option');
        Route::post('/get-option-choices', [ProductController::class, 'get_option_choices'])->name('product.get_option_choices');

        Route::post('/sku-combination', [ProductController::class, 'sku_combination'])->name('product.sku_combination');

        Route::get('/{id}/barcode', [ProductController::class, 'generate_barcode'])->name('product.barcode');
        Route::post('/create/barcode', [ProductController::class, 'create_barcode'])->name('product.create_barcode');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::get('/duplicate/{id}', [ProductController::class, 'duplicate'])->name('product.duplicate');
        Route::post('/update/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::post('/published', [ProductController::class, 'updatePublished'])->name('product.published');
        Route::get('/destroy/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
        Route::get('/product_search/{search_item}', [ProductController::class, 'product_search'])->name('product.search');

        Route::post('/get_products_by_subcategory', [ProductController::class, 'get_products_by_subcategory'])->name('product.get_products_by_subcategory');
        
    });

        //======== CRM Routes Start by Repon ========
        Route::get('/customerlog/crm/proposal',[CustomerLogController::class,'customer_log_details_crm_proposal'])->name("customer_log_details_crm.proposal");
        Route::post('/customerlog/crm/proposal/store',[CustomerLogController::class,'customer_log_details_crm_proposal_store'])->name("customer_log_details_crm.proposal.store");
        Route::get('/customerlog/crm/proposal/view',[CustomerLogController::class,'customer_log_details_crm_proposal_view'])->name("customer_log_details_crm.proposal.view");
        Route::post('/customerlog/crm/proposal/changestatus',[CustomerLogController::class,'customer_log_details_crm_proposal_changestatus'])->name("customer_log_details_crm.proposal.changestatus");
        Route::get('/customerlog/crm/proposal/delete',[CustomerLogController::class,'customer_log_details_crm_proposal_delete'])->name("customer_log_details_crm.proposal.delete");
        Route::get('/customerlog/crm/customer/view/{id}',[CustomerLogController::class,'customer_log_details_crm_customer_view'])->name("customer_log_details_crm_customer.view");
        Route::get('/customerlog/crm/product/search',[CustomerLogController::class,'customer_log_details_crm_product_search'])->name("customer_log_details_crm_product.search");
        Route::get('/customer_feedback/crm/list',[CRMController::class,'customer_feedback_crm_list'])->name("customer_feedback_crm.list");
        Route::get('/customer_feedback/crm/search/bydate',[CRMController::class,'customer_feedback_crm_search_by_date'])->name("customer_feedback_crm.search_by_date");
        Route::get('/customer_feedback/crm/edit/{customer_id}',[CRMController::class,'customer_crm_feedback_edit'])->name("customer_crm_feedback.edit_view");
        Route::post('/customer_feedback/crm/update/{customer_id}',[CRMController::class,'customer_crm_feedback_update'])->name("customer_crm_feedback.update");
        Route::post('/customer_feedback/crm/customer_product_interest_search',[CRMController::class,'customer_product_interest_search'])->name("customer_product_interest_search");
        Route::get('/customer_feedback/crm/list/view',[CRMController::class,'customer_feedback_crm_list_viwe_each'])->name("customer_feedback_crm.view_each");
        Route::post('/customer_feedback/crm/list/edit',[CRMController::class,'customer_feedback_crm_list_edit_each'])->name("customer_feedback_crm.edit_each");


        
       

        // CRM Part
        Route::post('/crm_add_comment',[CRMController::class,'crm_add_comment'])->name("customer_crm.add_comment");
        Route::get('/crm_view_comment',[CRMController::class,'crm_view_comment'])->name("customer_crm.view_comment");
        Route::get('/check_customer_id_crm_manage',[CRMController::class,'check_customer_id_crm_manage'])->name("customer_crm.check_customer");
        Route::post('/crm_add_reminder',[CRMController::class,'crm_add_reminder'])->name("customer_crm.add_reminder");
        Route::get('/crm_product_search',[CRMController::class,'crm_product_search'])->name("customer_crm.product_search");
        Route::GET('/crm_getting_product_name',[CRMController::class,'crm_getting_product_name'])->name("customer_crm.getting_product_name");
        Route::GET('/crm_getting_product_name_modal',[CRMController::class,'crm_getting_product_name_modal'])->name("customer_crm.getting_product_name_modal");
        Route::GET('/crm_getting_comments_modal',[CRMController::class,'crm_getting_comments_modal'])->name("customer_crm.getting_comments_modal");

        Route::get('/crm_user_search',[CRMController::class,'crm_user_search'])->name("customer_crm.user_search");
        Route::get('/crm_user_search/search/bydate',[CRMController::class,'customer_crm_search_by_date'])->name("customer_crm.search_by_date");
        Route::get('/marketing_followup/commented_clients_view',[CRMController::class,'marketing_followup_commented_clients_view'])->name("marketing_followup.commented_clients");
        Route::get('/marketing_followup/comments_view',[CRMController::class,'marketing_followup_comments_view'])->name("marketing_followup.comments");
        Route::get('/marketing_followup/reminders_view',[CRMController::class,'marketing_followup_reminders_view'])->name("marketing_followup.reminders");
        Route::get('/marketing_followup/getting_data_view_modal',[CRMController::class,'getting_data_view_modal'])->name("marketing_followup.getting_data_view_modal");
        Route::post('/marketing_followup/delete_comments',[CRMController::class,'delete_comments'])->name("marketing_followup.delete_comments");
        Route::get('/marketing_followup/getting_comments_view',[CRMController::class,'getting_comments_view'])->name("marketing_followup.getting_comments_view");
        Route::post('/marketing_followup/update_comments',[CRMController::class,'update_comments'])->name("marketing_followup.update_comments");
        Route::post('/marketing_followup/add_comment',[CRMController::class,'add_comment_from_modal'])->name("marketing_followup.add_comment");
        Route::get('/marketing_followup/customer_profile_view',[CRMController::class,'customer_profile_view'])->name("marketing_followup.customer_profile_view");
        Route::get('/marketing_followup/getting_reminder_data',[CRMController::class,'getting_reminder_data'])->name("marketing_followup.getting_reminder_data");
        Route::post('/marketing_followup/update_reminder',[CRMController::class,'update_reminder'])->name("marketing_followup.update_reminder");
        Route::post('/marketing_followup/delete_reminder',[CRMController::class,'delete_reminder'])->name("marketing_followup.delete_reminder");
        Route::get('/marketing_followup/view_reminder',[CRMController::class,'view_reminder'])->name("marketing_followup.view_reminder");
        
        //======== CRM Routes End ========

        //======== CRM Report Routes Start ========

        Route::get('/crm_report',[CrmReport::class,'index'])->name("crm_report.view");
        Route::post('/crm_add_report',[CrmReport::class,'crm_add_report'])->name("crm_report.add_report");
        Route::get('/crm_report_view',[CrmReport::class,'crm_report_view'])->name("crm_report.view_all");
        Route::get('/crm_report_edit',[CrmReport::class,'crm_report_edit'])->name("crm_report.edit");
        Route::post('/crm_report_delete',[CrmReport::class,'crm_report_delete'])->name("crm_report.delete");
        Route::post('/crm_report_update',[CrmReport::class,'crm_report_update'])->name("crm_report.update_report");
        Route::get('/crm_report_print',[CrmReport::class,'crm_report_print'])->name("crm_report.print_report");
        Route::get('/crm_report_print_view',[CrmReport::class,'crm_report_print_view'])->name("crm_report.print_view");
        Route::get('/user_name_view',[CrmReport::class,'user_name_view'])->name("crm_report.user_name_view");

        //======== CRM Report Routes end ========

         //======== CRM Routes start by yeasin ========
         Route::get('/customer_marketing/crm/list',[CRMController::class,'customer_marketing_crm_list'])->name("customer_marketing_crm.list");
         Route::post('/customer_marketing/crm/add_customer',[CRMController::class,'customer_marketing_crm_add_customer'])->name("customer_marketing_crm.add_customer");
         Route::get('/customer_marketing/crm/add_status_view/{customer_id}',[CRMController::class,'customer_marketing_crm_add_status_view'])->name("customer_marketing_crm.add_status_view");
         Route::post('/customer_marketing/crm/add_status_store/{customer_id}',[CRMController::class,'customer_marketing_crm_add_status_store'])->name("customer_marketing_crm.add_status_store");
         //======== CRM Routes End by yeasin ========

        //======== CRM Report Routes Start by Repon ========
        Route::post('/customer_profile_update',[CustomerController::class,'customer_profile_update'])->name("customer_profile.update");
        Route::post('/customer_profile_document_update',[CustomerController::class,'customer_document_update'])->name("customer_profile.document_update");
        Route::post('/customer_profile_phone_update',[CustomerController::class,'customer_phone_update'])->name("customer_profile.phone_update");
        Route::post('/customer_profile_reference_update',[CustomerController::class,'customer_reference_update'])->name("customer_profile.reference_update");
        Route::post('/customer_profile_bank_info_update',[CustomerController::class,'customer_bank_info_update'])->name("customer_profile.bank_info_update");
        Route::post('/customer_profile_address_update',[CustomerController::class,'customer_address_update'])->name("customer_profile.address_update");
        Route::post('/customer_profile_comment_update',[CustomerController::class,'customer_comment_update'])->name("customer_profile.comment_update");
        Route::get('/customer_profile_reminder_view',[CustomerController::class,'customer_reminder_view'])->name("customer_profile.reminder_view");
        Route::post('/customer_profile_reminder_update',[CustomerController::class,'customer_reminder_update'])->name("customer_profile.reminder_update");
        Route::post('/customer_profile_assignto_add',[CustomerController::class,'customer_assignto_add'])->name("customer_profile.add_assignto");
        Route::get('/customer_profile_assignto_view',[CustomerController::class,'customer_assignto_view'])->name("customer_profile.assignto_view");
        Route::get('/customer_profile_edit_assignto',[CustomerController::class,'customer_edit_assignto'])->name("customer_profile.edit_assignto");
        Route::post('/customer_profile_delete_assignto',[CustomerController::class,'customer_delete_assignto'])->name("customer_profile.delete_assignto");
        Route::post('/customer_profile_update_assignto',[CustomerController::class,'customer_update_assignto'])->name("customer_profile.update_assignto");
        Route::get('/getUserName',[CustomerController::class,'getUserName'])->name("get_user_name");



        //======== CRM Report Routes End by Repon ========

        //======== Supplier Routes Start ========
        Route::get('/supplier/manage/view', [SupplierController::class, 'supplier_manage_view'])->name('supplier_manage.view');
        Route::get("/supplier/manage/viewsupplier",[SupplierController::class,"supplier_manage_modal_show"])->name('get_specefic_supplier.data');
        Route::get("/supplier/manage/editsupplier",[SupplierController::class,"supplier_manage_modal_edit"])->name('get_specefic_supplier_edit.data');
        Route::post('/supplier/manage/update',[SupplierController::class,'supplier_manage_modal_update'])->name("update_specefic_supplier.data");
        Route::post("/supplier/manage/delete",[SupplierController::class,"supplier_manage_modal_delete"])->name('delete_specefic_supplier.data');
        //======== Supplier Routes End ========

    Route::resource('customers', CustomerController::class);
    Route::get('customers_ban/{customer}', [CustomerController::class, 'ban'])->name('customers.ban');
    Route::get('/customers/login/{id}', [CustomerController::class, 'login'])->name('customers.login');
    Route::get('/customers/destroy/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    Route::get('/newsletter', [NewsletterController::class, 'index'])->name('newsletters.index');
    Route::post('/newsletter/send', [NewsletterController::class, 'send'])->name('newsletters.send');
    Route::post('/newsletter/test/smtp', [NewsletterController::class, 'testEmail'])->name('test.smtp');

    Route::resource('profile', ProfileController::class);

    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/update/activation', [SettingController::class, 'updateActivationSettings'])->name('settings.update.activation');
    Route::get('/general-setting', [SettingController::class, 'general_setting'])->name('general_setting.index');
    Route::get('/payment-method', [SettingController::class, 'payment_method'])->name('payment_method.index');
    Route::get('/file_system', [SettingController::class, 'file_system'])->name('file_system.index');
    Route::get('/social-login', [SettingController::class, 'social_login'])->name('social_login.index');
    Route::get('/smtp-settings', [SettingController::class, 'smtp_settings'])->name('smtp_settings.index');
    Route::post('/env_key_update', [SettingController::class, 'env_key_update'])->name('env_key_update.update');
    Route::post('/payment_method_update', [SettingController::class, 'payment_method_update'])->name('payment_method.update');

    Route::get('/third-party-settings', [SettingController::class, 'third_party_settings'])->name('third_party_settings.index');
    Route::post('/google_analytics', [SettingController::class, 'google_analytics_update'])->name('google_analytics.update');
    Route::post('/google_recaptcha', [SettingController::class, 'google_recaptcha_update'])->name('google_recaptcha.update');
    Route::post('/facebook_chat', [SettingController::class, 'facebook_chat_update'])->name('facebook_chat.update');
    Route::post('/facebook_pixel', [SettingController::class, 'facebook_pixel_update'])->name('facebook_pixel.update');

    // Currency
    Route::get('/currency', [CurrencyController::class, 'index'])->name('currency.index');
    Route::post('/currency/update', [CurrencyController::class, 'updateCurrency'])->name('currency.update');
    Route::post('/your-currency/update', [CurrencyController::class, 'updateYourCurrency'])->name('your_currency.update');
    Route::get('/currency/create', [CurrencyController::class, 'create'])->name('currency.create');
    Route::post('/currency/store', [CurrencyController::class, 'store'])->name('currency.store');
    Route::post('/currency/currency_edit', [CurrencyController::class, 'edit'])->name('currency.edit');
    Route::post('/currency/update_status', [CurrencyController::class, 'update_status'])->name('currency.update_status');

    // Language
    Route::resource('/languages', LanguageController::class);
    Route::post('/languages/update_rtl_status', [LanguageController::class, 'update_rtl_status'])->name('languages.update_rtl_status');
    Route::post('/languages/update_language_status', [LanguageController::class, 'update_language_status'])->name('languages.update_language_status');
    Route::get('/languages/destroy/{id}', [LanguageController::class, 'destroy'])->name('languages.destroy');
    Route::post('/languages/key_value_store', [LanguageController::class, 'key_value_store'])->name('languages.key_value_store');

    // website setting
    Route::group(['prefix' => 'website', 'middleware' => ['permission:website_setup']], function () {

        Route::view('/header', 'backend.website_settings.header')->name('website.header');
        Route::view('/footer', 'backend.website_settings.footer')->name('website.footer');
        Route::view('/banners', 'backend.website_settings.banners')->name('website.banners');
        Route::view('/pages', 'backend.website_settings.pages.index')->name('website.pages');
        Route::view('/appearance', 'backend.website_settings.appearance')->name('website.appearance');
        Route::resource('custom-pages', PageController::class);
        Route::get('/custom-pages/edit/{id}', [PageController::class, 'edit'])->name('custom-pages.edit');
        Route::get('/custom-pages/destroy/{id}', [PageController::class, 'destroy'])->name('custom-pages.destroy');
    });

    Route::resource('roles', RoleController::class);
    Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::get('/roles/destroy/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::resource('staffs', StaffController::class);
    Route::get('/staffs/destroy/{id}', [StaffController::class, 'destroy'])->name('staffs.destroy');

    // Offers
    Route::resource('offers', OfferController::class);
    Route::get('/offers/destroy/{id}', [OfferController::class, 'destroy'])->name('offers.destroy');
    Route::post('/offers/update_status', [OfferController::class, 'update_status'])->name('offers.update_status');
    Route::post('/offers/product_discount', [OfferController::class, 'product_discount'])->name('offers.product_discount');
    Route::post('/offers/product_discount_edit', [OfferController::class, 'product_discount_edit'])->name('offers.product_discount_edit');

    //Subscribers
    Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');

    // Orders
    Route::resource('orders', OrderController::class);
    Route::post('/orders/update_delivery_status', [OrderController::class, 'update_delivery_status'])->name('orders.update_delivery_status');
    Route::post('/orders/update_payment_status', [OrderController::class, 'update_payment_status'])->name('orders.update_payment_status');
    Route::get('/orders/destroy/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/orders/invoice/{order_id}', [InvoiceController::class, 'invoice_download'])->name('orders.invoice.download');
    Route::get('/orders/print/{order_id}/{type?}', [InvoiceController::class, 'invoice_print'])->name('orders.invoice.print');
    Route::post('/orders/advance_payment/store', [CategoryController::class, 'store_advance_payment'])->name('orders.advance_payment');
    Route::get('/orders/advance_payment/check', [CategoryController::class, 'get_advance_payment'])->name('orders.advance_payment.check');
    Route::post('/orders/shipment_cost/update', [CategoryController::class, 'update_shipment_cost'])->name('orders.shiment_cost.update');
    Route::get('/orders/shipment_cost/get_shipping_cost', [CategoryController::class, 'get_shipment_cost'])->name('orders.shiment_cost.get');
    Route::post('/orders/update_delivery_status/cancel_cause', [CategoryController::class, 'order_cancel_cause'])->name('orders.update_delivery_status.order_cancel_cause');
    Route::post('/orders/change_payment_status/unpaid', [OrderController::class, 'change_Payment_status_unpaid'])->name('order.change_payment_unpaid');
    Route::post('/orders/change_payment_status/paid', [OrderController::class, 'change_Payment_status_paid'])->name('order.change_payment_paid');
    Route::get('/orders/order_barcode_import/view/{order_id}', [OrderController::class, 'order_barcode_import_view']);
    Route::post('/orders/order_barcode_import/post/{order_id}', [OrderController::class, 'order_barcode_import_post'])->name('order_barcode_import.post');
    Route::post('/orders/print_payment_show', [OrderController::class, 'print_payment_show'])->name('orders.print_payment_show');

    // purchase order invoice
    Route::get('/orders/purchase_order/view', [CategoryController::class, 'purchase_order_home'])->name('orders.purchase_order.home');
    Route::get('/orders/purchase_order/view/{invoice_number}', [CategoryController::class, 'purchase_order_view'])->name('orders.purchase_order.view');
    Route::get('/orders/purchase_order/delete', [CategoryController::class, 'purchase_order_delete'])->name('orders.purchase_order.delete');
    Route::get('/orders/purchase_order/print/{invoice_number}', [CategoryController::class, 'purchase_order_print'])->name('orders.purchase_order.print');
    Route::get('/orders/purchase_order/due_list', [CategoryController::class, 'purchase_due_list'])->name('orders.purchase_order.due_list');
    Route::get('/orders/purchase_order/due_details/{invoice_number}', [CategoryController::class, 'purchase_due_details'])->name('orders.purchase_order.due_details');
    Route::post('/orders/purchase_order/due_payment', [CategoryController::class, 'due_payment'])->name('orders.purchase_order.due_payment');
    Route::get('/orders/purchase_order/supplier_payment_receipt_create/{invoice_number}', [CategoryController::class, 'supplier_payment_receipt_create'])->name('orders.purchase_order.supplier_payment_receipt');
    Route::post('/orders/purchase_order/supplier_payment_receipt_store', [CategoryController::class, 'supplier_payment_receipt_store'])->name('orders.purchase_order.supplier_payment_receipt_store');
    Route::get('/orders/purchase_order/supplier_payment_receipt_edit/{invoice_number}', [CategoryController::class, 'supplier_payment_receipt_edit'])->name('orders.purchase_order.supplier_payment_receipt_edit');
    Route::post('/orders/purchase_order/supplier_payment_receipt_update/{invoice_number}', [CategoryController::class, 'supplier_payment_receipt_update'])->name('orders.purchase_order.supplier_payment_receipt_update');
    Route::get('/orders/purchase_order/supplier_payment_receipt_print/{invoice_number}', [CategoryController::class, 'supplier_payment_receipt_print'])->name('orders.purchase_order.supplier_payment_receipt_print');
    //approved order
    Route::post('/orders/approved_order', [CategoryController::class, 'approved_order'])->name('orders.approved_order');
    //money receipt
    Route::get('/orders/money_receipt_create/{invoice_number}', [CategoryController::class, 'create_money_receipt'])->name('orders.money_receipt.create');
    Route::post('/orders/money_receipt_create/store_money_receipt', [CategoryController::class, 'store_money_receipt'])->name('orders.money_receipt.store');
    Route::post('/orders/money_receipt_create/update_money_receipt/{invoice_number}', [CategoryController::class, 'update_money_receipt'])->name('orders.money_receipt.update');
    Route::get('/orders/money_receipt_create/print_money_receipt/{invoice_number}', [CategoryController::class, 'money_receipt_print'])->name('orders.money_receipt.print');


   //Return order product 
    Route::get('/orders/return_products/back', [OrderController::class, 'return_products'])->name('orders.return_products');
    Route::get('/orders/return_products/specefic_order', [OrderController::class, 'return_products_specefic_data'])->name('orders.return_products_specefic_data');
    Route::post('/orders/return_products/store_return_order', [OrderController::class, 'store_return_product'])->name('orders.store_return_order');
    Route::get('/orders/return_products/list', [OrderController::class, 'return_product_list'])->name('orders.return_product_list');
    Route::get('/orders/return_products/print/{invoice}', [OrderController::class, 'return_product_list_invoices_print'])->name('orders.return_product_list_invoices_print');
    Route::get('/orders/return_products/list/{invoice}', [OrderController::class, 'return_product_list_details'])->name('orders.return_product_list_details');
    Route::get('/orders/return_products/list_delete', [OrderController::class, 'return_product_list_delete'])->name('orders.return_product_list_delete');
    Route::get('/orders/cancel_cause/list', [OrderController::class, 'order_cancel_list'])->name('orders.cancel_list');
    Route::post('/orders/cancel_cause/search_by_date', [OrderController::class, 'order_cancel_search_by_date'])->name('orders.cancel_search_by_date');
    //user history route start
    Route::post('/user_history/order/adv_payment', [CategoryController::class, 'advance_paymnet_history'])->name('user_history.orders.adv_payment');
    Route::post('/user_history/order/payment_status', [CategoryController::class, 'order_payment_status_history'])->name('user_history.orders.payment_status');
    Route::post('/user_history/order/delivery_status', [CategoryController::class, 'order_delivery_status_history'])->name('user_history.orders.delivery_status');
    Route::post('/user_history/order/shipment_cost', [CategoryController::class, 'order_shipment_cost_history'])->name('user_history.orders.shipment_cost');
    Route::get('/user_history/list', [CategoryController::class, 'user_history_list'])->name('user_history.list');
    Route::post('/user_history/list/by_date', [CategoryController::class, 'user_history_list_by_date'])->name('user_history_date.list');
    
    //user edit history within product controller update method
    //user history route end

    //Coupons
    Route::resource('coupon', CouponController::class);
    Route::post('/coupon/get_form', [CouponController::class, 'get_coupon_form'])->name('coupon.get_coupon_form');
    Route::post('/coupon/get_form_edit', [CouponController::class, 'get_coupon_form_edit'])->name('coupon.get_coupon_form_edit');
    Route::get('/coupon/destroy/{id}', [CouponController::class, 'destroy'])->name('coupon.destroy');

    //Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/published', [ReviewController::class, 'updatePublished'])->name('reviews.published');

    Route::any('/uploaded-files/file-info', [AizUploadController::class, 'file_info'])->name('uploaded-files.info');
    Route::resource('/uploaded-files', AizUploadController::class);
    Route::get('/uploaded-files/destroy/{id}', [AizUploadController::class, 'destroy'])->name('uploaded-files.destroy');


    Route::resource('addons', AddonController::class);
    Route::post('/addons/activation', [AddonController::class, 'activation'])->name('addons.activation');

    //Shipping Configuration
    Route::get('/shipping_configuration', [SettingController::class, 'shipping_configuration'])->name('shipping_configuration.index');
    Route::post('/shipping_configuration/update', [SettingController::class, 'shipping_configuration_update'])->name('shipping_configuration.update');

    Route::resource('countries', CountryController::class);
    Route::post('/countries/status', [CountryController::class, 'updateStatus'])->name('countries.status');

    Route::resource('states', StateController::class);
    Route::post('/states/status', [StateController::class, 'updateStatus'])->name('states.status');

    Route::resource('cities', CityController::class);
    Route::get('/cities/edit/{id}', [CityController::class, 'edit'])->name('cities.edit');
    Route::get('/cities/destroy/{id}', [CityController::class, 'destroy'])->name('cities.destroy');
    Route::post('/cities/status', [CityController::class, 'updateStatus'])->name('cities.status');

    Route::resource('zones', ZoneController::class);
    Route::get('/zones/destroy/{id}', [ZoneController::class, 'destroy'])->name('zones.destroy');


    Route::view('/system/update', 'backend.system.update')->middleware('permission:system_update')->name('system_update');
    Route::view('/system/server-status', 'backend.system.server_status')->middleware('permission:server_status')->name('server_status');

    // tax
    Route::resource('taxes', TaxController::class);
    Route::post('/tax/status_update', [TaxController::class, 'updateStatus'])->name('tax.status_update');
    Route::get('/taxes/destroy/{id}', [TaxController::class, 'destroy'])->name('taxes.destroy');

    //chats
    Route::resource('chats', ChatController::class);
    Route::post('/refresh/chats', [ChatController::class, 'refresh'])->name('chats.refresh');
    Route::post('/chat-reply', [ChatController::class, 'reply'])->name('chats.reply');

    Route::get('/update/step1', [UpdateController::class, 'step1']);
});

Route::get('/addons/multivendor', [MultiVendorController::class, 'helloFromMultiVendor']);
