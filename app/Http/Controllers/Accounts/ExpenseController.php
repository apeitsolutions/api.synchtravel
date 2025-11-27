<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\expense\expense;
use App\Models\expense\expenseCategory;
use App\Models\expense\expenseSubCategory;
use App\Models\Accounts\CashAccounts;
use App\Models\Accounts\CashAccountsBal;
use App\Models\Accounts\CashAccountledger;
use DB;
use Auth;
use App\Models\CustomerSubcription\CustomerSubcription;



class ExpenseController extends Controller
{
    
/*
|--------------------------------------------------------------------------
| ExpenseController Function Started
|--------------------------------------------------------------------------
*/    
    
/*
|--------------------------------------------------------------------------
| Index Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to display All Expenses List like expense category,cash account for Our Clients.
*/   
public function index(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->where('status',1)->select('id','status')->first();
        if($userData){
            $allCategories  = expenseCategory::where('customer_id',$userData->id)->get();
            $expense_data   = expense::join('expense_categories','expenses.category_id','expense_categories.id')
                                ->join('expense_sub_categories','expenses.sub_category_id','expense_sub_categories.id')
                                ->join('cash_accounts', 'cash_accounts.id', '=', 'expenses.account_id')
                                ->where('expenses.customer_id',$userData->id)
                                ->select('expenses.*','expense_sub_categories.exp_sub_category','expense_categories.exp_category_name','cash_accounts.name','cash_accounts.account_no')
                                ->orderBy("id",'desc')->get();
                                
            $CashAccounts_data  = DB::table('cash_accounts')->where('customer_id',$userData->id)->get();
            return response()->json([
                'status'            => 'success',
                'allCategories'     => $allCategories,
                'expense_data'      => $expense_data,
                'CashAccounts_data' => $CashAccounts_data,
            ]); 
        }else{
            return response()->json([
                'status'    => 'validation_error',
                'data'      => ''
            ]);
        }
    }
/*
|--------------------------------------------------------------------------
| Index Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Day Book Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to display Day wise Book return view File for Our Clients.
*/ 
public function day_book(){
        $date = date('Y-m-d');
        return view('adminPanel.expense.day_book');

    }
/*
|--------------------------------------------------------------------------
| Day Book Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Day Book Sub Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to display Day wise data for Our Clients.
*/
public function day_book_sub(Request $request){
        $day_book_data = CashAccountledger::whereDate('created_at', $request->date)->get();;
        // print_r($day_book_data);
        $date = $request->date;
        return view('adminPanel.expense.day_book_display',compact('date','day_book_data'));
        
    }
/*
|--------------------------------------------------------------------------
| Day Book Sub Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Expense Print Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Print Expense data for Our Clients.
*/
public function expense_print($id){
        $expense_data = expense::find($id);
        return view('adminPanel.expense.expense_print',compact('expense_data'));
    }
/*
|--------------------------------------------------------------------------
| Expense Print Function Ended
|--------------------------------------------------------------------------
*/    
/*
|--------------------------------------------------------------------------
| Category Wise Expense Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to get Expense data from category wise for Our Clients.
*/    
public function category_wise_expense(Request $request){
        $category_data = expenseCategory::find($request->category_name);

        $expense_data = DB::table('expenses')
                            ->join('expense_categories', 'expense_categories.id', '=', 'expenses.category_id')// joining the contacts table , where user_id and contact_user_id are same
                            ->join('cash_accounts', 'cash_accounts.id', '=', 'expenses.account_id')// joining the contacts table , where user_id and contact_user_id are same
                            ->where('expenses.category_id',$request->category_name)
                            ->select('expenses.*', 'expense_categories.exp_category_name','cash_accounts.account_name','cash_accounts.account_number')
                            ->get();
        // print_r($expense_data);
        // die;
        return view('adminPanel.expense.cateory_wise_expense',compact('expense_data','category_data'));
        
    }
/*
|--------------------------------------------------------------------------
| Category Wise Expense Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Sub Category Wise Expense Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to get Expense data from Sub category wise for Our Clients.
*/ 
public function sub_category_wise_expense(Request $request){
        $sub_category_data = expenseSubCategory::find($request->sub_category_id);

        $expense_data = DB::table('expenses')
                            ->join('expense_categories', 'expense_categories.id', '=', 'expenses.category_id')// joining the contacts table , where user_id and contact_user_id are same
                            ->join('expense_sub_categories', 'expense_sub_categories.id', '=', 'expenses.sub_category_id')// joining the contacts table , where user_id and contact_user_id are same
                            ->join('cash_accounts', 'cash_accounts.id', '=', 'expenses.account_id')// joining the contacts table , where user_id and contact_user_id are same
                            ->where('expenses.sub_category_id',$request->sub_category_id)
                            ->select('expenses.*', 'expense_categories.exp_category_name','cash_accounts.account_name','cash_accounts.account_number','expense_sub_categories.exp_sub_category')
                            ->get();
        // print_r($expense_data);
        // die;
        return view('adminPanel.expense.sub_cateory_wise_expense',compact('expense_data','sub_category_data'));
        
    }
/*
|--------------------------------------------------------------------------
| Sub Category Wise Expense Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Date Wise Expense Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to get Expense data from Date wise for Our Clients.
*/
public function date_wise_expense(Request $request){

        $expense_data = DB::table('expenses')
                            ->join('expense_categories', 'expense_categories.id', '=', 'expenses.category_id')// joining the contacts table , where user_id and contact_user_id are same
                            ->join('expense_sub_categories', 'expense_sub_categories.id', '=', 'expenses.sub_category_id')// joining the contacts table , where user_id and contact_user_id are same
                            ->join('cash_accounts', 'cash_accounts.id', '=', 'expenses.account_id')// joining the contacts table , where user_id and contact_user_id are same
                            ->whereBetween('date', [$request->start_date,$request->end_date])
                            ->select('expenses.*', 'expense_categories.exp_category_name','cash_accounts.account_name','cash_accounts.account_number','expense_sub_categories.exp_sub_category')
                            ->get();
        // print_r($expense_data);
        // die;
        return view('adminPanel.expense.date_wise_expense',compact('expense_data','request'));
        
    }
/*
|--------------------------------------------------------------------------
| Date Wise Expense Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Cash Account Wise Expense Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to get Expense data from Cash Account wise for Our Clients.
*/
public function cash_account_wise_expense(Request $request){

        $expense_data = DB::table('expenses')
                            ->join('expense_categories', 'expense_categories.id', '=', 'expenses.category_id')// joining the contacts table , where user_id and contact_user_id are same
                            ->join('expense_sub_categories', 'expense_sub_categories.id', '=', 'expenses.sub_category_id')// joining the contacts table , where user_id and contact_user_id are same
                            ->join('cash_accounts', 'cash_accounts.id', '=', 'expenses.account_id')// joining the contacts table , where user_id and contact_user_id are same
                            ->where('account_id', $request->account_id)
                            ->select('expenses.*', 'expense_categories.exp_category_name','cash_accounts.account_name','cash_accounts.account_number','expense_sub_categories.exp_sub_category')
                            ->get();

        $account_data =  CashAccounts::find($request->account_id);
        // print_r($expense_data);
        // die;
        return view('adminPanel.expense.cash_account_wise_expense',compact('expense_data','request','account_data'));
        
    }
/*
|--------------------------------------------------------------------------
| Cash Account Wise Expense Function Ended
|--------------------------------------------------------------------------
*/ 
/*
|--------------------------------------------------------------------------
| Print All Expense Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Print All Expense.
*/
public function print_all_expense(Request $request){

        $expense_data = DB::table('expenses')
                            ->join('expense_categories', 'expense_categories.id', '=', 'expenses.category_id')// joining the contacts table , where user_id and contact_user_id are same
                            ->join('expense_sub_categories', 'expense_sub_categories.id', '=', 'expenses.sub_category_id')// joining the contacts table , where user_id and contact_user_id are same
                            ->join('cash_accounts', 'cash_accounts.id', '=', 'expenses.account_id')// joining the contacts table , where user_id and contact_user_id are same
                            ->select('expenses.*', 'expense_categories.exp_category_name','cash_accounts.account_name','cash_accounts.account_number','expense_sub_categories.exp_sub_category')
                            ->orderBy("id",'asc')
                            ->get();
        // print_r($expense_data);
        // die;
        return view('adminPanel.expense.print_all_expense',compact('expense_data'));
        
    }
/*
|--------------------------------------------------------------------------
| Print All Expense Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Expense Reports Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Reports Expense.
*/
public function expense_reports(){
        $allCategories = expenseCategory::all();
        $CashAccountsdata = CashAccounts::all();
        return view('adminPanel.expense.expense_reports',compact('allCategories','CashAccountsdata'));

    }
/*
|--------------------------------------------------------------------------
| Expense Reports Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Expense Categories Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to display expense categories.
*/
public function expense_categories(Request $request){
         $userData = CustomerSubcription::where('Auth_key',$request->token)
                                            ->where('status',1)
                                            ->select('id','status')->first();
        if($userData){
             $allCategories = expenseCategory::where('customer_id',$userData->id)->get();
             return response()->json([
                'status'=>'success',
                'allCategories'=>$allCategories
            ]);
             
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>''
            ]);
        }
       
    }
    /*
|--------------------------------------------------------------------------
| Expense Categories Function End
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Expense Categories Sub Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to display expense categories Sub.
*/
public function expense_sub_categories(Request $request){
      
        $userData = CustomerSubcription::where('Auth_key',$request->token)
                                            ->where('status',1)
                                            ->select('id','status')->first();
        if($userData){
             $allCategories = expenseCategory::where('customer_id',$userData->id)->get();;
             $allSubCategories = expenseSubCategory::join('expense_categories','expense_sub_categories.category_id','expense_categories.id')
                                                        ->where('expense_sub_categories.customer_id',$userData->id)
                                                        ->select('expense_sub_categories.*','expense_categories.exp_category_name')
                                                        
                                                        ->get();
            //  print_r($allSubCategories->categoryOf->exp_category_name);
            //  die;
            //  categoryOf
             return response()->json([
                'status'=>'success',
                'allCategories'=>$allCategories,
                'allSubCategories'=>$allSubCategories
            ]);
             
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>''
            ]);
        }
    }
/*
|--------------------------------------------------------------------------
| Expense Categories Sub Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Store Categories Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to store data from database in Store Category.
*/
public function storeCategory(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)
                                            ->where('status',1)
                                            ->select('id','status')->first();
        if($userData){
            $request_data = json_decode($request->request_data);
            $expenseCategory =  new expenseCategory;
            $expenseCategory->exp_category_name = $request_data->exp_category_name;
            $expenseCategory->customer_id = $userData->id;
            $result = $expenseCategory->save();
            if($result){
                return response()->json([
                    'status'=>'success',
                ]);
            }else{
               return response()->json([
                    'status'=>'error',
                ]);
            }
        
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>''
            ]);
        }
        //
        
        // print_r($request->all());
    }
/*
|--------------------------------------------------------------------------
| Store Categories Function ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Expense Sub Categories Submit Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to store data from database in Expense Sub Catigory.
*/
public function expense_sub_cat_submit(Request $request){
        //
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)
                                            ->where('status',1)
                                            ->select('id','status')->first();
        if($userData){
            $request_data = json_decode($request->request_data);
            
            $expensesubCategory =  new expenseSubCategory;
            $expensesubCategory->exp_sub_category = $request_data->exp_sub_category;
            $expensesubCategory->category_id = $request_data->category_id;
            $expensesubCategory->user_id = 0;
            $expensesubCategory->customer_id = $userData->id;
            $result = $expensesubCategory->save();
            
            if($result){
                return response()->json([
                    'status'=>'success',
                ]);
            }else{
               return response()->json([
                    'status'=>'error',
                ]);
            }
        
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>''
            ]);
        }

    }
/*
|--------------------------------------------------------------------------
| Expense Sub Categories Submit Function Ended
|--------------------------------------------------------------------------
*/ 
/*
|--------------------------------------------------------------------------
| Fetch Sub Categories Submit Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to display Sub category List.
*/
public function fetch_sub_category(Request $request){
         $userData = CustomerSubcription::where('Auth_key',$request->token)
                                            ->where('status',1)
                                            ->select('id','status')->first();
        if($userData){
            $sub_categories = expenseSubCategory::where('category_id',$request->category_id)
            ->select('id','exp_sub_category')
            ->get();
            

            if($sub_categories){
                return response()->json([
                    'status'=>'success',
                    'sub_categories'=>$sub_categories
                ]);
            }else{
               return response()->json([
                    'status'=>'error',
                     'sub_categories'=>[]
                ]);
            }
        
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>''
            ]);
        }
        
        
        // print_r($location_socities);
        // echo "Socitites Function is call now ";
    }
/*
|--------------------------------------------------------------------------
| Fetch Sub Categories Submit Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Store Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Store the data from Database in expense.
*/
public function store(Request $request){
        //
        $userData = CustomerSubcription::where('Auth_key',$request->token)
                                            ->where('status',1)
                                            ->select('id','status')->first();
        if($userData){
            $request_data = json_decode($request->request_data);
            
                $expenseObj = new expense;
                $expenseObj->exp_name = $request_data->exp_name;
                $expenseObj->total_amount = $request_data->total_amount;
                $expenseObj->date = $request_data->date;
                $expenseObj->account_id = $request_data->account_id;
                $expenseObj->category_id = $request_data->category_id;
                if(isset($request_data->sub_category_id)){
                    $expenseObj->sub_category_id = $request_data->sub_category_id;
                }
                
                $expenseObj->user_id = 0;
                $expenseObj->customer_id = $userData->id;
        
                try {
                    DB::transaction(function() use ($expenseObj,$request_data,$userData) {
                        $expenseObj->save();
                        
                        $cash_account_data = DB::table('cash_accounts')->where('id',$request_data->account_id)->select('id','balance','name')->first();
        
                        $updatedBalance =  $cash_account_data->balance - $request_data->total_amount;
                         
                        DB::table('cash_accountledgers')->insert([
                                            'account_id'=>$cash_account_data->id,
                                            'payment'=>$request_data->total_amount,
                                            'balance'=>$updatedBalance,
                                            'expense_id'=>$expenseObj->id,
                                            'customer_id'=>$userData->id,
                                            'date'=>$request_data->date,
                                            ]);
                                            
                        DB::table('cash_accounts')->where('id',$cash_account_data->id)->update(['balance'=>$updatedBalance]);
                        
                        
                       
                    });
                    
                    return response()->json([
                            'status'=>'success',
                        ]);
                    
                } catch (\PDOException $e) {
                    // Woopsy
                    echo $e;
                    DB::rollBack();
                     return response()->json([
                            'status'=>'error',
                        ]);
                }
            
            
        
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>''
            ]);
        }
     

      
    }
/*
|--------------------------------------------------------------------------
| Store Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| ExpenseController Function Ended
|--------------------------------------------------------------------------
*/
}
