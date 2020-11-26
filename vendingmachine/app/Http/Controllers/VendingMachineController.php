<?php

namespace App\Http\Controllers;

Use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

Class VendingMachineController extends Controller 
{
    /**
     * Get all available products
     */
    public function getProducts()
    {
        return config('vending-machine.products');
    }

    /**
     * Get all valid coins
     */
    public function getValidCoins()
    {
        return config('vending-machine.valid_coins');
    }

    /**
     * Show vending machine page
     */
    public function showPage($change = null)
    {
        return view('vendingmachine', ['products' => $this->getProducts(), 'valid_coins' => $this->getValidCoins(), 'change' => $change ]);
    }

    /**
     * Calculate change based on selected product and money input
     */
    public function calculateChange(Request $request)
    {
        $valid_coins = $this->getValidCoins();
        $available_products =  $this->getProducts();

        //get the user coin input
        $chosen_coins = [];
        foreach($valid_coins as $valid_coin){
            if( $request->input('coin'.$valid_coin) != null && $request->input('coin'.$valid_coin) > 0){
                $chosen_coins += [$valid_coin => $request->input('coin'.$valid_coin) ];
            } 
        }

        //calculate total user input
        $input_total = 0;
        foreach($chosen_coins as $coin=>$amount){
            if(is_numeric($amount) ){
                $input_total += (abs($amount) * $coin);
            }else{
                $this->showPage("There was an error while processing the form, please try again");
            }
        }

        //find selected product
        $selected_product = array_search($request->input('product'), array_column($available_products, 'product_name'));
        
        if($selected_product != FALSE){
            $spare_change_cents = $input_total - intval($available_products[$selected_product+1]['price'] * 100);
            if($spare_change_cents < 0){
                return $this->showPage(["You need to input more coins to be able to purchase the selected product "]);
            }

            //calculate efficient spare change
            $result = [
                'Product: ' . $available_products[$selected_product+1]['product_name'],
                'Amount inserted: ' . number_format($input_total/100,2),
                'Price product: € ' . $available_products[$selected_product+1]['price'],
                'Change: ',
            ];

            foreach(array_reverse($valid_coins) as $coin){
                if($spare_change_cents == 0){
                    return $this->showPage($result);
                }
                //amount of coins of the current amount that can be used for change
                $coin_amount = intval($spare_change_cents/$coin);
                if($coin_amount > 0){
                    //add result to result array
                $result[] = $coin_amount . 'x € ' . number_format( $coin/100,2);

                //update spare change cents to remainder
                $spare_change_cents = $spare_change_cents % $coin;  
                }
            }
            return $this->showPage($result);
        }else{
           return $this->showPage(["There was an error selecting the product, please try again"]);
        }   
    }
}